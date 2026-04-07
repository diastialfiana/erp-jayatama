<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResourceServiceController extends Controller
{
    public function index()
    {
        $contracts = [
            [
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
                'alert' => false
            ],
            [
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
                'note' => '',
                'audit' => 'PASS',
                'days' => '-2882',
                'recid' => '44f35504',
                'alert' => true
            ]
        ];

        return view('inventory.resource_services.index', compact('contracts'));
    }
}
