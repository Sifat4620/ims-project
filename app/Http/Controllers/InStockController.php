<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class InStockController extends Controller
{
    public function index()
    {
        // Fetch all items with barcode relationship
        $items = Item::with('itemBarcode')->get();

        $title = 'In Stock';
        return view('item-received-data.item-received-data', compact('title', 'items'));
    }
}
