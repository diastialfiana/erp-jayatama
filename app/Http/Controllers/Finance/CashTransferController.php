<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Finance\CashTransfer;
use App\Models\Finance\CashTransferDetail;
use App\Models\Finance\BankAccount;

class CashTransferController extends Controller
{
    public function index()
    {
        $banks = BankAccount::all();
        return view('finance.cash-transfer.index', compact('banks'));
    }

    public function apiBanks()
    {
        return response()->json(BankAccount::select('id', 'code', 'bank_name as name', 'currency')->get());
    }

    public function recordsList()
    {
        $transfers = CashTransfer::with(['user', 'fromBank', 'details'])->latest()->get();
        return view('finance.cash-transfer.records-list', compact('transfers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'reference' => 'nullable|string',
            'currency' => 'required|string',
            'rate' => 'required|numeric',
            'from_bank_id' => 'required|integer',
            'note' => 'nullable|string',
            'details' => 'required|json', // Grid Details
        ]);

        $details = json_decode($validated['details'], true);

        if (empty($details)) return back()->with('error', 'Minimal 1 tujuan transfer.');

        $totalAmount = 0;
        foreach ($details as $d) {
            $amt = floatval($d['amount'] ?? 0);
            if ($amt <= 0) return back()->with('error', 'Amount harus lebih dari 0.');
            if (empty($d['to_bank_id'])) return back()->with('error', 'Bank tujuan wajib diisi.');
            if ($d['to_bank_id'] == $validated['from_bank_id']) return back()->with('error', 'Bank tujuan (' . $d['to_bank_id'] . ') tidak boleh sama dengan bank sumber.');
            
            $totalAmount += $amt;
        }

        try {
            DB::beginTransaction();
            
            $cashTransfer = CashTransfer::create([
                'date' => $validated['date'],
                'reference' => $validated['reference'] ?? 'TR-' . time(),
                'currency' => $validated['currency'],
                'rate' => $validated['rate'],
                'from_bank_id' => $validated['from_bank_id'],
                'total_amount' => $totalAmount,
                'note' => $validated['note'],
                'created_by' => auth()->id() ?? 1,
            ]);

            foreach ($details as $d) {
                CashTransferDetail::create([
                    'cash_transfer_id' => $cashTransfer->id,
                    'to_bank_id' => $d['to_bank_id'],
                    'cost_id' => $d['cost_id'] ?? null,
                    'dept_id' => $d['dept_id'] ?? null,
                    'currency' => $d['currency'] ?? 'IDR',
                    'rate' => $d['rate'] ?? 1,
                    'amount' => $d['amount'],
                    'description' => $d['description'] ?? null,
                ]);

                // Target bank goes up (Debit)
                \App\Models\Finance\BankTransaction::record([
                    'bank_account_id' => $d['to_bank_id'],
                    'type'            => 'transfer',
                    'reference_id'    => $cashTransfer->id,
                    'reference_type'  => \App\Models\Finance\CashTransfer::class,
                    'department_id'   => $d['dept_id'] ?? null,
                    'cost_center_id'  => $d['cost_id'] ?? null,
                    'date'            => $cashTransfer->date,
                    'reference'       => $cashTransfer->reference,
                    'description'     => $d['description'] ?? 'Transfer from bank '.$cashTransfer->from_bank_id,
                    'amount'          => $d['amount'] ?? 0,
                    'debit'           => $d['amount'] ?? 0,
                    'credit'          => 0,
                    'currency'        => $d['currency'] ?? 'IDR',
                    'rate'            => $d['rate'] ?? 1,
                    'created_by'      => $cashTransfer->created_by,
                ]);
                
                // Source bank goes down (Credit)
                \App\Models\Finance\BankTransaction::record([
                    'bank_account_id' => $cashTransfer->from_bank_id,
                    'type'            => 'transfer',
                    'reference_id'    => $cashTransfer->id,
                    'reference_type'  => \App\Models\Finance\CashTransfer::class,
                    'department_id'   => $d['dept_id'] ?? null,
                    'cost_center_id'  => $d['cost_id'] ?? null,
                    'date'            => $cashTransfer->date,
                    'reference'       => $cashTransfer->reference,
                    'description'     => $d['description'] ?? 'Transfer to bank '.$d['to_bank_id'],
                    'amount'          => $d['amount'] ?? 0,
                    'debit'           => 0,
                    'credit'          => $d['amount'] ?? 0,
                    'currency'        => $d['currency'] ?? 'IDR',
                    'rate'            => $d['rate'] ?? 1,
                    'created_by'      => $cashTransfer->created_by,
                ]);
            }

            DB::commit();
            return redirect()->route('finance.cash-transfer.index')->with('success', 'Cash Transfer berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $ct = CashTransfer::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            \App\Models\Finance\BankTransaction::where('reference_id', $ct->id)
                ->where('reference_type', \App\Models\Finance\CashTransfer::class)
                ->delete();

            $ct->details()->delete();
            $ct->delete();
            DB::commit();
            
            return redirect()->route('finance.cash-transfer.index')->with('success', 'Transfer deleted and balances reverted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }
}
