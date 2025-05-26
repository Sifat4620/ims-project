<?php

namespace App\Http\Controllers;

use App\Models\Invoice; // Make sure to use your Invoice model
use Illuminate\Http\Request;

class DeliveryChallanController extends Controller
{
    public function index()
    {
        $title = 'Delivery Challan';

        // Fetch all invoices with their associated items (using eager loading for the 'item' relation)
        $invoices = Invoice::with('item')->get();

        // Grouping the invoices by category, brand, model_no, and classification
        $groupedInvoices = $invoices->groupBy(function ($invoice) {
            return $invoice->invoice_number . '|' . $invoice->item->category . '|' . $invoice->item->brand . '|' . $invoice->item->model_no . '|' . $invoice->item->specification;
        });

        // Pass the grouped invoices to the view
        return view('delivery-challan-data.delivery-challan-data', compact('title', 'groupedInvoices'));
    }
}
