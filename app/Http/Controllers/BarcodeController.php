<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemBarcode;
use App\Models\Product;
use Milon\Barcode\DNS1D;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('perPage', 10);
        $search = $request->get('search', '');
        $searchField = $request->get('search_field', 'all');
        $issueStatus = $request->get('issue_status', '');

        $query = Item::query();

        if ($issueStatus !== '') {
            $query->where('status', $issueStatus);
        }

        if ($search) {
            $query->where(function ($q) use ($search, $searchField) {
                if ($searchField === 'all') {
                    $q->where('lc_po_type', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('model_no', 'like', "%{$search}%")
                      ->orWhere('serial_no', 'like', "%{$search}%");
                } else {
                    $q->where($searchField, 'like', "%{$search}%");
                }
            });
        }

        $items = $query->with('itemBarcode')->paginate($perPage)->withQueryString();
        $title = 'Item Barcode Generator';

        return view('barcode.product-barcode-index', compact('title', 'items', 'search', 'searchField', 'perPage', 'issueStatus'));
    }

    public function generate($itemId)
    {
        $item = Item::findOrFail($itemId);

        if ($this->handleBarcodeGeneration($item)) {
            return redirect()->route('barcode.index')->with('success', 'Barcode generated and stored successfully.');
        }

        return back()->with('error', 'Failed to generate barcode for this item.');
    }

    public function download($id)
    {
        $item = Item::findOrFail($id);

        $barcodeData = ItemBarcode::where('item_id', $item->id)->first();

        if (!$barcodeData || !$barcodeData->barcode_string) {
            $this->handleBarcodeGeneration($item);
            $barcodeData = ItemBarcode::where('item_id', $item->id)->first();

            if (!$barcodeData || !$barcodeData->barcode_string) {
                return back()->with('error', 'Failed to auto-generate barcode for download.');
            }
        }

        $barcodeString = $barcodeData->barcode_string;

        $barcode = new DNS1D();
        $svg = $barcode->getBarcodeSVG($barcodeString, 'C128', 2, 60);

        $folder = storage_path('app/public/barcodes');
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $filename = 'barcode_' . $item->id . '.svg';
        $fullPath = $folder . '/' . $filename;

        file_put_contents($fullPath, $svg);

        return response()->download($fullPath, $filename, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }

    public function bulkPdf(Request $request)
    {
        $selectedIds = $request->input('selected_ids');

        if (!$selectedIds || !is_array($selectedIds)) {
            return back()->with('error', 'No items selected for barcode download.');
        }

        $selectedIdString = collect($selectedIds)->join('-');

        $barcodes = ItemBarcode::whereIn('item_id', $selectedIds)->get()->groupBy('item_id');
        $generator = new BarcodeGeneratorPNG();

        $items = Item::whereIn('id', $selectedIds)->get()->map(function ($item) use ($barcodes, $generator) {
            $barcodeString = optional($barcodes->get($item->id))->first()->barcode_string ?? null;
            $barcodePng = $barcodeString
                ? 'data:image/png;base64,' . base64_encode(
                    $generator->getBarcode($barcodeString, $generator::TYPE_CODE_128)
                )
                : null;

            return [
                'item_id' => $item->id,
                'item_name' => $item->name ?? 'Unknown',
                'barcode_string' => $barcodeString,
                'barcode_png' => $barcodePng,
            ];
        });

        if ($items->isEmpty()) {
            return back()->with('error', 'No valid barcodes found.');
        }

        $pdf = Pdf::loadView('barcode.bulk-pdf', compact('items'))->setPaper('a4');
        $timestamp = now()->format('Y-m-d_H-i');
        $filename = "{$timestamp}_items-{$selectedIdString}.pdf";

        return $pdf->download($filename);
    }

    public function doubleCheck(Request $request)
    {
        $title = 'Double Check Barcode';
        return view('barcode.double-check', compact('title'));
    }

    public function ajaxDoubleCheck(Request $request)
    {
        $barcode = $request->input('barcode');

        $item = Item::whereHas('ajaxBarcode', function ($query) use ($barcode) {
            $query->where('barcode_string', $barcode);
        })->with('itemBarcode')->first();

        if ($item && $item->itemBarcode) {
            return response()->json([
                'success' => true,
                'item' => [
                    'lc_po_type' => $item->lc_po_type,
                    'brand' => $item->brand,
                    'model_no' => $item->model_no,
                    'serial_no' => $item->serial_no,
                    'condition' => $item->condition,
                    'status' => $item->status,
                    'barcode_string' => $item->ajaxBarcode->barcode_string,
                    'barcode_svg' => \DNS1D::getBarcodeSVG($item->ajaxBarcode->barcode_string, 'C128', 2, 50),
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No item found for this barcode.',
        ]);
    }

    public function ajaxGenerate(Request $request)
    {
        $item = Item::with('itemBarcode')->find($request->item_id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }

        if ($item->itemBarcode && $item->itemBarcode->isNotEmpty()) {
            return response()->json(['success' => false, 'message' => 'Barcode already exists']);
        }

        $success = $this->handleBarcodeGeneration($item);

        if (!$success) {
            return response()->json(['success' => false, 'message' => 'Failed to generate barcode']);
        }

        $barcode = $item->fresh()->itemBarcode->barcode_string;

        return response()->json([
            'success' => true,
            'barcode_string' => $barcode,
            'barcode_svg' => \DNS1D::getBarcodeSVG($barcode, 'C128', 2, 80),
        ]);
    }

    private function handleBarcodeGeneration(Item $item): bool
    {
        $originalSerial = preg_replace('/\D/', '', $item->serial_no);

        if (empty($originalSerial)) {
            return false;
        }

        $randomSerial = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $product = Product::where('product_brand', $item->brand)
            ->where('category', $item->category)
            ->first();

        if (!$product) {
            return false;
        }

        $productCode = $product->product_id;
        $prefix = substr(strrev(now()->format('Y')), 0, 2);
        $barcodeString = $productCode . $prefix . $randomSerial;

        ItemBarcode::updateOrCreate(
            ['item_id' => $item->id],
            [
                'product_id' => $product->id,
                'barcode_string' => $barcodeString,
                'downloaded_at' => now(),
            ]
        );

        return true;
    }
    


     /**
     * NEW: Bulk flip status from "Processing" to "Delivery" by scanned barcodes.
     * Expected payload: { items: [ { barcode: "123...", quantity: 1 }, ... ] }
     */
    public function bulkStatus(Request $request)
    {
        $items = $request->input('items', []);

        if (empty($items) || !is_array($items)) {
            return response()->json(['success' => false, 'message' => 'No items provided.'], 422);
        }

        $barcodes = collect($items)
            ->pluck('barcode')
            ->filter()
            ->unique()
            ->values();

        if ($barcodes->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No valid barcodes provided.'], 422);
        }

        // Find items by barcode via ItemBarcode relation
        $matchingItems = \App\Models\Item::whereHas('itemBarcode', function ($q) use ($barcodes) {
                $q->whereIn('barcode_string', $barcodes);
            })
            ->with(['itemBarcode' => function ($q) {
                $q->select('id', 'item_id', 'barcode_string');
            }])
            ->get(['id', 'status']);

        if ($matchingItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No items found for provided barcodes.'], 404);
        }

        // Update any item that is NOT already "Processing"
        $toUpdateIds = $matchingItems
            ->filter(fn ($i) => $i->status !== 'Processing')
            ->pluck('id')
            ->all();

        $updatedCount = 0;
        if (!empty($toUpdateIds)) {
            $updatedCount = \App\Models\Item::whereIn('id', $toUpdateIds)
                ->update(['status' => 'Processing', 'updated_at' => now()]);
        }

        $result = [
            'updated'           => $updatedCount,
            'total_matched'     => $matchingItems->count(),
            'already_processing'=> $matchingItems->where('status', 'Processing')->count(),
            'changed_from'      => $matchingItems
                                    ->filter(fn ($i) => $i->status !== 'Processing')
                                    ->pluck('status')
                                    ->unique()
                                    ->values(),
        ];

        return response()->json(['success' => true, 'result' => $result]);
    }



}
