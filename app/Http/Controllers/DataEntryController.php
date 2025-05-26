<?php

namespace App\Http\Controllers;

use App\Models\UploadDocument;
use App\Models\Product; // Import Product model for categories/brands
use App\Models\Item; // Import Item model for storing data
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import the DB facade

class DataEntryController extends Controller
{
   // Display the form
   public function index()
   {
       // Fetch L/C or PO types from the database
       $lcpoTypes = UploadDocument::select('type', 'lcpo_no')
           ->whereIn('type', ['local', 'import'])
           ->get();

       // Fetch distinct categories and brands from the products table
       $categories = Product::select('category')->distinct()->get();
       $brands = Product::select('product_brand')->distinct()->get();

       // Return the view with data
       $title = 'Data Entry';
       return view('item-input.item-input', compact('title', 'lcpoTypes', 'categories', 'brands'));
   }

   // Handle AJAX request to fetch brands based on selected category
   public function getBrands(Request $request)
   {
       // Validate the category input
       $request->validate([
           'category' => 'required|string',
       ]);

       // Fetch brands related to the selected category
       $brands = Product::where('category', $request->category)
           ->select('product_brand')
           ->distinct()
           ->get();

       // Return the brands as a JSON response
       return response()->json($brands);
   }


   public function store(Request $request)
   {
       // Log the full request to ensure we're receiving the data correctly
       \Log::info('Incoming Request:', $request->all());
   
       // Validate the incoming request data
       $request->validate([
           'entries' => 'required|json', // Ensure 'entries' is a valid JSON string
       ]);
   
       // Decode the 'entries' JSON string into an array
       $entries = json_decode($request->input('entries'), true);
   
       // Check if JSON decode fails
       if (json_last_error() !== JSON_ERROR_NONE) {
           \Log::error('JSON Decode Error:', ['error' => json_last_error_msg()]);
           return back()->withErrors(['msg' => 'Invalid JSON format.']);
       }
   
       // Log the decoded 'entries' data to ensure correct parsing
       \Log::info('Decoded Entries:', ['entries' => $entries]);
   
       // Initialize the DB transaction
       DB::beginTransaction();
       try {
           // Process each entry and store it in the database
           foreach ($entries as $entry) {
               $validatedEntry = $this->validateEntry($entry);
   
               foreach ($validatedEntry['serial_no'] as $serial) {
                   DB::table('items')->insert([
                       'lc_po_type' => $validatedEntry['lc_po_type'],
                       'category' => $validatedEntry['category'],
                       'brand' => $validatedEntry['brand'],
                       'model_no' => $validatedEntry['model_no'],
                       'serial_no' => $serial,
                       'specification' => $validatedEntry['specification'], // Added specification
                       'condition' => $validatedEntry['condition'],
                       'status' => $validatedEntry['status'],
                       'holding_location' => $validatedEntry['holding_location'],
                       'date' => $validatedEntry['date'],
                       'created_at' => now(),
                       'updated_at' => now(),
                   ]);
               }
           }
           DB::commit();
           return redirect()->route('dataentry.index')->with('success', 'Entries saved successfully!');
       } catch (\Exception $e) {
           DB::rollback();
           \Log::error('Transaction failed:', ['error' => $e->getMessage()]);
           return back()->withErrors(['msg' => 'Failed to save entries: ' . $e->getMessage()]);
       }
   }
   
   private function validateEntry($entry)
   {
       $serialNo = is_array($entry['serial_no']) ? $entry['serial_no'] : explode(',', $entry['serial_no']);
   
       return [
           'lc_po_type' => $entry['lc_po_type'] ?? 'local',
           'category' => $entry['category'] ?? 'Uncategorized',
           'brand' => $entry['brand'] ?? 'Unknown',
           'model_no' => $entry['model_no'] ?? 'N/A',
           'serial_no' => $serialNo,
           'specification' => $entry['specification'] ?? 'Not specified', // Handle specification
           'condition' => $entry['condition'] ?? 'Good',
           'status' => $entry['status'] ?? 'No',
           'holding_location' => $entry['holding_location'] ?? 'Warehouse',
           'date' => $entry['date'] ?? now()->toDateString(),
       ];
   }
   
    // Helper function to validate the condition field (Good or Faulty)
    private function getValidCondition($condition)
    {
        // Only allow "Good" or "Faulty"
        return (in_array(strtolower($condition), ['good', 'faulty'])) ? ucfirst(strtolower($condition)) : 'Good';
    }


}
