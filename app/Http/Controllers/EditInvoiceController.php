<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditInvoiceController extends Controller
{
    public function index()
    {
        $title = 'Edit Invoice';
        return view('invoice-edit', compact('title'));
    }
}
