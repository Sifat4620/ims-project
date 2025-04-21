<?php

namespace App\Http\Controllers;

use App\Models\UploadDocument; // Use the UploadDocument model
use Illuminate\Http\Request;

class AllDocumentsController extends Controller
{
    public function index()
    {
        // Fetch documents with pagination (10 documents per page)
        $documents = UploadDocument::paginate(10); // Use UploadDocument instead of Document

        // Define the title for the page
        $title = 'All Documents';

        // Return the view with documents and title
        return view('alldocuments', compact('title', 'documents'));
    }
}
