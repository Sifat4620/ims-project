<?php

namespace App\Http\Controllers;

use App\Models\Product;  // Assuming you have a Product model
use Illuminate\Http\Request;

class CoreInventoryPrimaryRecordsController extends Controller
{
    // Method to show the form
    public function primaryRecords()
    {
        $title = 'Primary Records';
        return view('master_item_input', compact('title'));
    }

    // Method to fetch and display products
    public function index()
    {
        // Fetch all products from the Product model
        $products = Product::all();  // Or use paginate() for pagination

        // Return the view with the products variable
        return view('product.index', compact('products'));  // Change 'product.index' to your view name
    }

    // Method to handle form submission and store data
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'product_id' => 'required|unique:products,product_id|max:255',
            'category' => 'required|string|max:255',
            'product_brand' => 'required|string|max:255',
            'entry_date' => 'required|date',
        ]);

        // Create a new product record
        Product::create([
            'product_id' => $request->product_id,
            'category' => $request->category,
            'product_brand' => $request->product_brand,
            'entry_date' => $request->entry_date,
        ]);

        // Redirect with a success message
        return redirect()->route('logistics.coredata')->with('success', 'Product added successfully!');
    }
}
