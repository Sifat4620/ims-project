<?php

namespace App\Http\Controllers;

use App\Models\Product;  
use Illuminate\Http\Request;
use App\Models\Item;


class BarcodeController extends Controller
{
    // Show all products with option to generate/create barcode
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $search = $request->get('search', '');
        $searchField = $request->get('search_field', 'all');
        $issueStatus = $request->get('issue_status', 'No');  // Default to 'No'

        $query = Item::query();

        // Apply issue status filter
        if ($issueStatus !== '') {
            $query->where('status', $issueStatus);
        }

        // Apply search logic
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

        // Paginate results with query string parameters kept
        $items = $query->paginate($perPage)->withQueryString();

        $title = 'Item List - Create Barcodes';

        // Pass all variables to your Blade view
        return view('barcode.product-barcode-index', compact('title', 'items', 'search', 'searchField', 'perPage', 'issueStatus'));
    }



    // Show list for barcode download (could be the same or different)
    public function download()
    {
        $products = Product::paginate(10);
        $title = 'All Products List with Barcodes to Download';

        return view('barcode.product-barcode-download', compact('title', 'products'));
    }
}
