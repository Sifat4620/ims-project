<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DefectiveItemsReportController extends Controller
{
    public function defectiveProductsReport(Request $request)
    {
        try {
            // Validate incoming request data
            $validated = $request->validate([
                'perPage' => 'integer|min:1|max:100', // Limit items per page
                'search_field' => 'nullable|string|in:all,lc_po_type,brand,category,model_no', // Allow only valid fields
                'search' => 'nullable|string|max:255', // Ensure search term is a string
            ]);

            $perPage = $validated['perPage'] ?? 10; // Default to 10 items per page
            $searchField = $validated['search_field'] ?? 'all'; // Default to searching all fields
            $search = $validated['search'] ?? ''; // Default to no search keyword

            $query = DB::table('faulty_items')
            ->join('items', 'faulty_items.item_id', '=', 'items.id')
            ->select(
                'faulty_items.*',  // All columns from faulty_items
                'items.lc_po_type',
                'items.brand',
                'items.category',
                'items.model_no',
                'items.serial_no', // Ensure this is included (or the correct column name)
                'items.specification',
                'items.condition',
                'items.holding_location',
                'items.date'
            );

            // Apply search filters
            $this->applySearchFilters($query, $searchField, $search);

            // Paginate results
            $paginatedItems = $query->paginate($perPage);

            // Log the action
            Log::info('Defective Products Report generated', [
                'perPage' => $perPage,
                'searchField' => $searchField,
                'search' => $search,
                'results_count' => $paginatedItems->total(),
            ]);

            // Return the view with paginated data
            return view('defective_report', [
                'title' => 'Defective Products Report',
                'paginatedItems' => $paginatedItems,
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error generating defective products report', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            // Return an error response or view
            return back()->withErrors(['error' => 'An unexpected error occurred while generating the report. Please try again.']);
        }
    }

    /**
     * Apply search filters to the query.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $searchField
     * @param string $search
     * @return void
     */
    private function applySearchFilters(&$query, $searchField, $search)
    {
        if ($search && $searchField !== 'all') {
            $query->where("items.$searchField", 'like', "%{$search}%");
        } elseif ($search) {
            $query->where(function ($q) use ($search) {
                $q->orWhere('items.lc_po_type', 'like', "%{$search}%")
                    ->orWhere('items.brand', 'like', "%{$search}%")
                    ->orWhere('items.category', 'like', "%{$search}%")
                    ->orWhere('items.model_no', 'like', "%{$search}%");
            });
        }
    }
}
