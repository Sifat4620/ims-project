<?php

namespace App\Http\Controllers;

use App\Models\Returns; // Import the Returns model
use Illuminate\Http\Request;

class ReturnableController extends Controller
{
    /**
     * Display a listing of returnable items.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Returnable Items';

        // Fetch returns with associated item details (brand, category)
        $returns = Returns::with('item:id,brand,category')->get();

        // Pass the data to the view
        return view('returnable', compact('title', 'returns'));
    }
}
