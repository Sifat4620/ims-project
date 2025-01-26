<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarrantyLogController extends Controller
{
    public function index()
    {
        $title = 'Warranty Log';
        return view('logistics.warrantylog', compact('title'));
    }
}
