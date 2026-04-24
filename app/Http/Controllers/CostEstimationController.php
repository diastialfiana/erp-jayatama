<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CostEstimationController extends Controller
{
    public function index()
    {
        $customers = [
            ['id' => 1, 'name' => 'PT. ADITYA MANDIRI'],
            ['id' => 2, 'name' => 'PT. CEMERLANG ABADI'],
            ['id' => 3, 'name' => 'PT. SUMBER BARU'],
            ['id' => 4, 'name' => 'PT. JAYA MAJU']
        ];

        $locations = ['SEMARANG', 'SURABAYA', 'JAKARTA', 'BANDUNG', 'MEDAN'];

        $productServices = [
            'SECURITY SERVICES',
            'CLEANING SERVICES',
            'MANPOWER SUPPLY',
            'DRIVING SERVICES'
        ];

        $costProcessList = [
            ['code' => '001', 'description' => 'MAN POWER', 'price' => '3,583,000', 'lbl' => '1'],
            ['code' => '002', 'description' => 'LEMBUR', 'price' => '36,250', 'lbl' => '2'],
            ['code' => '003', 'description' => 'UMK LEMBUR', 'price' => '19,500', 'lbl' => '3'],
            ['code' => '004', 'description' => 'BPJS', 'price' => '71,660', 'lbl' => '4'],
            ['code' => '005', 'description' => 'TUNJ. KESEHATAN', 'price' => '143,320', 'lbl' => '5'],
            ['code' => '006', 'description' => 'PPH 21', 'price' => '38,925', 'lbl' => '6']
        ];

        $estimationsHistory = [
            [
                'date' => '2024-03-25',
                'customer' => 'PT. ADITYA MANDIRI',
                'location' => 'SEMARANG',
                'userno' => 'USR-101',
                'doc_reff' => 'REF/001/2024',
                'quotations' => 'QOT-001',
                'inv_date' => '2024-03-30',
                'direct_costs' => '12,500,000',
                'indirect_costs' => '2,500,000',
                'others_costs' => '500,000',
                'management_fee' => '1,500,000'
            ],
            [
                'date' => '2024-03-28',
                'customer' => 'PT. CEMERLANG ABADI',
                'location' => 'SURABAYA',
                'userno' => 'USR-102',
                'doc_reff' => 'REF/002/2024',
                'quotations' => 'QOT-002',
                'inv_date' => '2024-04-02',
                'direct_costs' => '8,200,000',
                'indirect_costs' => '1,800,000',
                'others_costs' => '300,000',
                'management_fee' => '1,000,000'
            ]
        ];

        return view('inventory.cost_estimations.index', compact(
            'costProcessList', 
            'customers', 
            'locations', 
            'productServices',
            'estimationsHistory'
        ));
    }
}
