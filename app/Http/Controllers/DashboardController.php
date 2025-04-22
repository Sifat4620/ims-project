<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Returns;
use App\Models\FaultyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Optimized queries for fetching the counts
            $totalStockIn = Item::where('status', 'No')->count();
            $totalStockOut = Item::where('status', 'Yes')->count();
            $totalReturns = Returns::count();
            $totalDamaged = FaultyItem::count();

            // Optional: Adding any other data (e.g., the user’s roles, or other dashboard metrics)
            $user = Auth::user(); // If you need to pass user info for the dashboard
            $roles = $user->roles; // Assuming you have a roles relation set up in the User model

            // Preparing title dynamically or statically
            $title = 'Dashboard';

            // Pass data to the view
            return view('index', compact('totalStockIn', 'totalStockOut', 'totalReturns', 'totalDamaged', 'title', 'roles'));
        } catch (\Exception $e) {
            // Log the error and show a generic error message
            Log::error('Error loading dashboard data: ' . $e->getMessage());
            return redirect()->route('error.page')->with('error', 'There was an issue loading the dashboard data.');
        }
    }
}
