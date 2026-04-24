<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LsiStatusController extends Controller
{
    public function index()
    {
        // No data visible in the screenshot grid, passing empty array
        $statuses = [];

        return view('inventory.lsi_status.index', compact('statuses'));
    }
}
