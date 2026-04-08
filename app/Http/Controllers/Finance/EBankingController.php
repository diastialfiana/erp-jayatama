<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Finance\EbankingRequest;

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

    public function apiInvoices(Request $request)
    {
        return response()->json([
            ['id' => 'INV-2026-001', 'account_no' => '542-123-991', 'account_name' => 'PT. INDO JAYA SAKTI', 'bank_name' => 'BCA', 'amount' => 15500000],
            ['id' => 'INV-2026-002', 'account_no' => '119-099-281', 'account_name' => 'CV. ABADI MAKMUR', 'bank_name' => 'MANDIRI', 'amount' => 7450000],
            ['id' => 'INV-2026-003', 'account_no' => '990-212-005', 'account_name' => 'PT. LESTARI ALAM', 'bank_name' => 'BNI', 'amount' => 2200000],
            ['id' => 'OP-2026-011', 'account_no' => '443-122-300', 'account_name' => 'PLN (PERSERO)', 'bank_name' => 'BRI', 'amount' => 9500000],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:operational,non_operational',
            'invoice_id' => 'nullable|string',
            'account_no' => 'required|string',
            'account_name' => 'required|string',
            'bank_name' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            EbankingRequest::create([
                'date' => $validated['date'],
                'type' => $validated['type'],
                'invoice_id' => $validated['invoice_id'],
                'account_no' => $validated['account_no'],
                'account_name' => $validated['account_name'],
                'bank_name' => $validated['bank_name'],
                'amount' => $validated['amount'],
                'status' => 'pending',
                'created_by' => auth()->id() ?? 1,
            ]);

            return redirect()->route('finance.ebanking-request.index')->with('success', 'e-Banking Request submitted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit request: ' . $e->getMessage());
        }
    }
}
