<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Invoice;

class ConfirmInvoiceController extends Controller
{
    protected $invoiceNumber;


    public function __construct()
    {
        // Fetch the latest invoice number and increment by one, or default to 1 if none exist
        $latestInvoiceNumber = Invoice::latest('created_at')->value('invoice_number');
        $this->invoiceNumber = $latestInvoiceNumber ? $latestInvoiceNumber + 1 : 1;
    }

    // Display the confirmation page
    public function index()
    {
        $title = 'Confirm Invoice';
        return view('invoice-add', compact('title'));
    }

    // Handle the POST request to confirm invoices
    public function store(Request $request)
    {
        // Validate the request to ensure 'selected_items' is an array
        $request->validate([
            'selected_items' => 'required|array', // Ensure the input is an array
            'selected_items.*' => 'integer|exists:items,id', // Each item ID must exist in the database
        ]);

        // Retrieve selected item IDs from the request
        $selectedItemIds = $request->input('selected_items', []);

        // Fetch only the necessary items from the database
        $selectedItems = Item::whereIn('id', $selectedItemIds)
            ->get(['id', 'category', 'brand', 'model_no', 'serial_no', 'specification']);

        // Store the invoice number in the session for use in subsequent requests
        session(['invoiceNumber' => $this->invoiceNumber]);

        // Pass the necessary data to the view
        $title = 'Confirm Invoice';
        return view('invoice-add', [
            'selectedItems' => $selectedItems,
            'title' => $title,
            'invoiceNumber' => $this->invoiceNumber,
        ]);
    }

    public function showInvoiceNumber()
    {
        // Fetch the latest invoice number from the database and increment it by one
        $latestInvoiceNumber = Invoice::latest('created_at')->value('invoice_number');
    
        // If no invoice exists yet, start from 1; otherwise, increment the latest number by one
        return $latestInvoiceNumber ? $latestInvoiceNumber + 1 : 1;
    }
}
