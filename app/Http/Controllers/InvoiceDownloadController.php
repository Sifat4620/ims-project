<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class InvoiceDownloadController extends Controller
{
    /**
     * Display the invoice preview page.
     */
    public function index()
    {
        $title = 'Download Challan';
        return view('challan-preview.challan-preview', compact('title'));
    }

    /**
     * Handle form submission and pass data to the invoice-preview view.
     */
    public function store(Request $request)
    {
        // Retrieve IDs of selected items from the request
        $selectedItemIds = $request->input('selected_items', []);
        
        // Debugging: Log the selected item IDs to check what is received from the request
        \Log::info('Selected Item IDs:', $selectedItemIds);

        // Update the status of selected items to "Yes"
        Item::whereIn('id', $selectedItemIds)->update(['status' => 'Yes']);

        // Fetch detailed data of selected items with status "Yes" from the database
        $selectedItems = Item::whereIn('id', $selectedItemIds)
                            ->where('status', 'Yes')
                            ->get(['id', 'category', 'brand', 'model_no', 'serial_no', 'specification']);
        
        // Debugging: Log the fetched data from the database to check its structure and contents
        \Log::info('Fetched Items Details:', $selectedItems->toArray());

        // Group items by category, brand, model_no, and specification
        $groupedItems = $selectedItems->groupBy(function ($item) {
            return $item->category . '|' . $item->brand . '|' . $item->model_no . '|' . $item->specification;
        });

        // Prepare the data array for the view, including grouped items
        $data = $this->prepareInvoiceData($request, $groupedItems);

        // Insert invoice data into the database
        $this->insertInvoiceData($data);

        // Render the invoice-preview view with the prepared data
        return view('challan-preview.challan-preview', $data);
    }


    //  Check PO Number 

    /**
     * Prepare the data array for the view and database insertion.
     */
    protected function prepareInvoiceData(Request $request, $selectedItems)
    {
        return [
            'title' => 'Download Challan',
            'invoice_number' => $request->input('invoice_number'),
            'selected_items' => $selectedItems,
            'customer_address' => $request->input('customer_address'),
            'date_issued' => $request->input('date_issued'),
            'po_number' => $request->input('po_number'),
            'po_date' => $request->input('po_date'),
            'part_numbers' => $request->input('part_no', []),
            'item_descriptions' => $request->input('item_description', []),
            'uoms' => $request->input('uom', []),
            'quantities' => $request->input('quantity', []),
            'auth_name' => $request->input('auth_name'),
            'auth_designation' => $request->input('auth_designation'),
            'auth_organization' => $request->input('auth_organization'),
            'auth_mobile' => $request->input('auth_mobile'),
            'rec_name' => $request->input('rec_name'),
            'rec_designation' => $request->input('rec_designation'),
            'rec_organization' => $request->input('rec_organization'),
        ];
    }

    /**
     * Insert the prepared invoice data into the database.
     */
    protected function insertInvoiceData($data)
    {
        foreach ($data['selected_items'] as $items) {
            foreach ($items as $item) {
                \DB::table('invoices')->insert([
                    'invoice_number' => $data['invoice_number'], // same for all inserts
                    'customer_address' => $data['customer_address'],
                    'authorized_name' => $data['auth_name'],
                    'authorized_designation' => $data['auth_designation'],
                    'authorized_mobile' => $data['auth_mobile'],
                    'recipient_name' => $data['rec_name'],
                    'recipient_designation' => $data['rec_designation'],
                    'recipient_organization' => $data['rec_organization'],
                    'item_id' => $item->id, // Access the individual item's id
                    'created_at' => now(),
                    'updated_at' => now(),
                    'date_issued' => $data['date_issued'] ?? now(),
                ]);
            }
        }
    }




    public function downloadPdf(Request $request)
    {
        $invoiceNumber = trim((string) $request->input('invoice_number'));

        $invalidValues = ['', 'null', 'n/a', 'undefined'];

        if (in_array(strtolower($invoiceNumber), $invalidValues, true)) {
            // Generate random invoice number starting with ZXY + random 6 digits
            $invoiceNumber = 'ZXY' . random_int(100000, 999999);
        }

        // Now fetch invoice records for this invoice number
        $invoiceRecords = \DB::table('invoices')
                            ->where('invoice_number', $invoiceNumber)
                            ->get();

        if ($invoiceRecords->isEmpty()) {
            abort(404, 'Invoice not found.');
        }

        // Extract first record for invoice info
        $firstRecord = $invoiceRecords->first();

        // Group items for PDF data
        $groupedItems = $invoiceRecords->groupBy(function ($item) {
            return $item->category . '|' . $item->brand . '|' . $item->model_no . '|' . $item->specification;
        });

        $data = [
            'invoice_number' => $invoiceNumber,
            'customer_address' => $firstRecord->customer_address,
            'date_issued' => $firstRecord->date_issued ? \Carbon\Carbon::parse($firstRecord->date_issued)->format('d M Y') : now()->format('d M Y'),
            'po_number' => $firstRecord->po_number ?? '',
            'po_date' => $firstRecord->po_date ?? '',
            'auth_name' => $firstRecord->authorized_name,
            'auth_designation' => $firstRecord->authorized_designation,
            'auth_mobile' => $firstRecord->authorized_mobile,
            'rec_name' => $firstRecord->recipient_name,
            'rec_designation' => $firstRecord->recipient_designation,
            'rec_organization' => $firstRecord->recipient_organization,
            'selected_items' => $groupedItems,
        ];

        $pdf = Pdf::loadView('pdf.delivery-challan', $data);

        $fileName = "delivery-challan-{$invoiceNumber}.pdf";

        return $pdf->download($fileName);
    }


}
