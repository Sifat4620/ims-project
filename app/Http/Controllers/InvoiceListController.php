<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class InvoiceListController extends Controller
{
    /**
     * Display the invoice list with search, filters, and specifically shows items with status "No" indicating non-availability.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10); // Items per page
        $searchQuery = $request->get('search', ''); // Search input
        $searchField = $request->get('search_field', 'all'); // Search field
        $issueStatus = $request->get('issue_status', ''); // Issue status

        $query = Item::query();

        // Filter only items where 'status' is 'No'
        $query->where('status', 'No');

        // Apply search logic
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery, $searchField) {
                if ($searchField == 'all') {
                    // Search across multiple fields
                    $q->where('lc_po_type', 'like', '%' . $searchQuery . '%')
                      ->orWhere('brand', 'like', '%' . $searchQuery . '%')
                      ->orWhere('category', 'like', '%' . $searchQuery . '%')
                      ->orWhere('model_no', 'like', '%' . $searchQuery . '%');
                } else {
                    // Search in the specific field
                    $q->where($searchField, 'like', '%' . $searchQuery . '%');
                }
            });
        }

        // Apply issue status filter if provided
        if ($issueStatus !== '') {
            $query->where('status', $issueStatus);
        }

        // Paginate results
        $items = $query->paginate($perPage)->withQueryString();

        // Pass data to the view
        return view('invoice-list', [
            'title' => 'Invoice List',
            'items' => $items,
            'search' => $searchQuery,
            'searchField' => $searchField,
            'perPage' => $perPage,
            'issueStatus' => $issueStatus,
        ]);
    }
}
