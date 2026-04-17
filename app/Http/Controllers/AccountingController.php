<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index()
    {
        return view('accounting.index');
    }

    public function arInvoice()
    {
        $arInvoices = [
            [
                'id' => 1,
                'type' => 'INV',
                'ref' => 'AR-2401-0001',
                'user_no' => 'USR001',
                'date' => '2024-01-10',
                'duedate' => '2024-02-10',
                'customer_name' => 'PT JAYA ABADI',
                'currency' => 'IDR',
                'rate' => 1.00,
                'note' => 'Sales of items batch A',
                'amount_total' => 1500000.00,
                'items' => [
                    ['account' => '400-10', 'account_desc' => 'Sales Revenue', 'dept' => 'Sales', 'cost' => 'Marketing', 'amount' => 1500000, 'description' => 'Penjualan Batch A']
                ]
            ],
            [
                'id' => 2,
                'type' => 'INV',
                'ref' => 'AR-2401-0002',
                'user_no' => 'USR002',
                'date' => '2024-01-15',
                'duedate' => '2024-02-15',
                'customer_name' => 'CV MAJU TERUS',
                'currency' => 'IDR',
                'rate' => 1.00,
                'note' => 'Monthly subscription service fee',
                'amount_total' => 3000000.00,
                'items' => [
                    ['account' => '400-20', 'account_desc' => 'Service Revenue', 'dept' => 'IT', 'cost' => 'Ops', 'amount' => 1000000, 'description' => 'Setup Fee'],
                    ['account' => '400-21', 'account_desc' => 'Maintenance', 'dept' => 'IT', 'cost' => 'Ops', 'amount' => 2000000, 'description' => 'Monthly Fee']
                ]
            ]
        ];

        return view('accounting.ar_invoice.index', compact('arInvoices'));
    }

    public function arReturn()
    {
        return view('accounting.index');
    }

    public function apInvoice()
    {
        $apInvoices = [
            [
                'id' => 1,
                'type' => 'VI',
                'ref' => 'periode Januari 2026',
                'user_no' => '10830',
                'link' => '180402',
                'date' => '2026-01-05',
                'duedate' => '2026-02-04',
                'supplier_name' => 'REGIONAL OFFICE MEDAN',
                'currency' => 'IDR',
                'rate' => 1,
                'amount_total' => 528675.00,
                'paid' => 0.00,
                'disc' => 0.00,
                'pph23' => 0.00,
                'note' => 'Note',
                'tax' => 0.00,
                'audit' => 'RIZKI',
                'prepaid' => 0.00,
                'items' => [
                    ['account' => '613001IDR', 'account_desc' => 'BIAYA RUMAHTANGGA KANTOR', 'dept' => '05C', 'cost' => '006', 'amount' => 528675.00, 'pph23' => 0, 'ppn' => 0, 'total' => 528675.00, 'description' => 'Reimburse Pemakaian Petty Cash Regional']
                ],
                'status_records' => [
                    ['desc' => 'Invoice diterima dari vendor', 'ga' => true, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'upd_states' => '5/1/26 09:48:15', 'checked_by' => 'ZAINAL'],
                    ['desc' => 'Invoice di serahkan ke user', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'upd_states' => '', 'checked_by' => ''],
                    ['desc' => 'Invoice diterima dari user', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'upd_states' => '', 'checked_by' => ''],
                    ['desc' => 'Pembuatan voucher transaksi', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'upd_states' => '', 'checked_by' => ''],
                    ['desc' => 'Checker', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'upd_states' => '', 'checked_by' => ''],
                    ['desc' => 'Otorisasi PINDIV Finance', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'upd_states' => '', 'checked_by' => '']
                ]
            ],
            [
                'id' => 2,
                'type' => 'VI',
                'ref' => 'MD No. 003...',
                'user_no' => '10831',
                'link' => '180403',
                'date' => '2026-01-05',
                'duedate' => '2026-01-10',
                'supplier_name' => 'BPJS KESEHATAN 1',
                'currency' => 'IDR',
                'rate' => 1,
                'amount_total' => 843072302.00,
                'paid' => 0.00,
                'disc' => 0.00,
                'pph23' => 0.00,
                'note' => 'BPJS Kesehatan PT J...',
                'tax' => 0.00,
                'audit' => 'RIZKI',
                'prepaid' => 0.00,
                'items' => [],
                'status_records' => []
            ]
        ];

        return view('accounting.ap_invoice.index', compact('apInvoices'));
    }

    public function apReturn()
    {
        return view('accounting.index');
    }
}
