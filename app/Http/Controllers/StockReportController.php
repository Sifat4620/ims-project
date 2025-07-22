<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item; // Assuming your model is named Item
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    /**
     * Show the stock report with grouped and filtered results.
     */
    public function showStockReport(Request $request)
    {
        // Title for the page
        $title = 'Stock Report';

        // Get filters from the request
        $perPage = $request->get('perPage', 10);

        // Fetch and group items by LC/PO Type, Brand, Category, and Model No
        $items = Item::where('status', 'No')
            ->get()
            ->groupBy(function ($item) {
                return $item->lc_po_type . '|' . $item->brand . '|' . $item->category . '|' . $item->model_no;
            })
            ->map(function ($group) {
                return [
                    'lc_po_type' => $group->first()->lc_po_type,
                    'brand' => $group->first()->brand,
                    'category' => $group->first()->category,
                    'model_no' => $group->first()->model_no,
                    'serial_numbers' => $group->pluck('serial_no')->implode(', '),
                    'specification' => $group->first()->specification,
                    'condition' => $group->first()->condition,
                    'status' => $group->first()->status,
                    'holding_location' => $group->first()->holding_location,
                    'date' => $group->first()->date,
                ];
            });

        // Paginate the grouped data manually
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemsCollection = collect($items);
        $currentPageItems = $itemsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedItems = new LengthAwarePaginator(
            $currentPageItems,
            $itemsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Pass data to the view
        return view('stock-report.stock-report', compact('title', 'paginatedItems'));
    }

    
    /**
     * Show the PO Stock Report with grouped and filtered results.
     */
    public function poStockReport(Request $request)
    {
        $title = 'PO Stock Report';

        $perPage = $request->get('perPage', 10);
        $searchField = $request->get('search_field', 'all');
        $searchValue = $request->get('search');

        // Fetch items where lc_po_type is "local" (not Import)
        $query = Item::where('lc_po_type', '!=', 'Import');

        if ($searchValue) {
            $query->where(function ($q) use ($searchField, $searchValue) {
                if ($searchField === 'all') {
                    $q->where('lc_po_type', 'LIKE', "%$searchValue%")
                        ->orWhere('brand', 'LIKE', "%$searchValue%")
                        ->orWhere('category', 'LIKE', "%$searchValue%")
                        ->orWhere('model_no', 'LIKE', "%$searchValue%");
                } else {
                    $q->where($searchField, 'LIKE', "%$searchValue%");
                }
            });
        }

        $items = $query->get()
            ->groupBy(function ($item) {
                return $item->lc_po_type . '|' . $item->brand . '|' . $item->category . '|' . $item->model_no;
            })
            ->map(function ($group) {
                return [
                    'lc_po_type' => $group->first()->lc_po_type,
                    'brand' => $group->first()->brand,
                    'category' => $group->first()->category,
                    'model_no' => $group->first()->model_no,
                    'serial_numbers_yes' => $group->where('status', 'Yes')->pluck('serial_no')->toArray(),
                    'serial_numbers_no' => $group->where('status', 'No')->pluck('serial_no')->toArray(),
                    'specification' => $group->first()->specification,
                    'condition' => $group->first()->condition,
                    'holding_location' => $group->first()->holding_location,
                    'date' => $group->first()->created_at ? $group->first()->created_at->format('Y-m-d') : '',
                ];
            });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemsCollection = collect($items);
        $currentPageItems = $itemsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedItems = new LengthAwarePaginator(
            $currentPageItems,
            $itemsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('po-stock-report.po-stock-report', compact('title', 'paginatedItems'));
    }



    /**
     * Paginate a collection.
     *
     * @param \Illuminate\Support\Collection $items
     * @param int $perPage
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function paginate($items, $perPage, $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemsCollection = collect($items);
        $currentPageItems = $itemsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $currentPageItems,
            $itemsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }



    public function lcWiseStock(Request $request)
    {
        $title = 'LC Wise Stock Report';

        $perPage = $request->get('perPage', 10);

        // JOIN items with upload_document to ensure only 'import' types are selected
        $items = DB::table('items')
            ->join('upload_document', 'items.lc_po_type', '=', 'upload_document.lcpo_no')
            ->where('upload_document.type', 'import') // Only import entries
            ->whereIn('items.status', ['Yes', 'No'])   // Include both statuses
            ->select(
                'items.lc_po_type',
                'items.brand',
                'items.category',
                'items.model_no',
                'items.serial_no',
                'items.specification',
                'items.status',
                'items.date'
            )
            ->get()
            ->groupBy(function ($item) {
                return $item->lc_po_type . '|' . $item->brand . '|' . $item->category . '|' . $item->model_no;
            })
            ->map(function ($group) {
                return [
                    'lc_po_type' => $group->first()->lc_po_type,
                    'brand' => $group->first()->brand,
                    'category' => $group->first()->category,
                    'model_no' => $group->first()->model_no,
                    'serial_numbers_yes' => collect($group)->where('status', 'Yes')->pluck('serial_no')->toArray(),
                    'serial_numbers_no' => collect($group)->where('status', 'No')->pluck('serial_no')->toArray(),
                    'specification' => $group->first()->specification,
                    'date' => $group->first()->date,
                    'quantity_yes' => collect($group)->where('status', 'Yes')->count(),
                    'quantity_no' => collect($group)->where('status', 'No')->count(),
                ];
            });

        // Manual pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemsCollection = collect($items);
        $currentPageItems = $itemsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedItems = new LengthAwarePaginator(
            $currentPageItems,
            $itemsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('lc-stock-report.lc-stock-report', compact('title', 'paginatedItems'));
    }

}
