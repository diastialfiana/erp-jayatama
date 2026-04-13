<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        $customers = [
            ['id' => 1, 'name' => 'PT. ADITYA MANDIRI'],
            ['id' => 2, 'name' => 'PT. CEMERLANG ABADI'],
            ['id' => 3, 'name' => 'PT. SUMBER BARU'],
            ['id' => 4, 'name' => 'PT. JAYA MAJU']
        ];

        $salesNames = ['BUDI SANTOSO', 'SITI AMINAH', 'IWAN SETIAWAN', 'RATNA DEWI'];

        $estimations = [
            ['code' => 'EST/2024/001', 'name' => 'SECURITY SERVICES PKL'],
            ['code' => 'EST/2024/002', 'name' => 'CLEANING SERVICES JK'],
            ['code' => 'EST/2024/003', 'name' => 'DRIVER SERVICES SBY']
        ];

        $quotationsHistory = [
            [
                'date' => '2024-03-20',
                'userno' => 'USR-201',
                'customer' => 'PT. ADITYA MANDIRI',
                'attn1' => 'Bpk. Andi',
                'sales' => 'BUDI SANTOSO',
                'amount' => '15,000,000',
                'discount' => '0',
                'tax' => '1,650,000',
                'total' => '16,650,000',
                'note' => 'Project A',
                'est_selected' => 'EST/2024/001',
                'order_no' => 'ORD-101',
                'po_ref' => 'PO-REF-01',
                'recid' => '1'
            ],
            [
                'date' => '2024-03-22',
                'userno' => 'USR-202',
                'customer' => 'PT. CEMERLANG ABADI',
                'attn1' => 'Ibu Maya',
                'sales' => 'SITI AMINAH',
                'amount' => '8,500,000',
                'discount' => '500,000',
                'tax' => '880,000',
                'total' => '8,880,000',
                'note' => 'Maintenance',
                'est_selected' => 'EST/2024/002',
                'order_no' => 'ORD-102',
                'po_ref' => 'PO-REF-02',
                'recid' => '2'
            ]
        ];

        $estimationItems = [
            'EST/2024/001' => [
                ['estNo' => 'EST/2024/001', 'productName' => 'SECURITY AGENT', 'qty' => 5, 'price' => 2500000, 'amount' => 12500000, 'unitOff' => 'PERSON', 'tlOffers' => 12500000],
                ['estNo' => 'EST/2024/001', 'productName' => 'SUPERVISOR', 'qty' => 1, 'price' => 2500000, 'amount' => 2500000, 'unitOff' => 'PERSON', 'tlOffers' => 2500000],
            ],
            'EST/2024/002' => [
                ['estNo' => 'EST/2024/002', 'productName' => 'CLEANER', 'qty' => 10, 'price' => 1500000, 'amount' => 15000000, 'unitOff' => 'PERSON', 'tlOffers' => 15000000],
                ['estNo' => 'EST/2024/002', 'productName' => 'CLEANING TOOLS', 'qty' => 1, 'price' => 5000000, 'amount' => 5000000, 'unitOff' => 'SET', 'tlOffers' => 5000000],
            ],
            'EST/2024/003' => [
                ['estNo' => 'EST/2024/003', 'productName' => 'DRIVER', 'qty' => 3, 'price' => 3000000, 'amount' => 9000000, 'unitOff' => 'PERSON', 'tlOffers' => 9000000],
            ]
        ];

        $detailedQuotationItems = [
            [
                'date' => '2024-03-20', 'userno' => 'USR-201', 'customer' => 'PT. ADITYA MANDIRI', 'attn1' => 'Bpk. Andi', 'attn2' => '', 'sales' => 'BUDI SANTOSO', 'product' => 'SECURITY AGENT', 'qty' => 5, 'price' => '2,500,000', 'amount' => '12,500,000', 'disc_pct' => 0, 'discount' => 0,
                'tax' => '1,375,000', 'total' => '13,875,000', 'est_selected' => 'EST/2024/001', 'order_no' => 'ORD-101', 'po_ref' => 'PO-REF-01', 'note' => 'Regular Service'
            ],
            [
                'date' => '2024-03-20', 'userno' => 'USR-201', 'customer' => 'PT. ADITYA MANDIRI', 'attn1' => 'Bpk. Andi', 'attn2' => '', 'sales' => 'BUDI SANTOSO', 'product' => 'SUPERVISOR', 'qty' => 1, 'price' => '2,500,000', 'amount' => '2,500,000', 'disc_pct' => 0, 'discount' => 0,
                'tax' => '275,000', 'total' => '2,775,000', 'est_selected' => 'EST/2024/001', 'order_no' => 'ORD-101', 'po_ref' => 'PO-REF-01', 'note' => 'Lead Team'
            ],
        ];

        return view('inventory.quotations.index', compact(
            'customers', 
            'salesNames', 
            'estimations', 
            'quotationsHistory',
            'detailedQuotationItems',
            'estimationItems'
        ));
    }
}
