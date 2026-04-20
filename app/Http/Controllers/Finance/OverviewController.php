<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\BankAccount;
use App\Models\Finance\BankTransaction;

class OverviewController extends Controller
{
    public function index()
    {
        // Total Bank Balance (SUM per bank or just sum of all transactions)
        // Asset total = sum(debit - credit) from all bank transactions
        $totalBankBalance = BankTransaction::selectRaw('COALESCE(SUM(debit - credit), 0) as total')
            ->value('total');

        $totalCashIn = BankTransaction::where('type', 'cash_in')->sum('amount');
        $totalCashOut = BankTransaction::where('type', 'cash_out')->sum('amount');
        
        $totalReceipts = BankTransaction::where('type', 'receipt')->sum('amount');
        // Total penerimaan uang bisa digabung cash_in dan receipt jika user menganggap itu in. Tapi kita tampilkan terpisah juga ok.
        $totalUangMasuk = $totalCashIn + $totalReceipts;

        $banks = BankAccount::all();

        return view('finance.index', compact('totalBankBalance', 'totalUangMasuk', 'totalCashOut', 'banks'));
    }
}
