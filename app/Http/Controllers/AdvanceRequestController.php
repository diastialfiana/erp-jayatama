<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdvanceRequestController extends Controller
{
    public function index()
    {
        $advanceRequests = [
            [
                'id' => 1,
                'post' => true,
                'date' => '2026-01-04',
                'user_no' => '6042',
                'doc_ref' => 'REF-6042',
                'employees_name' => 'Tarja',
                'bank_name' => 'BANK MEGA',
                'p_account_name' => 'TARJA PRATAMA',
                'account_no' => '123-456-789',
                'link' => '0',
                'total' => '4.278.282',
                'note' => 'Pembayaran PPH Final Dokter',
                'audit' => 'ANNE',
                'unique_id' => 'a12b3c4d',
                'due_date' => '2026-02-04',
                'account_name' => 'TARJA PRATAMA',
                'items' => [
                    [
                        'code' => '00008IDR',
                        'bank_name' => 'BANK MEGA',
                        'cost' => '003',
                        'dept' => '004',
                        'amount' => '4.278.282,00',
                        'description' => 'PBY PPH FINAL DOKTER DAN PENSIUN ...',
                        'account_name' => 'TARJA PRATAMA'
                    ]
                ],
                'status_steps' => [
                    ['id' => 1, 'description' => 'Invoice diterima dari vendor', 'ga' => true, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => 'Voucher finalized', 'date' => '4/1/26 12:29:42', 'checker' => 'TARJA'],
                    ['id' => 2, 'description' => 'Invoice di serahkan ke user', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 3, 'description' => 'Invoice diterima dari user', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 4, 'description' => 'Pembuatan voucher transaksi', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 5, 'description' => 'Checker', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 6, 'description' => 'Otorisasi PINDIV Finance', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 7, 'description' => 'Pembuatan Form IB/Cheque/Giro', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 8, 'description' => 'Otorisasi Direksi', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 9, 'description' => 'Input e-banking', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 10, 'description' => 'Approval e-banking', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 11, 'description' => 'Transaction Release', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                ],
                'total_amount' => '4.278.282,00'
            ],
            [
                'id' => 2,
                'post' => false,
                'date' => '2026-01-05',
                'user_no' => '6043',
                'doc_ref' => 'REF-9922',
                'employees_name' => 'BRYANE BUDIMAN',
                'bank_name' => 'MANDIRI',
                'account_name' => 'BRYANE BUDIMAN',
                'account_no' => '987-654-321',
                'link' => '0',
                'total' => '7.292.500',
                'note' => 'Akomodasi dinas luar kota',
                'audit' => 'ANNE',
                'unique_id' => 'd13f7a4a',
                'due_date' => '2026-02-05',
                'items' => [
                    [
                        'code' => '00002IDR',
                        'bank_name' => 'MANDIRI',
                        'cost' => '002',
                        'dept' => '02A',
                        'amount' => '7.292.500,00',
                        'description' => 'Akomodasi Perjalanan Dinas',
                        'account_name' => ''
                    ]
                ],
                'status_steps' => [
                    ['id' => 1, 'description' => 'Invoice diterima dari vendor', 'ga' => true, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '2026-01-05', 'checker' => 'ANNE'],
                    ['id' => 2, 'description' => 'Invoice di serahkan ke user', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 3, 'description' => 'Invoice diterima dari user', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 4, 'description' => 'Pembuatan voucher transaksi', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 5, 'description' => 'Checker', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 6, 'description' => 'Otorisasi PINDIV Finance', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 7, 'description' => 'Pembuatan Form IB/Cheque/Giro', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 8, 'description' => 'Otorisasi Direksi', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 9, 'description' => 'Input e-banking', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 10, 'description' => 'Approval e-banking', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 11, 'description' => 'Transaction Release', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                ],
                'total_amount' => '7.292.500,00'
            ],
            [
                'id' => 4,
                'post' => false,
                'date' => '2026-01-05',
                'user_no' => '6046',
                'doc_ref' => 'REF-6046',
                'employees_name' => 'YUNITA CHAERUNISSA',
                'bank_name' => 'BNI',
                'account_name' => 'ENVIRO PRIMA PT.',
                'account_no' => '571.031.1155',
                'link' => '0',
                'total' => '4.034.850',
                'note' => 'Pembelian Alat untuk Kantor Cabang',
                'audit' => 'YUNITA',
                'unique_id' => '429dff74',
                'due_date' => '2026-02-05',
                'items' => [
                    [
                        'code' => '00003IDR',
                        'bank_name' => 'BNI',
                        'cost' => '003',
                        'dept' => '03A',
                        'amount' => '4.034.850,00',
                        'description' => 'Pembelian Alat',
                        'account_name' => 'ENVIRO PRIMA PT.'
                    ]
                ],
                'status_steps' => [
                    ['id' => 1, 'description' => 'Invoice diterima dari vendor', 'ga' => true, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '2026-01-05', 'checker' => 'YUNITA'],
                    ['id' => 2, 'description' => 'Invoice di serahkan ke user', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 3, 'description' => 'Invoice diterima dari user', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 4, 'description' => 'Pembuatan voucher transaksi', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 5, 'description' => 'Checker', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 6, 'description' => 'Otorisasi PINDIV Finance', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 7, 'description' => 'Pembuatan Form IB/Cheque/Giro', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 8, 'description' => 'Otorisasi Direksi', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 9, 'description' => 'Input e-banking', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 10, 'description' => 'Approval e-banking', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                    ['id' => 11, 'description' => 'Transaction Release', 'ga' => false, 'us' => false, 'ap' => false, 'ac' => false, 'kc' => false, 'pf' => false, 'ap2' => false, 'duo' => false, 'ap3' => false, 'kf' => false, 'pf2' => false, 'note' => '', 'date' => '', 'checker' => ''],
                ],
                'total_amount' => '4.034.850,00'
            ]
        ];

        return view('inventory.advance_requests.index', compact('advanceRequests'));
    }
}
