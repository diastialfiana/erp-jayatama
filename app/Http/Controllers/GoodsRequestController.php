<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoodsRequestController extends Controller
{
    public function index()
    {
        $requests = [
            [
                'id' => 1,
                'acc' => true,
                'type' => 'PO',
                'ref' => '',
                'user_no' => '631',
                'date' => '2021-06-09',
                'tl_qty' => '1',
                'note' => 'Urgent for maintenance',
                'process' => false,
                'user_id' => 'SINGGIH',
                'audit' => 'PASS',
                'priority' => 'High',
                'employees_name' => 'ENDANG HIDAYAT',
                'top_id' => '20007',
                'on_processed' => false,
                'approved' => true,
                'customer_name' => 'JAYA TAMA PT.',
                'warehouse_name' => 'GUDANG UTAMA',
                'items' => [
                    [
                        'code' => '010170072084',
                        'product_name' => 'BEARING SKF 6206 ZZ',
                        'qty' => '1',
                        'description' => 'untuk mbm',
                        'req_date' => '2021-06-09',
                        'hold' => false,
                        'dimensions' => '',
                        'volume' => '',
                        'otw' => '0'
                    ]
                ],
                'total_qty' => '1',
                'histories' => [
                    ['date' => '08/06/2021', 'type' => 'PO', 'ref' => 'REF-001', 'user_no' => '630', 'tl_qty' => '2', 'note' => 'Previous request', 'process' => true, 'audit' => 'PASS', 'acc' => true],
                    ['date' => '05/06/2021', 'type' => 'PO', 'ref' => 'REF-000', 'user_no' => '625', 'tl_qty' => '5', 'note' => 'Initial stock', 'process' => true, 'audit' => 'PASS', 'acc' => true]
                ],
                'advance_data' => [
                    ['code' => 'ADV-001', 'bank_name' => 'MANDIRI', 'cost' => 'MNT', 'dept' => 'TECH', 'amount' => '500.000', 'description' => 'Downpayment for bearing', 'account_name' => 'SKF INDO']
                ]
            ],
            [
                'id' => 2,
                'acc' => false,
                'type' => 'PO',
                'ref' => 'REQ-2024-001',
                'user_no' => '632',
                'date' => '10/06/2021',
                'tl_qty' => '10',
                'note' => 'Office supplies bundle',
                'process' => true,
                'user_id' => 'YUNITA',
                'audit' => 'WAIT',
                'priority' => 'Normal',
                'employees_name' => 'YUNITA CHAERUNISSA',
                'top_id' => '20008',
                'on_processed' => true,
                'approved' => false,
                'customer_name' => 'INTERNAL OFFICE',
                'warehouse_name' => 'GUDANG GA',
                'items' => [
                    ['code' => 'OFF-001', 'product_name' => 'PAPER A4 80G', 'qty' => '5', 'description' => 'Stock GA', 'req_date' => '10/06/2021', 'hold' => false, 'dimensions' => '', 'volume' => '', 'otw' => '0'],
                    ['code' => 'OFF-002', 'product_name' => 'PEN BLUE', 'qty' => '5', 'description' => 'Stock GA', 'req_date' => '10/06/2021', 'hold' => false, 'dimensions' => '', 'volume' => '', 'otw' => '0']
                ],
                'total_qty' => '10',
                'histories' => [
                    ['date' => '01/06/2021', 'type' => 'PO', 'ref' => 'REQ-2024-000', 'user_no' => '600', 'tl_qty' => '20', 'note' => 'Monthly supplies', 'process' => true, 'audit' => 'PASS', 'acc' => true]
                ],
                'advance_data' => []
            ],
            [
                'id' => 3,
                'acc' => true,
                'type' => 'ST',
                'ref' => 'ST-999',
                'user_no' => '635',
                'date' => '11/06/2021',
                'tl_qty' => '2',
                'note' => 'Replacement for broken parts',
                'process' => false,
                'user_id' => 'ANNE',
                'audit' => 'PASS',
                'priority' => 'Low',
                'employees_name' => 'ANNE MARIE',
                'top_id' => '20010',
                'on_processed' => false,
                'approved' => true,
                'customer_name' => 'WORKSHOP',
                'warehouse_name' => 'GUDANG TEKNIK',
                'items' => [
                    ['code' => 'ELC-001', 'product_name' => 'CABLE TIE', 'qty' => '2', 'description' => 'For electrical dept', 'req_date' => '11/06/2021', 'hold' => false, 'dimensions' => '', 'volume' => '', 'otw' => '0']
                ],
                'total_qty' => '2',
                'histories' => [],
                'advance_data' => [
                    ['code' => 'ADV-005', 'bank_name' => 'BCA', 'cost' => 'ELC', 'dept' => 'TECH', 'amount' => '150.000', 'description' => 'Cash purchase emergency', 'account_name' => 'Toko Listrik']
                ]
            ]
        ];

        return view('inventory.goods_requests.index', compact('requests'));
    }
}
