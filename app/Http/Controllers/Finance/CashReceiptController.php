<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\CashReceipt;
use App\Models\Finance\CashReceiptDetail;
use App\Models\Finance\JournalEntry;
use App\Models\Finance\SalesInvoice;
use App\Models\Finance\Customer;
use App\Models\Finance\BankAccount;
use App\Models\Finance\ChartOfAccount;
use App\Models\Administration\Department;
use App\Models\Administration\CostCenter;
use Illuminate\Support\Facades\DB;

class CashReceiptController extends Controller
{
    public function index()
    {
        return view('finance.cash-receipt.index');
    }

    public function recordsList()
    {
        $receipts = CashReceipt::with([
            'customer',
            'createdBy',
            'bankAccount'
        ])->latest()->get();
        return view('finance.cash-receipt.records-list', compact('receipts'));
    }

    public function apiCustomers()
    {
        return response()->json(Customer::select('id', 'name', 'code')->get());
    }

    public function apiBanks()
    {
        return response()->json(BankAccount::select('id', 'bank_name', 'bank_account', 'currency', 'code', 'ar_account')->get());
    }

    public function apiInvoices(Request $request)
    {
        $customerId = $request->customer_id;
        if (!$customerId) return response()->json([]);

        // Get unpaid or partially paid invoices for the customer
        $invoices = SalesInvoice::where('customer_id', $customerId)
            ->where(function($q) {
                $q->whereNull('balance')->orWhere('balance', '>', 0);
            })
            ->select('id', 'invoice_number', 'date', 'due_date', 'total', 'balance')
            ->get();
            
        // Map balance correctly
        $invoices = $invoices->map(function($inv) {
            $balance = $inv->balance !== null ? $inv->balance : $inv->total;
            $inv->current_balance = $balance;
            return $inv;
        });

        return response()->json($invoices);
    }

    public function apiAccounts()
    {
        return response()->json(ChartOfAccount::select('id', 'code', 'name')->get());
    }

    public function apiDeps()
    {
        return response()->json(Department::select('id', 'code', 'name')->get());
    }

    public function apiCosts()
    {
        return response()->json(CostCenter::select('id', 'code', 'name')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'bank_id' => 'required|exists:bank_accounts,id',
            'currency' => 'required|string',
            'rate' => 'required|numeric',
        ]);

        $details = json_decode($request->input('details', '[]'), true);
        $journals = json_decode($request->input('journals', '[]'), true);

        if (empty($details)) {
            return back()->with('error', 'Please select at least one invoice and enter a payment amount.');
        }

        $totalPaid = 0;
        foreach ($details as $d) {
            $totalPaid += (float)$d['amount'];
        }

        if ($totalPaid <= 0) {
            return back()->with('error', 'Total amount paid must be greater than zero.');
        }

        try {
            DB::beginTransaction();

            $receipt = CashReceipt::create([
                'date' => $request->date,
                'reference' => $request->reference,
                'customer_id' => $request->customer_id,
                'bank_id' => $request->bank_id,
                'currency' => $request->currency,
                'rate' => $request->rate,
                'total' => $totalPaid,
                'note' => $request->note,
                'created_by' => auth()->id(),
            ]);

            // Save details and update invoice balance
            foreach ($details as $d) {
                if ($d['amount'] > 0) {
                    CashReceiptDetail::create([
                        'receipt_id' => $receipt->id,
                        'invoice_id' => $d['invoice_id'],
                        'amount' => $d['amount'],
                        'discount' => $d['discount'] ?? 0,
                        'prepaid' => $d['prepaid'] ?? 0,
                    ]);

                    $invoice = SalesInvoice::find($d['invoice_id']);
                    if ($invoice) {
                        $currentBalance = $invoice->balance !== null ? $invoice->balance : $invoice->total;
                        $amountApplied = (float)$d['amount'] + (float)($d['discount'] ?? 0) - (float)($d['prepaid'] ?? 0);
                        
                        $invoice->balance = $currentBalance - $amountApplied;
                        $invoice->paid = (float)($invoice->paid ?? 0) + $amountApplied;
                        $invoice->save();
                    }
                }
            }

            // Save manual journals from Grid 2
            $totalDebit = 0;
            $totalCredit = 0;
            foreach ($journals as $j) {
                $debit = (float)($j['debit'] ?? 0);
                $credit = (float)($j['credit'] ?? 0);

                if ($debit == 0 && $credit == 0) continue;

                $totalDebit += $debit;
                $totalCredit += $credit;

                JournalEntry::create([
                    'receipt_id' => $receipt->id,
                    'account_id' => $j['account_id'] ?: null,
                    'department_id' => $j['department_id'] ?: null,
                    'cost_center_id' => $j['cost_center_id'] ?: null,
                    'debit' => $debit,
                    'credit' => $credit,
                    'description' => $j['description'] ?? null,
                ]);
            }

            // Validate Double Entry! (with 2 decimals precision)
            if (round($totalDebit, 2) !== round($totalCredit, 2)) {
                DB::rollBack();
                return back()->with('error', 'Accounting entry is not balanced. Total Debit: ' . $totalDebit . ', Total Credit: ' . $totalCredit);
            }

            DB::commit();

            return redirect()->route('finance.cash-receipt.index')->with('success', 'Cash receipt saved successfully. Receipt No: CR-' . $receipt->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error saving cash receipt: ' . $e->getMessage());
        }
    }
}
