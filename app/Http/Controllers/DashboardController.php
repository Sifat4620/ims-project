<?php

namespace App\Http\Controllers;

use App\Models\Item; // Import the Item model
use App\Models\Returns;
use App\Models\FaultyItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Dashboard';

        // Fetch dashboard statistics
        $totalStockIn = Item::where('status', 'No')->count(); // Stock In: status is 'No'
        $totalStockOut = Item::where('status', 'Yes')->count(); // Stock Out: status is 'Yes'
        $totalReturns = Returns::count(); // Total returns
        $totalDamaged = FaultyItem::count(); // Total damaged (faulty items)

        // Pass data to the view
        return view('index', compact('title', 'totalStockIn', 'totalStockOut', 'totalReturns', 'totalDamaged'));
    }
}
