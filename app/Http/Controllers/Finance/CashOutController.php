<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Finance\CashOut;
use App\Models\Finance\CashOutDetail;

class CashOutController extends Controller
{
    public function index()
    {
        return view('finance.cash-out.index');
    }

    public function recordsList()
    {
        $cashOuts = CashOut::with(['user', 'bankAccount', 'employee', 'details'])
            ->latest()
            ->get()
            ->map(function ($c) {
                $c->cash_amount = $c->details->sum('amount');
                $c->dp_amount = $c->is_down_payment ? $c->cash_amount : 0;
                return $c;
            });

        return view('finance.cash-out.records-list', compact('cashOuts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'reference' => 'nullable|string',
            'currency' => 'required|string',
            'rate' => 'required|numeric',
            'bank_account_id' => 'nullable|integer',
            'employee_id' => 'nullable|integer',
            'ca_reference' => 'nullable|string',
            'is_down_payment' => 'nullable|boolean',
            'note' => 'nullable|string',
            'details' => 'required|json',
        ]);

        $details = json_decode($validated['details'], true);

        if (empty($details)) return back()->with('error', 'Minimal 1 detail required.');
        
        $totalAmount = 0;
        foreach ($details as $d) {
            $totalAmount += floatval($d['amount'] ?? 0);
            if (empty($d['account_id'])) return back()->with('error', 'Account is required.');
        }

        if ($totalAmount == 0) return back()->with('error', 'Amount is zero. Not allowed unless intentionally 0-rated.');

        try {
            DB::beginTransaction();
            $cashOut = CashOut::create([
                'date' => $validated['date'],
                'reference' => $validated['reference'] ?? 'CO-' . time(),
                'currency' => $validated['currency'],
                'rate' => $validated['rate'],
                'bank_account_id' => $validated['bank_account_id'],
                'employee_id' => $validated['employee_id'],
                'ca_reference' => $validated['ca_reference'],
                'is_down_payment' => $validated['is_down_payment'] ?? 0,
                'note' => $validated['note'],
                'created_by' => auth()->id() ?? 1,
            ]);

            foreach ($details as $d) {
                CashOutDetail::create([
                    'cash_out_id' => $cashOut->id,
                    'account_id' => $d['account_id'],
                    'dept_id' => $d['dept_id'] ?? null,
                    'cost_id' => $d['cost_id'] ?? null,
                    'amount' => $d['amount'] ?? 0,
                    'description' => $d['description'] ?? null,
                ]);
            }
            DB::commit();
            return redirect()->route('finance.cash-out.index')->with('success', 'Cash Out saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $cashOut = CashOut::findOrFail($id);
        $cashOut->details()->delete();
        $cashOut->delete();

        return redirect()->route('finance.cash-out.index')->with('success', 'Transaction deleted.');
    }
}
