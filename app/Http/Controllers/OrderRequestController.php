<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderRequestController extends Controller
{
    public function index()
    {
        // Mock data representasi order request yang terlihat dari 3 gambar.
        $orderRequests = [
            [
                'id' => 1,
                'acc' => true,
                'type' => 'PO',
                'ref' => '',
                'user_no' => '628',
                'date' => '01/02/2018',
                'tl_qty' => 3,
                'note' => '',
                'process' => true,
                'user_id' => 'PASS',
                'audit' => 'PASS',
                'status' => 'Normal',
                'warehouse' => 'HEAD OFFICE - CHEMICAL',
                'items' => [
                    ['code' => '003020004016', 'name' => 'GUNTING', 'qty' => 1, 'unit' => 'PCS', 'description' => '', 'receive' => 0, 'cancel' => false, 'reload' => false],
                    ['code' => '003020017022', 'name' => 'KALKULATOR SEDANG', 'qty' => 1, 'unit' => 'PCS', 'description' => '', 'receive' => 0, 'cancel' => false, 'reload' => false],
                    ['code' => '003020010001', 'name' => 'LAKBAN HITAM', 'qty' => 1, 'unit' => 'PCS', 'description' => '', 'receive' => 0, 'cancel' => false, 'reload' => false],
                ]
            ],
            [
                'id' => 2,
                'acc' => true,
                'type' => 'PO',
                'ref' => '',
                'user_no' => '624',
                'date' => '21/03/2018',
                'tl_qty' => 16,
                'note' => '',
                'process' => true,
                'user_id' => 'PASS',
                'audit' => 'PASS',
                'status' => 'Normal',
                'warehouse' => 'HEAD OFFICE - ATK',
                'items' => [
                    ['code' => '003020004017', 'name' => 'PULPEN', 'qty' => 10, 'unit' => 'PCS', 'description' => '', 'receive' => 0, 'cancel' => false, 'reload' => false],
                    ['code' => '003020017025', 'name' => 'PENGGARIS', 'qty' => 6, 'unit' => 'PCS', 'description' => '', 'receive' => 0, 'cancel' => false, 'reload' => false],
                ]
            ],
            [
                'id' => 3,
                'acc' => true,
                'type' => 'PO',
                'ref' => '',
                'user_no' => '627',
                'date' => '2018-04-02',
                'tl_qty' => 200,
                'note' => '',
                'process' => true,
                'user_id' => '',
                'audit' => 'PASS',
                'status' => 'Urgent',
                'warehouse' => 'LOGISTIK',
                'items' => []
            ],
            [
                'id' => 4,
                'acc' => true,
                'type' => 'PO',
                'ref' => '',
                'user_no' => '629',
                'date' => '2018-07-02',
                'tl_qty' => 2,
                'note' => '',
                'process' => true,
                'user_id' => 'BUDI',
                'audit' => 'BUDI',
                'status' => 'Normal',
                'warehouse' => 'LOGISTIK',
                'items' => []
            ],
            [
                'id' => 5,
                'acc' => true,
                'type' => 'PO',
                'ref' => '211/FINAN...',
                'user_no' => '630',
                'date' => '2018-09-10',
                'tl_qty' => 1,
                'note' => '',
                'process' => true,
                'user_id' => 'AYU',
                'audit' => 'AYU',
                'status' => 'Normal',
                'warehouse' => 'LOGISTIK',
                'items' => []
            ]
        ];

        return view('inventory.order_requests.index', compact('orderRequests'));
    }
}
