<?php

namespace App\Http\Controllers;

use App\Models\Returns;
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

        // Fetch returns with selected fields and related item details
        $returns = Returns::select('id', 'lc_po_type', 'created_at', 'item_id')
            ->with(['item:id,brand,category,serial_no,model_no'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('returnable.returnable', compact('title', 'returns'));
    }
}
