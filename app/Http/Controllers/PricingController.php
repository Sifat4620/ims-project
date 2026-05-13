<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Pricing;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('pricing')
            ->select('id', 'serial_no', 'model_no', 'lc_po_type', 'category', 'condition', 'status', 'date');

        // Filter: priced / unpriced / all
        if ($request->filter === 'priced') {
            $query->whereHas('pricing', fn($q) => $q->whereNotNull('price'));
        } elseif ($request->filter === 'unpriced') {
            $query->whereDoesntHave('pricing', fn($q) => $q->whereNotNull('price'));
        }

        // Search by serial or model
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('serial_no', 'like', '%' . $request->search . '%')
                  ->orWhere('model_no', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->latest()->get();

        $title = 'Pricing Section';
        return view('pricing.index', compact('title', 'items'));
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'price'   => 'required|numeric|min:0',
        ]);

        $item = Item::findOrFail($request->item_id);

        Pricing::updateOrCreate(
            ['item_id' => $item->id, 'serial_no' => $item->serial_no],
            ['price'   => $request->price]
        );

        return back()->with('success', 'Price saved for serial: ' . $item->serial_no);
    }
}