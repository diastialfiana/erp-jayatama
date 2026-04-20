<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Finance\Advance;
use App\Models\Finance\AdvanceDetail;
use App\Models\Finance\BankAccount;
use App\Models\Finance\ChartOfAccount;
use App\Models\User;

class AdvanceReportController extends Controller
{
    public function index()
    {
        $banks = BankAccount::all();
        $employees = User::all();
        return view('finance.advance-report.index', compact('banks', 'employees'));
    }

    public function recordList()
    {
        $advances = Advance::with(['employee', 'bankAccount', 'creator'])->latest()->get();
        return view('finance.advance-report.records-list', compact('advances'));
    }

    public function apiAccounts()
    {
        // ✅ FIX: Query dari ChartOfAccount DB, bukan dummy
        return response()->json(
            ChartOfAccount::select('id', 'code', 'name')->orderBy('code')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'reference' => 'nullable|string',
            'currency' => 'required|string',
            'rate' => 'required|numeric',
            'employee_id' => 'required|integer',
            'bank_account_id' => 'required|integer',
            'note' => 'nullable|string',
            'advance_data' => 'required|json',
            'expenses_data' => 'required|json',
        ]);

        $advData = json_decode($validated['advance_data'], true);
        $expData = json_decode($validated['expenses_data'], true);

        if (empty($expData)) return back()->with('error', 'Minimal 1 baris expense wajib diisi.');

        $totalAdvance = floatval($advData['advance'] ?? 0);
        $totalCashback = floatval($advData['cashback'] ?? 0);
        $totalCashLess = floatval($advData['cash_less'] ?? 0);

        $totalExpenses = 0;
        foreach ($expData as $e) {
            $amt = floatval($e['amount'] ?? 0);
            if ($amt <= 0) return back()->with('error', 'Semua expense harus lebih dari 0.');
            $totalExpenses += $amt;
        }

        try {
            DB::beginTransaction();

            $advance = Advance::create([
                'date' => $validated['date'],
                'reference' => $validated['reference'] ?? 'ADV-' . time(),
                'currency' => $validated['currency'],
                'rate' => $validated['rate'],
                'employee_id' => $validated['employee_id'],
                'bank_account_id' => $validated['bank_account_id'],
                'total_advance' => $totalAdvance,
                'total_expenses' => $totalExpenses,
                'total_cashback' => $totalCashback,
                'total_cash_less' => $totalCashLess,
                'note' => $validated['note'],
                'created_by' => auth()->id() ?? 1,
            ]);

            foreach ($expData as $e) {
                AdvanceDetail::create([
                    'advance_id' => $advance->id,
                    'account_id' => $e['account_code'] ?? null,
                    'dept_id' => $e['dept_id'] ?? null,
                    'cost_id' => $e['cost_id'] ?? null,
                    'amount' => $e['amount'],
                    'description' => $e['description'] ?? null,
                ]);
            }

            // Optional: Bank balance updating logic similar to CashOut
            // Hitung net affect to bank account. 
            // If we gave advance (kas keluar), it decreases balance.
            // If cashback (dikembalikan), it increases balance.
            // If cash less (kurang dan kita bayar lagi), it decreases balance.
            // Total yang keluar dari bank = total_advance - total_cashback + total_cash_less (asumsi reimburse final)
            
            $netKasKeluar = $totalAdvance - $totalCashback + $totalCashLess;
            
            \App\Models\Finance\BankTransaction::record([
                'bank_account_id' => $validated['bank_account_id'],
                'type'            => 'advance',
                'reference_id'    => $advance->id,
                'reference_type'  => \App\Models\Finance\Advance::class,
                'date'            => $advance->date,
                'reference'       => $advance->reference,
                'description'     => $advance->note ?? 'Advance Settlement',
                'amount'          => abs($netKasKeluar),
                'debit'           => $netKasKeluar < 0 ? abs($netKasKeluar) : 0,
                'credit'          => $netKasKeluar > 0 ? abs($netKasKeluar) : 0,
                'currency'        => $advance->currency,
                'rate'            => $advance->rate,
                'created_by'      => $advance->created_by,
            ]);

            DB::commit();
            return redirect()->route('finance.advance-report.index')->with('success', 'Advance Report berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $adv = Advance::findOrFail($id);
        
        try {
            DB::beginTransaction();

            \App\Models\Finance\BankTransaction::where('reference_id', $adv->id)
                ->where('reference_type', \App\Models\Finance\Advance::class)
                ->delete();

            $adv->details()->delete();
            $adv->delete();
            
            DB::commit();
            
            return redirect()->route('finance.advance-report.index')->with('success', 'Advance deleted and balances reverted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }
}
