<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\ProductAsset;

class InventoryController extends Controller
{
    public function index()
    {
        // Get real counts from DB
        $stats = [
            'total_fixed_assets' => ProductAsset::where('category', 'ASET TETAP')->count(),
            'total_product_assets' => ProductAsset::count(),
            'total_employees' => Employee::count(),
            'recent_requests' => 0 // Fallback for now
        ];

        return view('inventory.index', compact('stats'));
    }
}
