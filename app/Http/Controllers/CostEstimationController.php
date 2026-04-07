<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CostEstimationController extends Controller
{
    public function index()
    {
        $costProcessList = [
            ['code' => '001', 'description' => 'MAN POWER', 'price' => '3.583.000', 'lbl' => '1'],
            ['code' => '002', 'description' => 'LEMBUR', 'price' => '36.250', 'lbl' => '2'],
            ['code' => '003', 'description' => 'UMK LEMBUR', 'price' => '19.500', 'lbl' => '3'],
            ['code' => '004', 'description' => 'BPJS', 'price' => '71.660', 'lbl' => '4'],
            ['code' => '005', 'description' => 'TUNJ. KESEHATAN', 'price' => '143.320', 'lbl' => '5'],
            ['code' => '006', 'description' => 'PPH 21', 'price' => '38.925', 'lbl' => '6']
        ];

        return view('inventory.cost_estimations.index', compact('costProcessList'));
    }
}
