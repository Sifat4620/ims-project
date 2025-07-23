<?php

namespace App\Http\Controllers;

use App\Models\FaultyItem;
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

        // Fetch defective items with selective fields and related item data
        $defectiveItems = FaultyItem::select('id', 'item_id', 'created_at')
            ->with(['item:id,brand,category,serial_no,model_no'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('defective-data.defective-data', compact('title', 'defectiveItems'));
    }
}
