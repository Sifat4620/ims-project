<?php

namespace App\Http\Controllers;

use App\Models\UploadDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AccessController extends Controller
{
    /**
     * Display all uploaded documents.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $title = 'Accessing & Downloading Your Information';
        $documents = UploadDocument::all();

        return view('accessing.accessing', compact('title', 'documents'));
    }

    /**
     * Download all documents for a specific LCPO number as a ZIP file.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function download(Request $request)
    {
        $lcpoNo = $request->route('lcpo_no');

        \Log::debug("Download initiated for LCPO No: {$lcpoNo}");

        if (empty($lcpoNo)) {
            \Log::error("LCPO number is missing.");
            return redirect()->back()->with('error', 'LCPO number is required.');
        }

        $documents = UploadDocument::where('lcpo_no', $lcpoNo)->get();

        if ($documents->isEmpty()) {
            \Log::error("No documents found for LCPO No: {$lcpoNo}");
            return redirect()->back()->with('error', 'No documents found for the provided LCPO number.');
        }

        $zip = new ZipArchive;
        $zipFileName = "documents_{$lcpoNo}.zip";
        $zipFilePath = storage_path("app/public/{$zipFileName}");

        if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
            \Log::error("Could not create ZIP for LCPO No: {$lcpoNo}");
            return redirect()->back()->with('error', 'Failed to create ZIP file.');
        }

        // List of fields to check for document files
        $documentFields = [
            'lc_document',
            'requisition_document',
            'management_approval_document',
            'purchase_order_document',
            'regulatory_approval_document',
            'invoice_document',
            'customs_document',
        ];

        foreach ($documents as $doc) {
            foreach ($documentFields as $field) {
                $filePath = $doc->{$field};

                // Skip null or empty values
                if (empty($filePath)) {
                    \Log::info("Skipped empty field '{$field}' for LCPO No: {$lcpoNo}");
                    continue;
                }

                // Only add existing files
                if (Storage::disk('public')->exists($filePath)) {
                    $absolutePath = Storage::disk('public')->path($filePath);
                    $zip->addFile($absolutePath, basename($filePath));
                    \Log::debug("Added file to ZIP: {$filePath}");
                } else {
                    \Log::warning("File does not exist: {$filePath} for LCPO No: {$lcpoNo}");
                }
            }
        }

        $zip->close();

        \Log::info("ZIP ready for download: {$zipFilePath}");

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
