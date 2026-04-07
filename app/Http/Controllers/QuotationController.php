<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = [];

        return view('inventory.quotations.index', compact('quotations'));
    }
}
