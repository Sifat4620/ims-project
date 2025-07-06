<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemBarcode;
use Milon\Barcode\Facades\DNS1DFacade as Barcode;
use App\Models\Product;

class BarcodeController extends Controller
{
    /**
     * Display paginated list of items with optional search and filters.
     */
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

    /**
     * Generate or update barcode for a specific item.
     * 
     * @param int $itemId
     * @return \Illuminate\View\View
     */
   public function generate($itemId)
    {
        // Find the item by ID or fail with 404
        $item = Item::findOrFail($itemId);

        // Extract numeric part of serial_no (remove any non-digit chars)
        $originalSerial = preg_replace('/\D/', '', $item->serial_no);

        if (empty($originalSerial)) {
            return back()->with('error', 'Invalid serial number for barcode generation.');
        }

        // Generate a zero-padded random 6-digit serial string
        $randomSerial = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Fetch product matching the item's brand and category
        $product = Product::where('product_brand', $item->brand)
            ->where('category', $item->category)
            ->first();

        if (!$product) {
            return back()->with('error', 'No matching product found for this item.');
        }

        // Use product_id (varchar) as product code for barcode prefix
        $productCode = $product->product_id;

        // Create a prefix using the first two digits of the reversed current year
        $prefix = substr(strrev(now()->format('Y')), 0, 2);

        // Construct the barcode string: product code + prefix + random serial
        $barcodeString = $productCode . $prefix . $randomSerial;

        // Store or update the barcode for this item
        \App\Models\ItemBarcode::updateOrCreate(
            ['item_id' => $item->id],
            [
                'product_id' => $product->id,       // FK: numeric ID for reference
                'barcode_string' => $barcodeString, // The generated barcode string
                'downloaded_at' => now(),           // Optional timestamp, adjust as needed
            ]
        );

        return redirect()
            ->route('barcode.index')
            ->with('success', 'Barcode generated and stored successfully.');
    }


    /**
     * Download barcode PNG image for an item.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $item = \App\Models\Item::findOrFail($id);
        $barcodeData = \App\Models\ItemBarcode::where('item_id', $item->id)->first();

        if (!$barcodeData || !$barcodeData->barcode_string) {
            return back()->with('error', 'No barcode found for this item.');
        }

        $barcodeString = $barcodeData->barcode_string;

        // Generate barcode PNG using Milon\Barcode
        $barcodeGenerator = new DNS1D();
        $barcodeGenerator->setStorPath(storage_path('framework/barcodes/'));

        // getBarcodePNG() returns base64 PNG string → decode it
        $pngData = base64_decode($barcodeGenerator->getBarcodePNG($barcodeString, 'C128'));

        return Response::make($pngData, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="barcode_' . $item->id . '.png"',
        ]);
    }

    /**
     * Double check barcode and retrieve item info.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function doubleCheck(Request $request)
    {
        $barcode = $request->input('barcode');

        $item = null;

        if ($barcode) {
            $item = Item::whereHas('itemBarcode', function ($query) use ($barcode) {
                $query->where('barcode_string', $barcode);
            })->with('itemBarcode')->first();
        }

        // If AJAX request, return JSON
        if ($request->ajax()) {
            if ($item) {
                return response()->json([
                    'success' => true,
                    'item' => [
                        'lc_po_type' => $item->lc_po_type,
                        'brand' => $item->brand,
                        'model_no' => $item->model_no,
                        'serial_no' => $item->serial_no,
                        'condition' => $item->condition,
                        'status' => $item->status,
                        'barcode_string' => $item->itemBarcode->barcode_string ?? '',
                        'barcode_svg' => DNS1D::getBarcodeSVG($item->itemBarcode->barcode_string ?? '', 'C128', 2, 50),
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No item found for this barcode.',
                ]);
            }
        }

        // If normal GET, show the page without scan results
        return view('barcode.double-check', ['title' => 'Double Check Section']);
    }


}
