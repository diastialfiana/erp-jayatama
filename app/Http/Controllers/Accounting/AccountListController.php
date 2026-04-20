<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AccountListController extends Controller
{
    public function index()
    {
        $accounts = Account::orderBy('code')->paginate(50);

        return view('accounting.account-list.index', compact('accounts'));
    }

    public function show($id)
    {
        $account = Account::find($id);

        if (!$account) {
            abort(404, 'Account not found');
        }

        return view('accounting.account-list.detail', compact('account'));
    }

    public function statistics($id)
    {
        $account = Account::findOrFail($id);

        $months = [
            'january','february','march','april','may','june',
            'july','august','september','october','november','december'
        ];

        $monthlyData = [0,0,0,0,0,0,0,0,0,0,0,$account->balance];

        return view('accounting.account-list.statistics', compact('account','months','monthlyData'));
    }

    public function backdate(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $date = $request->date ?? now()->toDateString();

        // CURRENT BALANCE (from transactions)
        $currentBalance = AccountTransaction::where('account_id', $id)
            ->sum(DB::raw('debit - credit'));

        // BACKDATE BALANCE (<= selected date)
        $backdateBalance = AccountTransaction::where('account_id', $id)
            ->whereDate('date', '<=', $date)
            ->sum(DB::raw('debit - credit'));

        return view('accounting.account-list.backdate', compact(
            'account',
            'currentBalance',
            'backdateBalance'
        ));
    }

    public function activity(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $from = $request->input('from');
        $thru = $request->input('thru');

        $query = AccountTransaction::query()->where('account_id', $id);

        if ($from) {
            $query->whereDate('date', '>=', $from);
        }
        if ($thru) {
            $query->whereDate('date', '<=', $thru);
        }

        // Default: newest on top (ERP)
        $transactions = $query
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Running balance (saldo berjalan) computed from transactions (not from accounts.balance)
        $endBalanceQuery = AccountTransaction::query()
            ->where('account_id', $id);

        if ($thru) {
            $endBalanceQuery->whereDate('date', '<=', $thru);
        }

        $running = (float) $endBalanceQuery->sum(DB::raw('debit - credit'));

        $rows = $transactions->map(function (AccountTransaction $tx) use (&$running) {
            $row = clone $tx;
            $row->running_balance = $running;

            $delta = (float) (($tx->debit ?? 0) - ($tx->credit ?? 0));
            $running -= $delta;

            return $row;
        });

        return view('accounting.account-list.activity', [
            'account' => $account,
            'transactions' => $rows,
            'from' => $from,
            'thru' => $thru,
        ]);
    }

    public function summary(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $from = $request->input('from') ?: now()->toDateString();
        $thru = $request->input('thru') ?: now()->toDateString();

        $hasData = AccountTransaction::where('account_id', $id)
            ->whereDate('date', '<=', $thru)
            ->exists();

        $begBalance = (float) AccountTransaction::where('account_id', $id)
            ->whereDate('date', '<', $from)
            ->sum(DB::raw('debit - credit'));

        $debit = (float) AccountTransaction::where('account_id', $id)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $thru)
            ->sum('debit');

        $credit = (float) AccountTransaction::where('account_id', $id)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $thru)
            ->sum('credit');

        $endBalance = $begBalance + $debit - $credit;

        return view('accounting.account-list.summary', [
            'account' => $account,
            'from'    => $from,
            'thru'    => $thru,
            'hasData' => $hasData,
            'begBalance' => $begBalance,
            'debit'      => $debit,
            'credit'     => $credit,
            'endBalance' => $endBalance,
        ]);
    }
}
