<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GatePassController extends Controller
{
    public function index()
    {
        $title = 'Gate Pass';
        return view('logistics.gatepass', compact('title'));
    }
}
