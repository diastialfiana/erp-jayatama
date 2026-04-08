<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Default: redirect to Records List.
     */
    public function index()
    {
        return redirect()->route('bank-account.records-list');
    }

    /**
     * Show Record Detail form (first record or blank new form).
     */
    public function recordDetail()
    {
        $total      = BankAccount::count();
        $current    = BankAccount::orderBy('id', 'asc')->first();
        $currentPos = 1;

        $navigation = [
            'total'      => $total,
            'currentPos' => $currentPos,
            'first'      => $total > 0 ? BankAccount::orderBy('id','asc')->first()->id  : null,
            'last'       => $total > 0 ? BankAccount::orderBy('id','desc')->first()->id : null,
            'prev'       => null,
            'next'       => $total > 0 ? BankAccount::orderBy('id','asc')->skip(1)->first()?->id : null,
        ];

        $branches    = ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Makassar'];
        $currencies  = ['IDR', 'USD', 'SGD', 'EUR', 'JPY'];
        $categories  = ['BANK LOCAL', 'BANK FOREIGN', 'CASH'];
        $bankList    = ['BANK MEGA', 'BANK BCA', 'BANK MANDIRI', 'BANK BRI', 'BANK BNI', 'BANK CIMB'];
        $costCenters = ['UMUM', 'OPERASIONAL', 'FINANCE', 'MARKETING', 'PRODUKSI'];
        $departments = ['FINANCE', 'ACCOUNTING', 'HRD', 'OPERASIONAL', 'MARKETING'];

        return view('finance.bank-account.record-detail', compact(
            'current', 'navigation', 'total',
            'branches', 'currencies', 'categories', 'bankList', 'costCenters', 'departments'
        ));
    }

    /**
     * Show a specific record by ID with navigation.
     */
    public function show($id)
    {
        $current    = BankAccount::findOrFail($id);
        $total      = BankAccount::count();
        $currentPos = BankAccount::where('id', '<=', $id)->count();

        $navigation = [
            'total'      => $total,
            'currentPos' => $currentPos,
            'first'      => BankAccount::orderBy('id', 'asc')->first()->id,
            'last'       => BankAccount::orderBy('id', 'desc')->first()->id,
            'prev'       => BankAccount::where('id', '<', $id)->orderBy('id', 'desc')->first()?->id,
            'next'       => BankAccount::where('id', '>', $id)->orderBy('id', 'asc')->first()?->id,
        ];

        $branches    = ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Makassar'];
        $currencies  = ['IDR', 'USD', 'SGD', 'EUR', 'JPY'];
        $categories  = ['BANK LOCAL', 'BANK FOREIGN', 'CASH'];
        $bankList    = ['BANK MEGA', 'BANK BCA', 'BANK MANDIRI', 'BANK BRI', 'BANK BNI', 'BANK CIMB'];
        $costCenters = ['UMUM', 'OPERASIONAL', 'FINANCE', 'MARKETING', 'PRODUKSI'];
        $departments = ['FINANCE', 'ACCOUNTING', 'HRD', 'OPERASIONAL', 'MARKETING'];

        return view('finance.bank-account.record-detail', compact(
            'current', 'navigation', 'total',
            'branches', 'currencies', 'categories', 'bankList', 'costCenters', 'departments'
        ));
    }

    /**
     * Show the full records list grid.
     */
    public function recordsList()
    {
        $banks = BankAccount::orderBy('code')->get();

        // Mock data for UI preview (used when table is empty)
        $mockBanks = [
            ['code'=>'00001','currency'=>'IDR','bank_name'=>'BANK MEGA CAB. TENDEAN', 'category'=>'BANK LOCAL','balance'=>2158947733,'sys_balance'=>2158947733,'account_code'=>'112001IDR','ar_account'=>'112001IDR','cost_center'=>'000','department'=>'000','audit'=>'PASS','is_default'=>true],
            ['code'=>'00002','currency'=>'IDR','bank_name'=>'BANK MEGA MAXI',         'category'=>'BANK LOCAL','balance'=>-77092423,  'sys_balance'=>-77092423,  'account_code'=>'112002IDR','ar_account'=>'112002IDR','cost_center'=>'000','department'=>'000','audit'=>'RAFDI','is_default'=>false],
            ['code'=>'00003','currency'=>'IDR','bank_name'=>'BANK MEGA SYARIAH',      'category'=>'BANK LOCAL','balance'=>1491379081, 'sys_balance'=>1491379081, 'account_code'=>'112003IDR','ar_account'=>'112003IDR','cost_center'=>'000','department'=>'000','audit'=>'TOMMY','is_default'=>false],
            ['code'=>'00004','currency'=>'IDR','bank_name'=>'BANK BCA UTAMA',         'category'=>'BANK LOCAL','balance'=>987654321,  'sys_balance'=>987654321,  'account_code'=>'112004IDR','ar_account'=>'112004IDR','cost_center'=>'000','department'=>'000','audit'=>'PASS', 'is_default'=>false],
            ['code'=>'00005','currency'=>'USD','bank_name'=>'BANK BCA USD ACCOUNT',   'category'=>'BANK FOREIGN','balance'=>125000,   'sys_balance'=>125000,     'account_code'=>'112005USD','ar_account'=>'112005USD','cost_center'=>'001','department'=>'001','audit'=>'PASS', 'is_default'=>false],
            ['code'=>'00006','currency'=>'IDR','bank_name'=>'BANK MANDIRI',           'category'=>'BANK LOCAL','balance'=>350000000,  'sys_balance'=>350000000,  'account_code'=>'112006IDR','ar_account'=>'112006IDR','cost_center'=>'000','department'=>'000','audit'=>'DIAN', 'is_default'=>false],
            ['code'=>'00007','currency'=>'IDR','bank_name'=>'BANK BRI CABANG UTAMA',  'category'=>'BANK LOCAL','balance'=>0,          'sys_balance'=>0,          'account_code'=>'112007IDR','ar_account'=>'112007IDR','cost_center'=>'000','department'=>'000','audit'=>'PASS', 'is_default'=>false],
        ];

        return view('finance.bank-account.records-list', compact('banks', 'mockBanks'));
    }

    /**
     * Statistics tab — monthly balance table + chart.
     */
    public function statistics(Request $request)
    {
        // Default to first bank; can be extended to accept ?bank_id=X
        $bank = BankAccount::orderBy('code')->first();
        $year = (int) $request->get('year', now()->year);

        // Monthly mock data (replace with real BankTransaction queries when table exists)
        $months = [
            'JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE',
            'JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER',
        ];

        $currentBalance   = $bank?->balance ?? 2158947733;
        $beginningBalance = 2156447733;

        // Monthly balances – only March has data in this demo
        $monthlyBalances = [
            1  => 0, 2 => 0, 3 => 2500000, 4 => 0,
            5  => 0, 6 => 0, 7 => 0,        8 => 0,
            9  => 0, 10 => 0, 11 => 0,       12 => 0,
        ];

        return view('finance.bank-account.statistics', compact(
            'bank', 'year', 'months', 'currentBalance', 'beginningBalance', 'monthlyBalances'
        ));
    }

    /**
     * Activity tab — transaction history with date filter.
     */
    public function activity(Request $request)
    {
        $bank     = BankAccount::orderBy('code')->first();
        $fromDate = $request->get('from_date', now()->format('Y-m-d'));
        $thruDate = $request->get('thru_date', now()->format('Y-m-d'));

        // When BankTransaction model/table exists, replace mock with:
        // $transactions = BankTransaction::where('bank_account_id', $bank?->id)
        //     ->whereBetween('date', [$fromDate, $thruDate])->get();

        $transactions = collect([
            ['id'=>1,'date'=>'17/03/2026','value_date'=>'17/03/2026','userno'=>'ADMIN','note'=>'Pembayaran Invoice #INV-001','debit'=>5000000,  'credit'=>0,         'balance'=>2158947733,'reference'=>'INV-001','currency'=>'IDR','rate'=>1,'link'=>''],
            ['id'=>2,'date'=>'16/03/2026','value_date'=>'16/03/2026','userno'=>'RAFDI','note'=>'Transfer dari Bank Mandiri',  'debit'=>0,          'credit'=>12000000, 'balance'=>2153947733,'reference'=>'TRF-002','currency'=>'IDR','rate'=>1,'link'=>''],
            ['id'=>3,'date'=>'15/03/2026','value_date'=>'15/03/2026','userno'=>'TOMMY','note'=>'Biaya Administrasi Bank',    'debit'=>250000,     'credit'=>0,         'balance'=>2165947733,'reference'=>'ADM-003','currency'=>'IDR','rate'=>1,'link'=>''],
            ['id'=>4,'date'=>'14/03/2026','value_date'=>'14/03/2026','userno'=>'ADMIN','note'=>'Pembayaran Supplier #SUP-004','debit'=>8750000,   'credit'=>0,         'balance'=>2166197733,'reference'=>'SUP-004','currency'=>'IDR','rate'=>1,'link'=>''],
            ['id'=>5,'date'=>'13/03/2026','value_date'=>'13/03/2026','userno'=>'DIAN', 'note'=>'Penerimaan Piutang Dagang',  'debit'=>0,          'credit'=>25000000,  'balance'=>2174947733,'reference'=>'RCV-005','currency'=>'IDR','rate'=>1,'link'=>''],
            ['id'=>6,'date'=>'12/03/2026','value_date'=>'12/03/2026','userno'=>'ADMIN','note'=>'Pembayaran Gaji Karyawan',   'debit'=>45000000,   'credit'=>0,         'balance'=>2149947733,'reference'=>'SAL-006','currency'=>'IDR','rate'=>1,'link'=>''],
            ['id'=>7,'date'=>'11/03/2026','value_date'=>'11/03/2026','userno'=>'RAFDI','note'=>'Penjualan Tunai',            'debit'=>0,          'credit'=>3200000,   'balance'=>2194947733,'reference'=>'SAL-007','currency'=>'IDR','rate'=>1,'link'=>''],
        ]);

        $totalDebit  = $transactions->sum('debit');
        $totalCredit = $transactions->sum('credit');

        return view('finance.bank-account.activity', compact(
            'bank', 'fromDate', 'thruDate', 'transactions', 'totalDebit', 'totalCredit'
        ));
    }

    /**
     * Backdate tab — historical balance per bank on a given date.
     */
    public function backdate(Request $request)
    {
        $backdateStr = $request->get('date', now()->format('Y-m-d'));

        // When BankTransaction model exists, replace mock with:
        // $banks = BankAccount::withSum(['transactions as balance' => fn($q) =>
        //     $q->where('date', '<=', $backdateStr)
        // ], 'amount')->get();

        // Mock: all bank accounts with historical balance data
        $banks = BankAccount::orderBy('code')->get();

        // Mock historical balances keyed by code for demo
        $historicalBalances = [
            '00001' => ['balance' => 2150000000, 'audit_balance' => 2150000000],
            '00002' => ['balance' => -80000000,  'audit_balance' => -80000000],
            '00003' => ['balance' => 1480000000, 'audit_balance' => 1480000000],
            '00004' => ['balance' => 975000000,  'audit_balance' => 975000000],
            '00005' => ['balance' => 120000,      'audit_balance' => 120000],
            '00006' => ['balance' => 340000000,  'audit_balance' => 340000000],
            '00007' => ['balance' => 0,           'audit_balance' => 0],
        ];

        // Fallback mock rows when DB is empty
        $mockBanks = [
            ['code'=>'00001','currency'=>'IDR','bank_name'=>'BANK MEGA CAB. TENDEAN','balance'=>2150000000,'audit_balance'=>2150000000,'is_default'=>true ],
            ['code'=>'00002','currency'=>'IDR','bank_name'=>'BANK MEGA MAXI',        'balance'=>-80000000, 'audit_balance'=>-80000000, 'is_default'=>false],
            ['code'=>'00003','currency'=>'IDR','bank_name'=>'BANK MEGA SYARIAH',     'balance'=>1480000000,'audit_balance'=>1480000000,'is_default'=>false],
            ['code'=>'00004','currency'=>'IDR','bank_name'=>'BANK BCA UTAMA',        'balance'=>975000000, 'audit_balance'=>975000000, 'is_default'=>false],
            ['code'=>'00005','currency'=>'USD','bank_name'=>'BANK BCA USD ACCOUNT',  'balance'=>120000,    'audit_balance'=>120000,    'is_default'=>false],
            ['code'=>'00006','currency'=>'IDR','bank_name'=>'BANK MANDIRI',          'balance'=>340000000, 'audit_balance'=>340000000, 'is_default'=>false],
            ['code'=>'00007','currency'=>'IDR','bank_name'=>'BANK BRI CABANG UTAMA', 'balance'=>0,         'audit_balance'=>0,         'is_default'=>false],
        ];

        return view('finance.bank-account.backdate', compact(
            'banks', 'backdateStr', 'historicalBalances', 'mockBanks'
        ));
    }

    /**
     * Summary tab — cash-flow summary per bank for a given period.
     */
    public function bankSummary(Request $request)
    {
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $thruDate = $request->get('thru_date', now()->format('Y-m-d'));

        $banks = BankAccount::orderBy('code')->get();

        // Mock summary rows (replace with real aggregation queries when BankTransaction exists)
        $mockRows = [
            ['code'=>'00001','bank_name'=>'BANK MEGA CAB. TENDEAN','currency'=>'IDR','beg_balance'=>2156447733,'cash_in'=>5000000,'giro_in'=>0,'trans_in'=>12000000,'receipt'=>3200000,'cash_out'=>250000,'giro_out'=>0,'payment'=>8750000,'trans_out'=>45000000,'adjust'=>0,'balance'=>2122647733,'is_default'=>true ],
            ['code'=>'00002','bank_name'=>'BANK MEGA MAXI',        'currency'=>'IDR','beg_balance'=>-77092423, 'cash_in'=>0,       'giro_in'=>0,'trans_in'=>0,        'receipt'=>0,       'cash_out'=>0,      'giro_out'=>0,'payment'=>0,        'trans_out'=>0,        'adjust'=>0,'balance'=>-77092423, 'is_default'=>false],
            ['code'=>'00003','bank_name'=>'BANK MEGA SYARIAH',     'currency'=>'IDR','beg_balance'=>1491379081,'cash_in'=>0,       'giro_in'=>0,'trans_in'=>0,        'receipt'=>0,       'cash_out'=>0,      'giro_out'=>0,'payment'=>0,        'trans_out'=>0,        'adjust'=>0,'balance'=>1491379081,'is_default'=>false],
            ['code'=>'00004','bank_name'=>'BANK BCA UTAMA',        'currency'=>'IDR','beg_balance'=>987654321, 'cash_in'=>2500000, 'giro_in'=>0,'trans_in'=>0,        'receipt'=>500000,  'cash_out'=>0,      'giro_out'=>0,'payment'=>1200000,  'trans_out'=>0,        'adjust'=>0,'balance'=>989454321, 'is_default'=>false],
            ['code'=>'00005','bank_name'=>'BANK BCA USD ACCOUNT',  'currency'=>'USD','beg_balance'=>125000,   'cash_in'=>0,       'giro_in'=>0,'trans_in'=>5000,     'receipt'=>0,       'cash_out'=>0,      'giro_out'=>0,'payment'=>2500,     'trans_out'=>0,        'adjust'=>0,'balance'=>127500,    'is_default'=>false],
            ['code'=>'00006','bank_name'=>'BANK MANDIRI',          'currency'=>'IDR','beg_balance'=>350000000,'cash_in'=>0,       'giro_in'=>0,'trans_in'=>0,        'receipt'=>0,       'cash_out'=>0,      'giro_out'=>0,'payment'=>0,        'trans_out'=>0,        'adjust'=>0,'balance'=>350000000, 'is_default'=>false],
            ['code'=>'00007','bank_name'=>'BANK BRI CABANG UTAMA', 'currency'=>'IDR','beg_balance'=>0,        'cash_in'=>0,       'giro_in'=>0,'trans_in'=>0,        'receipt'=>0,       'cash_out'=>0,      'giro_out'=>0,'payment'=>0,        'trans_out'=>0,        'adjust'=>0,'balance'=>0,         'is_default'=>false],
        ];

        return view('finance.bank-account.bank-summary', compact(
            'banks', 'fromDate', 'thruDate', 'mockRows'
        ));
    }

    /**
     * Store a new bank account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'         => 'required|string|unique:bank_accounts,code',
            'currency'     => 'required|string',
            'bank_name'    => 'required|string',
            'description'  => 'nullable|string',
            'category'     => 'nullable|string',
            'bank_account' => 'nullable|string',
            'ar_account'   => 'nullable|string',
            'cost_center'  => 'nullable|string',
            'department'   => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
            'branch_id'    => 'nullable|integer',
            'is_default'   => 'boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');

        // Unset previous default if this is being set as default
        if ($validated['is_default']) {
            BankAccount::where('is_default', true)->update(['is_default' => false]);
        }

        $bankAccount = BankAccount::create($validated);

        return redirect()->route('bank-account.record-detail')
            ->with('success', 'Bank Account created successfully.');
    }

    /**
     * Update an existing bank account.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $validated = $request->validate([
            'code'         => 'required|string|unique:bank_accounts,code,' . $bankAccount->id,
            'currency'     => 'required|string',
            'bank_name'    => 'required|string',
            'description'  => 'nullable|string',
            'category'     => 'nullable|string',
            'bank_account' => 'nullable|string',
            'ar_account'   => 'nullable|string',
            'cost_center'  => 'nullable|string',
            'department'   => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
            'branch_id'    => 'nullable|integer',
            'is_default'   => 'boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');

        if ($validated['is_default']) {
            BankAccount::where('id', '!=', $bankAccount->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $bankAccount->update($validated);

        return redirect()->route('bank-account.show', $bankAccount->id)
            ->with('success', 'Bank Account updated successfully.');
    }
}
