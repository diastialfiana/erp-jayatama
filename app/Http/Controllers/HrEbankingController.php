<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HrEbankingController extends Controller
{
    public function index()
    {
        $records = [
            [
                'id' => 1,
                'date' => '14/01/2026',
                'type' => 'HR',
                'data_ref' => '',
                'user_no' => '10853',
                'account_no' => 'Terlampir',
                'account_name' => 'Terlampir',
                'bank_name' => 'Terlampir',
                'total' => '261.905.931',
                'audit' => 'RIYO',
                'select_data' => 'Operational',
                'invoice_no' => ''
            ],
            [
                'id' => 2,
                'date' => '09/01/2026',
                'type' => 'HR',
                'data_ref' => '',
                'user_no' => '10826',
                'account_no' => 'Terlampir',
                'account_name' => 'Terlampir',
                'bank_name' => 'Terlampir',
                'total' => '286.834.055',
                'audit' => 'RIYO',
                'select_data' => 'Operational',
                'invoice_no' => ''
            ],
            [
                'id' => 3,
                'date' => '14/01/2026',
                'type' => 'HR',
                'data_ref' => '',
                'user_no' => '10852',
                'account_no' => 'Terlampir',
                'account_name' => 'Terlampir',
                'bank_name' => 'Terlampir',
                'total' => '709.797',
                'audit' => 'RIYO',
                'select_data' => 'Non Operational',
                'invoice_no' => ''
            ],
            [
                'id' => 4,
                'date' => '29/01/2026',
                'type' => 'HR',
                'data_ref' => '',
                'user_no' => '10900',
                'account_no' => 'Terlampir',
                'account_name' => 'Terlampir',
                'bank_name' => 'Terlampir',
                'total' => '16.864.227.569',
                'audit' => 'RIYO',
                'select_data' => 'Operational',
                'invoice_no' => ''
            ]
        ];

        return view('inventory.hr_ebanking.index', compact('records'));
    }
}
