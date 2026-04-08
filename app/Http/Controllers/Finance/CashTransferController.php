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

            // Update Saldo Asal (Berkurang)
            $fromBank = BankAccount::findOrFail($validated['from_bank_id']);
            $fromBank->decrement('balance', $totalAmount);

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

                // Update Saldo Tujuan (Bertambah)
                $toBank = BankAccount::findOrFail($d['to_bank_id']);
                $toBank->increment('balance', floatval($d['amount']));
            }

            DB::commit();
            return redirect()->route('finance.cash-transfer.index')->with('success', 'Cash Transfer berhasil disimpan dan antrian saldo telah disesuaikan.');
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
            
            // Revert balances
            $fromBank = BankAccount::findOrFail($ct->from_bank_id);
            $fromBank->increment('balance', $ct->total_amount);

            foreach ($ct->details as $d) {
                $toBank = BankAccount::findOrFail($d->to_bank_id);
                $toBank->decrement('balance', $d->amount);
            }

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
