<?php

namespace App\Http\Controllers;

use App\Models\Item; // Import the Item model
use Illuminate\Http\Request;

class InStockController extends Controller
{
    public function index()
    {
        // Fetch all items from the database
        $items = Item::all();  // Get all items from the 'items' table

        $title = 'In Stock';
        return view('item_received_data', compact('title', 'items'));
    }
}
