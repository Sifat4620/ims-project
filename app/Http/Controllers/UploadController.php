<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\UploadDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UploadController extends Controller
{
    public function localFileUpload()
    {
        $title = 'Local File Upload';
        return view('local-file-upload.local-file-upload', compact('title'));
    }

    public function importFileUpload()
    {
        $title = 'Import File Upload';
        return view('import-file-upload.import-file-upload', compact('title'));
    }

    //local section
    public function storeLocalFileUpload(Request $request)
    {
        try {
            // Validate the form data
            $validator = Validator::make($request->all(), [
                'po_ref' => 'required|string|max:255', // PO reference
                'total_amount' => 'required|numeric', // Allowing any numeric value (integer or float)
                'lc_document' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'requisition_document' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'management_approval_document' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'purchase_order_document' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'regulatory_approval_document' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'invoice_document' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'customs_document' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
            ]);
        
            // Check if validation fails
            if ($validator->fails()) {
                // Log validation errors for better traceability
                \Log::error('Validation failed: ', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator)->withInput();
            }
        
            // Get validated data
            $validated = $validator->validated();
        
            // Initialize the UploadDocument model
            $uploadDocument = new UploadDocument();
            $uploadDocument->type = 'local';
            $uploadDocument->lcpo_no = $validated['po_ref'];
            $uploadDocument->part_shipment = $request->part_shipment ?? 'no';
            $uploadDocument->total_amount = floatval($validated['total_amount']); // Ensure total_amount is stored as float
            $uploadDocument->remarks = $request->remarks;
        
            // Define the list of possible file uploads
            $files = [
                'lc_document',
                'requisition_document',
                'management_approval_document',
                'purchase_order_document',
                'regulatory_approval_document',
                'invoice_document',
                'customs_document'
            ];
        
            // Loop through each file and handle the upload
            foreach ($files as $fileKey) {
                if ($request->hasFile($fileKey)) {
                    // Generate new file name with po_ref and timestamp
                    $originalFileName = $request->file($fileKey)->getClientOriginalName();
                    $newFileName = $validated['po_ref'] . '.' . $fileKey . '.' . time() . '.' . $request->file($fileKey)->getClientOriginalExtension();
        
                    // Define the full file path to store in public storage
                    $filePath = 'documents/' . $newFileName;
        
                    // Check if the old file exists in storage and delete it if needed
                    if (!empty($uploadDocument->{$fileKey}) && Storage::disk('public')->exists($uploadDocument->{$fileKey})) {
                        try {
                            Storage::disk('public')->delete($uploadDocument->{$fileKey});
                        } catch (\Exception $exception) {
                            \Log::error("Error deleting old file: " . $exception->getMessage());
                            return response()->json(['error' => "Error deleting old file. Please try again."]);
                        }
                    }
        
                    // Store the new file in the public storage
                    try {
                        $request->file($fileKey)->storeAs('documents', $newFileName, 'public');
                        $uploadDocument->{$fileKey} = $filePath; // Store the file path in the database
                    } catch (\Exception $exception) {
                        \Log::error("Error storing file: " . $exception->getMessage());
                        return response()->json(['error' => "Error uploading file: " . $fileKey . ". Please try again."]);
                    }
                }
            }
        
            // Save the upload document record
            try {
                $uploadDocument->save();
            } catch (\Exception $e) {
                \Log::error("Error saving record: " . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while saving the document. Please try again later.')->withInput();
            }
        
            // Redirect back with success message
            return redirect()->route('upload.localFileUpload')->with('success', 'Files and data uploaded successfully!');
        } catch (\Exception $e) {
            // Log any unexpected error
            \Log::error("Unexpected error: " . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again later.')->withInput();
        }
    }
    
    
    // import 
    public function storeImportFileUpload(Request $request)
    {
        try {
            // Validate the form data
            $validator = Validator::make($request->all(), [
                'po_ref' => 'required|string|max:255', // PO reference
                'total_amount' => 'required|numeric', // Updated to allow any numeric value (integer or float)
                'lcDocument' => 'required|file|mimes:pdf,doc,docx|max:4048',  // Adjusted to match request key
                'requisitionDocument' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'managementApprovalDocument' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'purchaseDocument' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'regulatoryApprovalDocument' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'invoiceDocument' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
                'customsDocument' => 'nullable|file|mimes:pdf,doc,docx|max:4048',
            ]);
        
            // Handle validation failure
            if ($validator->fails()) {
                \Log::error('Validation failed: ', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator)->withInput();
            }
        
            $validated = $validator->validated();
        
            // Initialize the UploadDocument model
            $uploadDocument = new UploadDocument();
            $uploadDocument->type = 'import';
            $uploadDocument->lcpo_no = $validated['po_ref'];
            $uploadDocument->part_shipment = $request->input('part_shipment', 'no');
            $uploadDocument->total_amount = floatval($validated['total_amount']); // Ensure total_amount is stored as float
            $uploadDocument->remarks = $request->input('remarks', '');
        
            // Map the request keys to database fields
            $fileMappings = [
                'lcDocument' => 'lc_document',
                'requisitionDocument' => 'requisition_document',
                'managementApprovalDocument' => 'management_approval_document',
                'purchaseDocument' => 'purchase_order_document',
                'regulatoryApprovalDocument' => 'regulatory_approval_document',
                'invoiceDocument' => 'invoice_document',
                'customsDocument' => 'customs_document',
            ];
        
            foreach ($fileMappings as $requestKey => $dbField) {
                // Check if file is uploaded for the corresponding field
                if ($request->hasFile($requestKey)) {
                    $file = $request->file($requestKey);
                    $originalFileName = $file->getClientOriginalName();
                    $newFileName = $validated['po_ref'] . '_' . $dbField . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $filePath = 'documents/' . $newFileName;
        
                    // Store the file and assign its path to the model
                    try {
                        if ($file->storeAs('documents', $newFileName, 'public')) {
                            $uploadDocument->{$dbField} = $filePath;
                        } else {
                            \Log::error("Failed to store file: $requestKey");
                            return redirect()->back()->with('error', "Failed to upload file: $requestKey")->withInput();
                        }
                    } catch (\Exception $e) {
                        \Log::error("Error storing file $requestKey: " . $e->getMessage());
                        return redirect()->back()->with('error', "Error uploading file: $requestKey. Please try again.")->withInput();
                    }
                }
            }
        
            // Save the document record in the database
            $uploadDocument->save();
        
            // Flash success message to session
            session()->flash('success', 'Files and data uploaded successfully!');
        
            // Redirect to the data entry form page
            return redirect()->route('dataentry.index');
        
        } catch (\Exception $e) {
            \Log::error("Unexpected error: " . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again later.')->withInput();
        }
    }


    
}
