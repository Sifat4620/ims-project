<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class InvoiceListController extends Controller
{
    /**
     * Display the Product List with search, filters, and specifically shows items with status "No" indicating non-availability.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->get('search', '');        // Search input
        $searchField = $request->get('search_field', 'all'); // Search field
        $issueStatus = $request->get('issue_status', '');  // Issue status

        $query = Item::query();

        // Only show items where status is 'No'
        $query->where('status', 'No');

        // Apply search logic
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery, $searchField) {
                if ($searchField === 'all') {
                    $q->where('lc_po_type', 'like', "%$searchQuery%")
                    ->orWhere('brand', 'like', "%$searchQuery%")
                    ->orWhere('category', 'like', "%$searchQuery%")
                    ->orWhere('model_no', 'like', "%$searchQuery%");
                } else {
                    $q->where($searchField, 'like', "%$searchQuery%");
                }
            });
        }

        // Apply issue status filter if provided
        if ($issueStatus !== '') {
            $query->where('status', $issueStatus);
        }

        // Get all filtered results (no pagination)
        $items = $query->get();

        return view('product-list.product-list', [
            'title' => 'Product List',
            'items' => $items,
            'search' => $searchQuery,
            'searchField' => $searchField,
            'issueStatus' => $issueStatus,
        ]);
    }

}
