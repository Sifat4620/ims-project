<?php

namespace App\Http\Controllers;

use App\Models\UploadDocument;  // Assuming you have this model to access document data
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Response;

class AccessController extends Controller
{
    /**
     * Show the document access page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $title = 'Accessing & Downloading Your Information';

        // Fetch documents based on LCPO Number if provided
        $documents = null;

        if ($request->has('lcpo_no')) {
            $lcpoNo = $request->input('lcpo_no');
            // Fetch documents that match the provided LCPO Number
            $documents = UploadDocument::where('lcpo_no', $lcpoNo)->get();
        }

        return view('accessing.accessing', compact('title', 'documents'));
    }

    /**
     * Handle the document download (Zip all files).
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Request $request)
    {
        // Get LCPO number from route parameter
        $lcpoNo = $request->route('lcpo_no');
        
        // Debugging: Log LCPO number
        \Log::debug("Received LCPO number for download: $lcpoNo");
    
        // Check if LCPO number is provided
        if (empty($lcpoNo)) {
            \Log::error("LCPO number is missing in the request.");
            return redirect()->back()->with('error', 'LCPO number is required.');
        }
    
        // Fetch documents for the given LCPO number
        $documents = UploadDocument::where('lcpo_no', $lcpoNo)->get();
    
        // Debugging: Log the number of documents found
        \Log::debug("Documents found for LCPO No: $lcpoNo - Count: " . $documents->count());
    
        // Check if no documents are found
        if ($documents->isEmpty()) {
            \Log::error("No documents found for LCPO No: $lcpoNo");
            return redirect()->back()->with('error', 'No documents found for the provided LCPO number.');
        }
    
        // Create a new ZIP file for download
        $zip = new ZipArchive;
        $zipFileName = "documents_$lcpoNo.zip";
        $zipFilePath = storage_path('app/public/' . $zipFileName);
    
        if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
            \Log::error("Failed to create ZIP file for LCPO No: $lcpoNo");
            return redirect()->back()->with('error', 'Failed to create a ZIP file. Please try again.');
        }
    
        // List of document fields
        $documentFields = [
            'lc_document',
            'requisition_document',
            'management_approval_document',
            'purchase_order_document',
            'regulatory_approval_document',
            'invoice_document',
            'customs_document'
        ];
    
        // Loop through the documents and add them to the ZIP file
        foreach ($documents as $document) {
            foreach ($documentFields as $field) {
                // Get the path to the file in public storage
                $documentPath = storage_path('app/public/' . $document->{$field});

                // Ensure the file exists and is accessible
                if (Storage::disk('public')->exists($document->{$field})) {
                    // Add the file to the ZIP
                    $zip->addFile(Storage::disk('public')->path($document->{$field}), basename($document->{$field}));
                    \Log::debug("Added file to ZIP: " . $document->{$field});
                } else {
                    \Log::warning("File not found for LCPO No: $lcpoNo, Document: {$document->{$field}}");
                }
            }
        }
    
        // Close the ZIP file
        $zip->close();
    
        // Debugging: Log the final zip file path
        \Log::debug("ZIP file created successfully: $zipFilePath");
    
        // Serve the ZIP file for download and delete it after sending
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
