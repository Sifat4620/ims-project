<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Returns;
use App\Models\FaultyItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with full statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // === BASIC COUNTS ===
            $totalStockIn = Item::where('status', 'No')->count();     // Still in stock
            $totalStockOut = Item::where('status', 'Yes')->count();    // Issued or moved
            $totalReturns = Returns::count();
            $totalDamaged = FaultyItem::count();
            $totalProducts = Product::count();

            // === GROUPED SUMMARY ===

            // Brand & Category summary (Total Products in Stock per Brand/Category)
            $brandCategorySummary = Item::where('status', 'No')
                ->get()
                ->groupBy('brand')
                ->map(function ($brandItems) {
                    return $brandItems->groupBy('category');
                });

            // Optionally, you can also get top 5 categories or brands if needed
            $stockByCategory = Item::select('category')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('category')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            $stockByBrand = Item::select('brand')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('brand')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            // === RECENT ITEMS / PRODUCTS ===
            $recentItems = Item::latest('created_at')->take(5)->get(['id', 'model_no', 'brand', 'category', 'status', 'created_at']);
            $recentProducts = Product::latest('created_at')->take(5)->get(['id', 'product_id', 'category', 'product_brand', 'entry_date']);

            // === USER INFO ===
            $user = Auth::user();
            $roles = $user?->roles ?? [];

            // === DASHBOARD TITLE ===
            $title = 'Dashboard Overview';

            // === PASS ALL DATA TO VIEW ===
            return view('index', compact(
                'title',
                'totalStockIn',
                'totalStockOut',
                'totalReturns',
                'totalDamaged',
                'totalProducts',
                'stockByCategory',
                'stockByBrand',
                'recentItems',
                'recentProducts',
                'roles',
                'brandCategorySummary' // <-- Pass summary to Blade
            ));

        } catch (\Exception $e) {
            Log::error('Error loading dashboard data: ' . $e->getMessage());
            return redirect()->route('error.page')->with('error', 'Failed to load dashboard data. Please try again.');
        }
    }
}
