<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item; // Assuming your model is named Item
use Illuminate\Pagination\LengthAwarePaginator;

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
        // Title for the page
        $title = 'PO Stock Report';

        // Items per page, default to 10 if not provided
        $perPage = $request->get('perPage', 10);

        // Fetch and filter items for PO Stock Report
        $items = Item::query()
            ->where('status', 'No') // Only items with status "No"
            ->where('lc_po_type', 'LIKE', 'Local-%') // LC/PO Type starts with 'Local-'
            ->select([
                'lc_po_type',
                'brand',
                'category',
                'model_no',
                'serial_no',
                'specification',
                'condition',
                'status',
                'holding_location',
                'date',
            ]) // Only select necessary fields
            ->get()
            ->groupBy(function ($item) {
                // Group by LC/PO Type, Brand, Category, and Model No
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

        // Paginate the grouped data
        $paginatedItems = $this->paginate($items, $perPage, $request);

        // Pass data to the view
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

        // Items per page, default to 10 if not provided
        $perPage = $request->get('perPage', 10);

        // Fetch and group items for LC Wise Stock Report
        $items = Item::where('lc_po_type', 'Import') // Filter LC Type "Import"
            ->whereIn('status', ['Yes', 'No']) // Include both Yes and No statuses
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
                    'serial_numbers_yes' => $group->where('status', 'Yes')->pluck('serial_no')->toArray(),
                    'serial_numbers_no' => $group->where('status', 'No')->pluck('serial_no')->toArray(),
                    'specification' => $group->first()->specification,
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
        return view('lc-stock-report.lc-stock-report', compact('title', 'paginatedItems'));
    }

}
