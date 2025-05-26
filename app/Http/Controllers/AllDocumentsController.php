<?php

namespace App\Http\Controllers;

use App\Models\UploadDocument; 
use Illuminate\Http\Request;

class AllDocumentsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $searchField = $request->input('search_field', 'all');
        $search = $request->input('search', '');
    
        $documentsQuery = UploadDocument::query();
    
        if ($searchField !== 'all' && !empty($search)) {
            $documentsQuery->where($searchField, 'like', "%{$search}%");
        }
    
        $documents = $documentsQuery->paginate($perPage);
    
        $title = 'All Documents';
    
        return view('alldocuments.alldocuments', compact('title', 'documents', 'perPage', 'searchField', 'search'));
    }
    
}
