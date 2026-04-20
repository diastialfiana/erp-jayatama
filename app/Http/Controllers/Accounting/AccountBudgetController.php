<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountBudget;
use App\Models\AccountTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountBudgetController extends Controller
{
    public function detailView(Request $request, $id = null)
    {
        // 1. Get Accounts for Right Grid
        $accounts = Account::orderBy('code')->get();

        if ($accounts->isEmpty()) {
            return "No accounts found.";
        }

        // Selected Account
        $account = $id ? Account::findOrFail($id) : $accounts->first();

        $thisYear = now()->year;
        $lastYear = $thisYear - 1;

        // Fetch This Year's budet
        $budgetsThisYear = AccountBudget::where('account_id', $account->id)
            ->where('year', $thisYear)
            ->get()
            ->keyBy('month');

        // Fetch Last Year's budget
        $budgetsLastYear = AccountBudget::where('account_id', $account->id)
            ->where('year', $lastYear)
            ->get()
            ->keyBy('month');

        // Fallback: If no budget for Last Year, check transactions
        // For accurate display, we group transactions by month in $lastYear
        $hasLastYearBudget = $budgetsLastYear->isNotEmpty();
        $transactionsLastYear = [];

        if (!$hasLastYearBudget) {
            $transactionsLastYear = AccountTransaction::select(
                DB::raw('MONTH(date) as m'),
                DB::raw('SUM(debit - credit) as net')
            )
            ->where('account_id', $account->id)
            ->whereYear('date', $lastYear)
            ->groupBy('m')
            ->pluck('net', 'm');
        }

        $months = [
            1 => 'JANUARY', 2 => 'FEBRUARY', 3 => 'MARCH',
            4 => 'APRIL', 5 => 'MAY', 6 => 'JUNE',
            7 => 'JULY', 8 => 'AUGUST', 9 => 'SEPTEMBER',
            10 => 'OCTOBER', 11 => 'NOVEMBER', 12 => 'DECEMBER'
        ];

        // Format data
        $budgetData = [];
        $totalThisYear = 0;
        $totalLastYear = 0;

        foreach ($months as $num => $name) {
            $tyAmount = isset($budgetsThisYear[$num]) ? (float)$budgetsThisYear[$num]->amount : 0;
            
            if ($hasLastYearBudget) {
                $lyAmount = isset($budgetsLastYear[$num]) ? (float)$budgetsLastYear[$num]->amount : 0;
            } else {
                $lyAmount = isset($transactionsLastYear[$num]) ? (float)$transactionsLastYear[$num] : 0;
            }

            $budgetData[$num] = [
                'name' => $name,
                'this_year' => $tyAmount,
                'last_year' => $lyAmount,
            ];

            $totalThisYear += $tyAmount;
            $totalLastYear += $lyAmount;
        }

        return view('accounting.account-budget.detail-view', compact(
            'accounts', 'account', 'thisYear', 'lastYear', 
            'budgetData', 'totalThisYear', 'totalLastYear'
        ));
    }

    public function store(Request $request, $id)
    {
        $account = Account::findOrFail($id);
        $thisYear = now()->year;

        $budgets = $request->input('budgets', []);

        DB::transaction(function () use ($account, $thisYear, $budgets) {
            foreach ($budgets as $month => $amount) {
                // Ensure amount is parsed securely
                $cleanAmount = str_replace(',', '', $amount);
                
                AccountBudget::updateOrCreate(
                    [
                        'account_id' => $account->id,
                        'year' => $thisYear,
                        'month' => $month
                    ],
                    [
                        'amount' => (float)$cleanAmount
                    ]
                );
            }
        });

        // Use session to pass success message or just reload
        return redirect()->route('accounting.account-budget', ['id' => $id]);
    }

    public function statistics(Request $request, $id = null)
    {
        $accounts = Account::orderBy('code')->get();
        if ($accounts->isEmpty()) {
            return "No accounts found.";
        }
        $account = $id ? Account::findOrFail($id) : $accounts->first();

        $thisYear = now()->year;
        $lastYear = $thisYear - 1;

        $budgetsThisYear = AccountBudget::where('account_id', $account->id)
            ->where('year', $thisYear)->pluck('amount', 'month');
        
        $budgetsLastYear = AccountBudget::where('account_id', $account->id)
            ->where('year', $lastYear)->pluck('amount', 'month');

        // Realities: Sum of debit-credit per month for thisYear
        $realitiesData = AccountTransaction::select(
                DB::raw('MONTH(date) as m'),
                DB::raw('SUM(debit - credit) as net')
            )
            ->where('account_id', $account->id)
            ->whereYear('date', $thisYear)
            ->groupBy('m')
            ->pluck('net', 'm');

        // Begin balance (before thisYear)
        $beginBalance = (float) AccountTransaction::where('account_id', $account->id)
            ->whereYear('date', '<', $thisYear)
            ->sum(DB::raw('debit - credit'));

        $months = [
            1 => 'JANUARY', 2 => 'FEBRUARY', 3 => 'MARCH',
            4 => 'APRIL', 5 => 'MAY', 6 => 'JUNE',
            7 => 'JULY', 8 => 'AUGUST', 9 => 'SEPTEMBER',
            10 => 'OCTOBER', 11 => 'NOVEMBER', 12 => 'DECEMBER'
        ];

        $statData = [];
        $runningBalance = $beginBalance;
        
        $totalBalance = 0;
        $totalLastYear = 0;
        $totalThisYear = 0;
        $totalRealities = 0;

        foreach ($months as $num => $name) {
            $ly = isset($budgetsLastYear[$num]) ? (float)$budgetsLastYear[$num] : 0;
            $ty = isset($budgetsThisYear[$num]) ? (float)$budgetsThisYear[$num] : 0;
            $real = isset($realitiesData[$num]) ? (float)$realitiesData[$num] : 0;
            
            $runningBalance += $real;

            $statData[$num] = [
                'name' => $name,
                'balance' => $runningBalance,
                'last_year' => $ly,
                'this_year' => $ty,
                'realities' => $real,
            ];

            // Wait, summing Balance makes no accounting sense but we can sum if needed. Let's just pass runningBalance.
            $totalLastYear += $ly;
            $totalThisYear += $ty;
            $totalRealities += $real;
        }

        // For Chart
        $chartThisYear = collect($statData)->pluck('this_year')->values()->toJson();
        $chartRealities = collect($statData)->pluck('realities')->values()->toJson();

        return view('accounting.account-budget.statistics', compact(
            'accounts', 'account', 'thisYear', 'lastYear', 
            'statData', 'runningBalance', 'totalLastYear', 'totalThisYear', 'totalRealities',
            'chartThisYear', 'chartRealities'
        ));
    }
}
