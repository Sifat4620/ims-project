<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemBarcode;
use Milon\Barcode\Facades\DNS1DFacade as Barcode;
use Carbon\Carbon;
use App\Models\Product;


class BarcodeController extends Controller
{
    /**
     * Display paginated list of items with search and filters.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10);
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
     * Generate barcode for a specific item.
     * Creates or updates the barcode record.
     */
    public function generate($itemId)
    {
        $item = Item::findOrFail($itemId);

        // Step 1: Get original serial number
        $originalSerial = $item->serial_no;

        // Step 2: Generate a new random serial number (6 digits)
        $randomSerial = mt_rand(100000, 999999);

        // Step 3: Get brand and category from item
        $brand = $item->brand;
        $category = $item->category;

        // Step 4: Find product to get product_id
        $product = Product::where('product_brand', $brand)
                        ->where('category', $category)
                        ->first();

        $productIdFromProductsTable = $product ? $product->product_id : null;

        // Step 5: Create the year-based prefix
        $year = now()->format('Y');        // e.g., 2025
        $reversed = strrev($year);         // "5202"
        $prefix = substr($reversed, 0, 2); // "52"

        // Step 6: Build barcode string with product ID before prefix
        $barcodeString = $productIdFromProductsTable . $prefix . $randomSerial;

        // Step 7: Original barcode string with product ID and original serial
        $originalBarcodeString = $productIdFromProductsTable . $prefix . $originalSerial;

        // Step 8: Generate barcode SVG
        $barcodeSVG = Barcode::getBarcodeSVG($barcodeString, 'C128', 2, 60);

        return view('barcode.show', [
            'barcodeSVG' => $barcodeSVG,
            'barcodeString' => $barcodeString,
            'originalBarcodeString' => $originalBarcodeString,
            'productId' => $productIdFromProductsTable,
            'itemId' => $item->id,
        ]);
    }





    /**
     * Download the barcode PNG image for a given item.
     */
    public function download($id)
    {
        $barcode = ItemBarcode::where('item_id', $id)->firstOrFail();

        // Generate barcode PNG base64 string
        $barcodePngBase64 = Barcode::getBarcodePNG($barcode->barcode_string, 'C128', 2, 80);

        $barcodeImage = base64_decode($barcodePngBase64);

        return response($barcodeImage)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="barcode_' . $barcode->item_id . '.png"');
    }
}
