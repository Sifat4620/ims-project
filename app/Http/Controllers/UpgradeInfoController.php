<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class UpgradeInfoController extends Controller
{
    public function index(Request $request)
    {

        $title = 'Upgrade Information';
        // Get filters & search from request
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');
        $searchField = $request->input('search_field', 'all');

        $query = Item::query();

        if ($search && $searchField !== 'all') {
            $query->where($searchField, 'like', "%$search%");
        } elseif ($search) {
            $query->where(function($q) use ($search) {
                $q->where('lc_po_type', 'like', "%$search%")
                  ->orWhere('brand', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%")
                  ->orWhere('model_no', 'like', "%$search%");
            });
        }

        $items = $query->paginate($perPage)->withQueryString();

        return view('upgrade-info.upgrade-info', [
            'title' => $title,
            'items' => $items,
            'perPage' => $perPage,
            'search' => $search,
            'searchField' => $searchField,
            'conditionFilter' => $request->input('condition_filter', ''),
        ]);
    }

    // public function store(Request $request)
    // {
    //     // Implement storing logic here
    //     // For example: validate and create a new item
    // }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $title = 'Edit Upgrade Item';  // page title

        return view('upgrade-info-edit.upgrade-info-edit', compact('item', 'title'));
    }

  
    public function update(Request $request, $id)
    {
        
        $validated = $request->validate([
            'lc_po_type' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'model_no' => 'nullable|string|max:255',
            'serial_no' => 'nullable|string|max:255',
            'specification' => 'nullable|string',
            'holding_location' => 'nullable|string|max:255',
            'condition' => 'nullable|in:Good,Faulty',
            'status' => 'nullable|in:Yes,No',
            'date' => 'nullable|date',
        ]);

        // Fetch the item
        $item = Item::findOrFail($id);

        // List of fields to update
        $fields = [
            'lc_po_type', 'brand', 'category', 'model_no', 'serial_no',
            'specification', 'holding_location', 'condition', 'status', 'date'
        ];

        // Loop through and update only fields present in the request
        foreach ($fields as $field) {
            if ($request->filled($field)) {
                $item->$field = $request->input($field);
            }
        }

        // Save updated item
        $item->save();

        // Redirect with success message
        return redirect()->route('upgrade.info')->with('success', 'Item updated successfully.');
    }



    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('upgrade.info')->with('success', 'Item deleted successfully.');
    }


}
