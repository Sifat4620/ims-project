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
            // === USER INFO ===
            $user = Auth::user();
            $roles = $user?->roles ?? [];

            // === STOCK COUNTS ===
            $totalStockIn = Item::where('status', 'No')->count();      // Still in stock
            $totalStockOut = Item::where('status', 'Yes')->count();     // Issued or moved
            $totalReturns = Returns::count();
            $totalDamaged = FaultyItem::count();
            $totalProducts = Product::count();

            // === UNIQUE BRANDS AND CATEGORIES ===
            $totalBrands = Item::distinct('brand')->count('brand');
            $totalCategories = Item::distinct('category')->count('category');

            // === BRAND-CATEGORY-PRODUCT SUMMARY ===
            $items = Item::all(); // all items
            $brandCategorySummary = [];

            foreach ($items as $item) {
                $brand = $item->brand ?: 'Unknown';
                $category = $item->category ?: 'Unknown';
                $brandCategorySummary[$brand][$category][] = $item;
            }

            // === STOCK SUMMARY BY CATEGORY / BRAND / TYPE ===
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

            $stockByType = Item::select('lc_po_type')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('lc_po_type')
                ->orderByDesc('total')
                ->get();

            // === RECENT ITEMS / PRODUCTS ===
            $recentItems = Item::latest('created_at')
                ->take(5)
                ->get(['id', 'model_no', 'brand', 'category', 'status', 'created_at']);

            $recentProducts = Product::latest('created_at')
                ->take(5)
                ->get(['id', 'product_id', 'category', 'product_brand', 'entry_date']);

            // === DASHBOARD TITLE ===
            $title = 'Dashboard Overview';

            // === RETURN VIEW WITH ALL DATA ===
            return view('index', compact(
                'title',
                'roles',
                'totalStockIn',
                'totalStockOut',
                'totalReturns',
                'totalDamaged',
                'totalProducts',
                'totalBrands',
                'totalCategories',
                'brandCategorySummary',
                'stockByCategory',
                'stockByBrand',
                'stockByType',
                'recentItems',
                'recentProducts'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading dashboard data: ' . $e->getMessage());
            return redirect()->route('error.page')->with('error', 'Failed to load dashboard data. Please try again.');
        }
    }
}
