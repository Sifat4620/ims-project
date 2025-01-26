<?php

namespace App\Http\Controllers;

use App\Models\Product;  // Import the Product model
use Illuminate\Http\Request;

class CoreDataController extends Controller
{
    public function index()
    {
        // Fetch products with pagination (10 products per page)
        $products = Product::paginate(10);

        // Pass the products and title to the view
        $title = 'Core Data';
        return view('master_product_data', compact('title', 'products'));
    }
}
