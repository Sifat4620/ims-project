<?php

namespace App\Http\Controllers;

use App\Models\UploadDocument;  // Assuming you have this model to access document data
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccessController extends Controller
{
    public function show(Request $request)
    {
        // Define the title for the page
        $title = 'Accessing & Downloading Your Information';

        // Fetch documents based on LCPO Number if provided
        $documents = null;

        if ($request->has('lcpo_no')) {
            // Retrieve documents that match the provided LCPO Number
            $documents = UploadDocument::where('lcpo_no', $request->input('lcpo_no'))->get();
        }

        return view('accessing', compact('title', 'documents'));
    }

    // Function to handle document download
    public function download(Request $request, $documentId)
    {
        $document = UploadDocument::find($documentId);

        if (!$document) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        // Get the file path from the document model (you need to have a file path column in your table)
        $filePath = $document->file_path;

        // Check if the file exists in storage
        if (!Storage::exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        // Return the file for download
        return Storage::download($filePath, $document->file_name); // Assuming you have 'file_name' in your database
    }
}
