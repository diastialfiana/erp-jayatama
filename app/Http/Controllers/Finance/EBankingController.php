<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Finance\EbankingRequest;
use App\Models\Finance\SalesInvoice;

class EBankingController extends Controller
{
    public function index()
    {
        return view('finance.ebanking-request.index');
    }

    public function recordList()
    {
        $requests = EbankingRequest::with(['user'])->latest()->get();
        return view('finance.ebanking-request.records-list', compact('requests'));
    }

    /**
     * ✅ FIX: apiInvoices() sekarang query dari sales_invoices yang belum lunas
     */
    public function apiInvoices(Request $request)
    {
        $invoices = SalesInvoice::with('customer')
            ->where(function ($q) {
                $q->whereNull('balance')->orWhere('balance', '>', 0);
            })
            ->select('id', 'invoice_number', 'customer_id', 'total', 'balance', 'currency')
            ->latest()
            ->get()
            ->map(fn($inv) => [
                'id'           => $inv->invoice_number,
                'account_name' => $inv->customer?->counter_name ?? '-',
                'bank_name'    => '-',
                'account_no'   => '-',
                'amount'       => $inv->balance !== null ? (float) $inv->balance : (float) $inv->total,
                'currency'     => $inv->currency,
            ]);

        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'         => 'required|date',
            'type'         => 'required|in:operational,non_operational',
            'invoice_id'   => 'nullable|string',
            'account_no'   => 'required|string',
            'account_name' => 'required|string',
            'bank_name'    => 'required|string',
            'amount'       => 'required|numeric|min:0.01',
        ]);

        try {
            EbankingRequest::create([
                'date'         => $validated['date'],
                'type'         => $validated['type'],
                'invoice_id'   => $validated['invoice_id'],
                'account_no'   => $validated['account_no'],
                'account_name' => $validated['account_name'],
                'bank_name'    => $validated['bank_name'],
                'amount'       => $validated['amount'],
                'status'       => 'pending',
                'created_by'   => auth()->id() ?? 1,
            ]);

            return redirect()->route('finance.ebanking-request.index')
                ->with('success', 'e-Banking Request submitted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit request: ' . $e->getMessage());
        }
    }

    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'bank_account_id' => 'required|integer'
        ]);

        $ebRequest = EbankingRequest::findOrFail($id);

        if ($ebRequest->status !== 'pending') {
            return back()->with('error', 'Request is not pending.');
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $ebRequest->update(['status' => 'approved']);

            // Flow: E-Banking out = Cash out (Credit bank)
            \App\Models\Finance\BankTransaction::record([
                'bank_account_id' => $validated['bank_account_id'],
                'type'            => 'manual',
                'reference_id'    => $ebRequest->id,
                'reference_type'  => \App\Models\Finance\EbankingRequest::class,
                'date'            => now(),
                'description'     => 'E-Banking Approval for ' . $ebRequest->account_name . ($ebRequest->invoice_id ? ' (Inv: ' . $ebRequest->invoice_id . ')' : ''),
                'amount'          => $ebRequest->amount,
                'debit'           => 0,
                'credit'          => $ebRequest->amount, // Uang kaluar
                'currency'        => 'IDR',
                'created_by'      => auth()->id(),
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->back()->with('success', 'E-Banking approved and posted to Bank Transactions.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Failed to approve: ' . $e->getMessage());
        }
    }
}
