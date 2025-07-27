<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CoreDataController extends Controller
{
    public function index()
    {
        // Fetch all products (no pagination)
        $products = Product::all();

        // Pass the products and title to the view
        $title = 'Core Data';
        return view('master-product-data.master-product-data', compact('title', 'products'));
    }
}
