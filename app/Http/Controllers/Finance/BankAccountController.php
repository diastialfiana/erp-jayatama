<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\BankAccount;
use App\Models\Finance\BankTransaction;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Default: redirect ke Records List.
     */
    public function index()
    {
        // ✅ FIX: route yang benar dengan prefix finance.
        return redirect()->route('finance.bank-accounts.records-list');
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

        $lookups = $this->getLookupData();

        return view('finance.bank-account.record-detail', array_merge(compact(
            'current', 'navigation', 'total'
        ), $lookups));
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

        $lookups = $this->getLookupData();

        return view('finance.bank-account.record-detail', array_merge(compact(
            'current', 'navigation', 'total'
        ), $lookups));
    }

    /**
     * Lookup data shared between recordDetail() and show().
     * Centralized here to avoid duplication.
     */
    private function getLookupData(): array
    {
        return [
            'branches'    => ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Makassar'],
            'currencies'  => ['IDR', 'USD', 'SGD', 'EUR', 'JPY'],
            'categories'  => ['BANK LOCAL', 'BANK FOREIGN', 'CASH'],
            'bankList'    => ['BANK MEGA', 'BANK BCA', 'BANK MANDIRI', 'BANK BRI', 'BANK BNI', 'BANK CIMB'],
            'costCenters' => ['UMUM', 'OPERASIONAL', 'FINANCE', 'MARKETING', 'PRODUKSI'],
            'departments' => ['FINANCE', 'ACCOUNTING', 'HRD', 'OPERASIONAL', 'MARKETING'],
        ];
    }

    /**
     * Show the full records list grid.
     */
    public function recordsList()
    {
        $banks = BankAccount::orderBy('code')->get();
        return view('finance.bank-account.records-list', compact('banks'));
    }

    /**
     * Statistics tab — monthly balance via BankTransaction.
     */
    public function statistics(Request $request)
    {
        $bank = BankAccount::orderBy('code')->first();
        $year = (int) $request->get('year', now()->year);

        $months = [
            'JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE',
            'JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER',
        ];

        $beginningBalance = (float) ($bank?->balance ?? 0);
        $currentBalance   = (float) ($bank?->balance ?? 0);
        $monthlyBalances  = array_fill(1, 12, 0);

        if ($bank && class_exists(BankTransaction::class)) {
            $txByMonth = BankTransaction::where('bank_account_id', $bank->id)
                ->whereYear('date', $year)
                ->selectRaw('MONTH(date) as month, SUM(credit - debit) as net')
                ->groupBy('month')
                ->get();

            foreach ($txByMonth as $tx) {
                $monthlyBalances[$tx->month] = (float) $tx->net;
            }
        }

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

        $transactions = collect();
        $totalDebit   = 0;
        $totalCredit  = 0;

        if ($bank && class_exists(BankTransaction::class)) {
            $transactions = BankTransaction::where('bank_account_id', $bank->id)
                ->whereBetween('date', [$fromDate, $thruDate])
                ->with('createdBy')
                ->orderBy('date')
                ->get()
                ->map(fn($tx) => [
                    'id'         => $tx->id,
                    'date'       => \Carbon\Carbon::parse($tx->date)->format('d/m/Y'),
                    'value_date' => \Carbon\Carbon::parse($tx->date)->format('d/m/Y'),
                    'userno'     => $tx->createdBy?->name ?? 'SYSTEM',
                    'note'       => $tx->description,
                    'debit'      => (float) $tx->debit,
                    'credit'     => (float) $tx->credit,
                    'balance'    => (float) $tx->running_balance,
                    'reference'  => $tx->reference,
                    'currency'   => $bank->currency,
                    'rate'       => 1,
                    'link'       => '',
                ]);

            $totalDebit  = $transactions->sum('debit');
            $totalCredit = $transactions->sum('credit');
        }

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
        $banks       = BankAccount::orderBy('code')->get();

        if (class_exists(BankTransaction::class)) {
            $banks = $banks->map(function ($bank) use ($backdateStr) {
                $balance = (float) BankTransaction::where('bank_account_id', $bank->id)
                    ->whereDate('date', '<=', $backdateStr)
                    ->selectRaw('SUM(credit) - SUM(debit) as net')
                    ->value('net');

                $bank->computed_balance     = $balance;
                $bank->computed_audit_balance = $balance;
                return $bank;
            });
        } else {
            $banks = $banks->map(function ($bank) {
                $bank->computed_balance       = (float) $bank->balance;
                $bank->computed_audit_balance = (float) $bank->balance;
                return $bank;
            });
        }

        return view('finance.bank-account.backdate', compact(
            'banks', 'backdateStr'
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

        if (class_exists(BankTransaction::class)) {
            $summaryRows = $banks->map(function ($bank) use ($fromDate, $thruDate) {
                // beginning balance = all transactions before period
                $begBalance = (float) BankTransaction::where('bank_account_id', $bank->id)
                    ->whereDate('date', '<', $fromDate)
                    ->selectRaw('SUM(credit) - SUM(debit) as net')
                    ->value('net');

                $cashIn  = (float) BankTransaction::where('bank_account_id', $bank->id)
                    ->whereBetween('date', [$fromDate, $thruDate])
                    ->where('source_type', 'cash_in')->sum('credit');

                $cashOut = (float) BankTransaction::where('bank_account_id', $bank->id)
                    ->whereBetween('date', [$fromDate, $thruDate])
                    ->where('source_type', 'cash_out')->sum('debit');

                $receipt = (float) BankTransaction::where('bank_account_id', $bank->id)
                    ->whereBetween('date', [$fromDate, $thruDate])
                    ->where('source_type', 'cash_receipt')->sum('credit');

                $transIn  = (float) BankTransaction::where('bank_account_id', $bank->id)
                    ->whereBetween('date', [$fromDate, $thruDate])
                    ->where('source_type', 'cash_transfer_in')->sum('credit');

                $transOut = (float) BankTransaction::where('bank_account_id', $bank->id)
                    ->whereBetween('date', [$fromDate, $thruDate])
                    ->where('source_type', 'cash_transfer_out')->sum('debit');

                $endBalance = $begBalance + $cashIn + $receipt + $transIn - $cashOut - $transOut;

                return [
                    'code'       => $bank->code,
                    'bank_name'  => $bank->bank_name,
                    'currency'   => $bank->currency,
                    'beg_balance'=> $begBalance,
                    'cash_in'    => $cashIn,
                    'giro_in'    => 0,
                    'trans_in'   => $transIn,
                    'receipt'    => $receipt,
                    'cash_out'   => $cashOut,
                    'giro_out'   => 0,
                    'payment'    => 0,
                    'trans_out'  => $transOut,
                    'adjust'     => 0,
                    'balance'    => $endBalance,
                    'is_default' => $bank->is_default,
                ];
            });
        } else {
            $summaryRows = $banks->map(fn($b) => [
                'code'        => $b->code,
                'bank_name'   => $b->bank_name,
                'currency'    => $b->currency,
                'beg_balance' => (float) $b->balance,
                'cash_in'     => 0, 'giro_in' => 0, 'trans_in' => 0, 'receipt' => 0,
                'cash_out'    => 0, 'giro_out' => 0, 'payment'  => 0, 'trans_out' => 0,
                'adjust'      => 0,
                'balance'     => (float) $b->balance,
                'is_default'  => $b->is_default,
            ]);
        }

        return view('finance.bank-account.bank-summary', compact(
            'banks', 'fromDate', 'thruDate', 'summaryRows'
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

        if ($validated['is_default']) {
            BankAccount::where('is_default', true)->update(['is_default' => false]);
        }

        $bankAccount = BankAccount::create($validated);

        // ✅ FIX: route yang benar
        return redirect()->route('finance.bank-accounts.show', $bankAccount->id)
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

        // ✅ FIX: route yang benar
        return redirect()->route('finance.bank-accounts.show', $bankAccount->id)
            ->with('success', 'Bank Account updated successfully.');
    }
}
