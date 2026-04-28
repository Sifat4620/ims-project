<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Invoice;

class DeliveryChallanDownloadController extends Controller
{
    public function index()
    {
        $title = "Delivery Challan Download";

        // Fetch invoices with their related items
        $invoices = Invoice::with('item')->get();

        // Pass to view
        return view('delivery-challan.delivery-challan-download', compact('invoices', 'title'));
    }
}
