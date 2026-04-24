<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResourceServiceController extends Controller
{
    public function index()
    {
        $contracts = [
            [
                'id' => 1,
                'order_date' => '02/01/2018',
                'exp_date' => '09/05/2018',
                'userno' => '1022',
                'quotation' => '',
                'ref' => '',
                'customer_name' => 'PT. BANK MEGA TBK',
                'curr' => 'IDR',
                'rate' => '1',
                'amount' => '185.000',
                'discount' => '0',
                'tax_value' => '0,00',
                'pph_23' => '0,00',
                'total' => '160.000',
                'note' => '',
                'audit' => 'PASS',
                'days' => '-2868',
                'recid' => 'e93aad00',
                'alert' => false,
                'services' => [
                    ['code' => 'S01', 'name' => 'Support Maintenance', 'qty' => 10, 'price' => '2.000', 'amount' => '20.000', 'description' => 'Monthly support'],
                    ['code' => 'S02', 'name' => 'Consultation Fee', 'qty' => 7, 'price' => '20.000', 'amount' => '140.000', 'description' => 'Initial consultation'],
                    ['code' => 'S03', 'name' => 'Transport Allowance', 'qty' => 5, 'price' => '5.000', 'amount' => '25.000', 'description' => 'Site visit']
                ]
            ],
            [
                'id' => 2,
                'order_date' => '19/02/2018',
                'exp_date' => '25/04/2018',
                'userno' => '1020',
                'quotation' => '001/PH/18/000000...',
                'ref' => '',
                'customer_name' => 'PT. JASA SWADAYA UTAMA',
                'curr' => 'IDR',
                'rate' => '1',
                'amount' => '14.028.596',
                'discount' => '0',
                'tax_value' => '46.133,00',
                'pph_23' => '0,00',
                'total' => '14.074.729',
                'note' => 'Urgent request',
                'audit' => 'PASS',
                'days' => '-2882',
                'recid' => '44f35504',
                'alert' => true,
                'services' => [
                    ['code' => 'S04', 'name' => 'Cloud Hosting', 'qty' => 1, 'price' => '14.000.000', 'amount' => '14.000.000', 'description' => 'Annual subscription'],
                    ['code' => 'S05', 'name' => 'Domain Setup', 'qty' => 1, 'price' => '28.596', 'amount' => '28.596', 'description' => 'One-time setup']
                ]
            ]
        ];

        return view('inventory.resource_services.index', compact('contracts'));
    }
}
