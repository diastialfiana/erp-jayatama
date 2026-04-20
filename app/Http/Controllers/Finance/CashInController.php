<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\CashIn;
use App\Models\Finance\CashInDetail;
use App\Models\Finance\ChartOfAccount;
use App\Models\Administration\Department;
use App\Models\Administration\CostCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashInController extends Controller
{
    public function index()
    {
        return view('finance.cash-in.index');
    }

    public function recordsList()
    {
        $cashIns = CashIn::with(['user', 'bankAccount', 'details'])
            ->latest()
            ->get()
            ->map(function ($c) {
                $c->cash_amount = $c->details->sum('amount');
                $c->giro_amount = 0; // standard mock for now
                return $c;
            });

        return view('finance.cash-in.records-list', compact('cashIns'));
    }

    public function create()
    {
        return view('finance.cash-in.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'reference' => 'nullable|string',
            'currency' => 'required|string',
            'rate' => 'required|numeric',
            'bank_account_id' => 'nullable|integer',
            'note' => 'nullable|string',
            'details' => 'required|json',
        ]);

        $details = json_decode($validated['details'], true);

        if (empty($details) || count($details) === 0) {
            return back()->with('error', 'Minimal 1 detail required.');
        }

        $totalAmount = 0;
        foreach ($details as $d) {
            $totalAmount += floatval($d['amount'] ?? 0);
            if (empty($d['account_id'])) {
                return back()->with('error', 'Account is required for every row.');
            }
        }

        if ($totalAmount == 0) {
            return back()->with('error', 'Total amount cannot be zero.');
        }

        try {
            DB::beginTransaction();

            $cashIn = CashIn::create([
                'date' => $validated['date'],
                'reference' => $validated['reference'] ?? 'CI-' . time(),
                'currency' => $validated['currency'],
                'rate' => $validated['rate'],
                'bank_account_id' => $validated['bank_account_id'],
                'note' => $validated['note'],
                'created_by' => auth()->id() ?? 1,
            ]);

            foreach ($details as $d) {
                CashInDetail::create([
                    'cash_in_id' => $cashIn->id,
                    'account_id' => $d['account_id'],
                    'dept_id' => $d['dept_id'] ?? null,
                    'cost_id' => $d['cost_id'] ?? null,
                    'amount' => $d['amount'] ?? 0,
                    'description' => $d['description'] ?? null,
                ]);

                \App\Models\Finance\BankTransaction::record([
                    'bank_account_id' => $cashIn->bank_account_id,
                    'type'            => 'cash_in',
                    'reference_id'    => $cashIn->id,
                    'reference_type'  => \App\Models\Finance\CashIn::class,
                    'account_id'      => $d['account_id'],
                    'department_id'   => $d['dept_id'] ?? null,
                    'cost_center_id'  => $d['cost_id'] ?? null,
                    'date'            => $cashIn->date,
                    'reference'       => $cashIn->reference,
                    'description'     => $d['description'] ?? $cashIn->note,
                    'amount'          => $d['amount'] ?? 0,
                    'debit'           => $d['amount'] ?? 0,
                    'credit'          => 0,
                    'currency'        => $cashIn->currency,
                    'rate'            => $cashIn->rate,
                    'created_by'      => $cashIn->created_by,
                ]);
            }

            DB::commit();

            return redirect()->route('finance.cash-in.index')->with('success', 'Cash In saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $cashIn = CashIn::findOrFail($id);
        $cashIn->details()->delete();
        $cashIn->delete();

        return redirect()->route('finance.cash-in.index')->with('success', 'Transaction deleted.');
    }

    // API endpoints for Grid Select
    public function apiAccounts()
    {
        $accounts = ChartOfAccount::select('id', 'code', 'name')->get();
        return response()->json($accounts);
    }
    
    public function apiDepartments()
    {
        $depts = Department::select('id', 'code', 'name')->get();
        return response()->json($depts);
    }
    
    public function apiCostCenters()
    {
        $costs = CostCenter::select('id', 'code', 'name')->get();
        return response()->json($costs);
    }
}
