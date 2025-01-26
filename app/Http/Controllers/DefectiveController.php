<?php

namespace App\Http\Controllers;

use App\Models\FaultyItem; // Import the FaultyItem model
use Illuminate\Http\Request;

class DefectiveController extends Controller
{
    /**
     * Display a listing of defective items.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Defective Items';

        // Fetch defective items with associated item details
        $defectiveItems = FaultyItem::with('item:id,brand,category')->get();

        // Pass the data to the view
        return view('defective-data', compact('title', 'defectiveItems'));
    }
}
