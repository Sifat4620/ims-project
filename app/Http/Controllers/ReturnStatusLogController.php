<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Invoice;

class ReturnStatusLogController extends Controller
{
    /**
     * Display the return log management page with optional search functionality.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = 'Return Log Management';
        $search = $request->input('search', ''); // Retrieve the search term from the request or default to empty string

        // Fetch all invoices with their related items, optionally filtered by the search term
        $invoices = Invoice::with('item')
                    ->when($search, function ($query) use ($search) {
                        // Add your search filters here
                        $query->where('invoice_number', 'like', "%{$search}%")
                              ->orWhereHas('item', function ($query) use ($search) {
                                  $query->where('category', 'like', "%{$search}%")
                                        ->orWhere('brand', 'like', "%{$search}%")
                                        ->orWhere('model_no', 'like', "%{$search}%")
                                        ->orWhere('specification', 'like', "%{$search}%");
                              });
                    })
                    ->get();




                    
                    $groupedInvoices = $invoices->groupBy(function ($invoice) {
                        return $invoice->invoice_number . '|' . $invoice->item->category . '|' . $invoice->item->brand . '|' . $invoice->item->model_no . '|' . $invoice->item->classification;
                    });
                    
                    $sections = $groupedInvoices->map(function ($group) {
                        $firstInvoice = $group->first();
                        $serialNos = [];
                        $allFaultyOrReturn = true; // Assume all are "Faulty" or "Return" until proven otherwise
                    
                        foreach ($group as $invoice) {
                            $item = $invoice->item; // Related item from the relationship
                            if ($item && $item->serial_no) {
                                $serials = explode(',', $item->serial_no);
                    
                                foreach ($serials as $serial) {
                                    // Check the status of the current item/serial
                                    if (!in_array($item->status, ['Faulty', 'Return'])) {
                                        $serialNos[] = $serial; // Collect valid serial numbers
                                        $allFaultyOrReturn = false; // At least one serial is valid
                                    }
                                }
                            }
                        }
                    
                        if ($allFaultyOrReturn) {
                            return null; // Skip this group if all serials are "Faulty" or "Return"
                        }
                    
                        $serialNos = array_unique($serialNos); // Ensure serial numbers are unique
                        return [
                            'invoice_number' => $firstInvoice->invoice_number,
                            'category' => $firstInvoice->item->category,
                            'brand' => $firstInvoice->item->brand,
                            'model_no' => $firstInvoice->item->model_no,
                            'classification' => $firstInvoice->item->classification,
                            'serial_numbers' => $serialNos,
                            'qty' => count($serialNos),
                            'challan_date' => $firstInvoice->created_at->format('d-m-Y'),
                            'customer_address' => $firstInvoice->customer_address,
                            'item_id' => $firstInvoice->item->id,
                            'item_details' => $firstInvoice->item->toArray(),
                        ];
                    })->filter(); // Remove null entries from the results
                    
                    if ($request->wantsJson()) {
                        return response()->json([
                            'status' => 'success',
                            'data' => $sections,
                        ]);
                    }
                    
                    // Return the view with the grouped data
                    return view('item-return-log.item-return-log', compact('title', 'sections', 'search'));
                    
    }



    
    /**
     * Update the status of a specific serial number.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'serial_number' => 'required|string',
            'status' => 'required|in:Faulty,Return',
        ]);

        // Find the item that contains the serial number
        $item = Item::where('serial_no', 'LIKE', '%' . $validated['serial_number'] . '%')->first();

        if (!$item) {
            return response()->json(['status' => 'error', 'message' => 'Item not found.'], 404);
        }

        // Update the status
        $item->status = $validated['status'];
        $item->save();

        return response()->json([
            'status' => 'success',
            'message' => "Serial number '{$validated['serial_number']}' updated to '{$validated['status']}'.",
            'data' => $item,
        ]);
    }



    
    // Upgrade this status code
    public function updateItemStatus(Request $request)
    {
        // Validate the incoming data
        $data = $request->validate([
            'selected_action.serial_number' => 'required|string',
            'selected_action.status' => 'required|in:Faulty,Return', // Ensure status is either 'Faulty' or 'Return'
        ]);
    
        try {
            // Start a transaction
            \DB::beginTransaction();
    
            // Attempt to find the item by serial number
            $item = Item::where('serial_no', 'LIKE', '%' . $data['selected_action']['serial_number'] . '%')->firstOrFail();
    
            // Update the item's status
            $item->status = $data['selected_action']['status'];
            $item->save();
    
            // Commit the transaction
            \DB::commit();
    
            // Return a success response with item details
            return response()->json([
                'status' => 'success',
                'message' => 'Item status updated successfully.',
                'data' => [
                    'item_id' => $item->id,
                    'serial_number' => $item->serial_no,
                    'new_status' => $item->status,
                ]
            ]);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Item not found.']);
        }  catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    

}
