<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Customer;
use App\Models\Finance\SalesInvoice;
use App\Models\Finance\CashReceipt;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource. (List All)
     */
    public function index()
    {
        $customers = Customer::orderBy('code', 'asc')->get();
        $total = $customers->count();
        return view('finance.customers.list', compact('customers', 'total'));
    }

    /**
     * Show the form for creating a new customer (blank detail form).
     */
    public function detail()
    {
        $customer   = new Customer();
        $navigation = $this->getNavigation(null);
        return view('finance.customers.detail', compact('customer', 'navigation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                   => 'required|string|unique:customers',
            'counter_name'           => 'required|string',
            'currency'               => 'required|string',
            'receivable_account_id'  => 'required|integer',
            'address'                => 'nullable|string',
            'phone'                  => 'nullable|string',
            'fax'                    => 'nullable|string',
            'mobile_phone'           => 'nullable|string',
            'region'                 => 'nullable|string',
            'initial_name'           => 'nullable|string',
            'invoice_layout'         => 'nullable|string',
            'cost_center_id'         => 'nullable|integer',
            'account_dept_id'        => 'nullable|integer',
            'default_bank_account_id'=> 'nullable|integer',
            'is_corporate_group'     => 'boolean',
            'group_id'               => 'nullable|integer',
            'prepaid_account_id'     => 'nullable|integer',
            'pph23_account_id'       => 'nullable|integer',
            'tax_account_id'         => 'nullable|integer',
            'sales_account_id'       => 'nullable|integer',
            'sales_return_account_id'=> 'nullable|integer',
        ]);

        $validated['is_corporate_group'] = $request->has('is_corporate_group');

        $customer = Customer::create($validated);

        // ✅ FIX: route yang benar
        return redirect()->route('finance.customers.show', $customer->id)
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource (Detail View).
     */
    public function show(Customer $customer)
    {
        $navigation = $this->getNavigation($customer->id);
        return view('finance.customers.detail', compact('customer', 'navigation'));
    }

    /**
     * Records list (alias index untuk tab navigasi).
     */
    public function recordsList()
    {
        $customers = Customer::orderBy('code', 'asc')->get();
        $total     = $customers->count();
        return view('finance.customers.list', compact('customers', 'total'));
    }

    /**
     * JSON data API (untuk Tabulator / AJAX).
     */
    public function data()
    {
        return response()->json(Customer::orderBy('code')->get());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'code'                   => 'required|string|unique:customers,code,' . $customer->id,
            'counter_name'           => 'required|string',
            'currency'               => 'required|string',
            'receivable_account_id'  => 'required|integer',
            'address'                => 'nullable|string',
            'phone'                  => 'nullable|string',
            'fax'                    => 'nullable|string',
            'mobile_phone'           => 'nullable|string',
            'region'                 => 'nullable|string',
            'initial_name'           => 'nullable|string',
            'invoice_layout'         => 'nullable|string',
            'cost_center_id'         => 'nullable|integer',
            'account_dept_id'        => 'nullable|integer',
            'default_bank_account_id'=> 'nullable|integer',
            'is_corporate_group'     => 'boolean',
            'group_id'               => 'nullable|integer',
            'prepaid_account_id'     => 'nullable|integer',
            'pph23_account_id'       => 'nullable|integer',
            'tax_account_id'         => 'nullable|integer',
            'sales_account_id'       => 'nullable|integer',
            'sales_return_account_id'=> 'nullable|integer',
        ]);

        $validated['is_corporate_group'] = $request->has('is_corporate_group');

        $customer->update($validated);

        // ✅ FIX: route yang benar
        return redirect()->route('finance.customers.show', $customer->id)
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Delete a customer (soft delete via Customer model).
     */
    public function delete(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('finance.customers.index')
            ->with('success', 'Customer deleted.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // ERP TABS — semua menerima $id dan query real data dari DB
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Statistic tab — monthly invoice chart per customer.
     */
    public function statistic($id = null)
    {
        $customer = $id ? Customer::findOrFail($id) : Customer::orderBy('code')->first();
        $year     = (int) request('year', now()->year);

        $months = [
            'January','February','March','April','May','June',
            'July','August','September','October','November','December',
        ];

        // Init arrays
        $balArray = [];
        $dpArray  = [];
        for ($m = 1; $m <= 12; $m++) {
            $balArray[$m] = 0.0;
            $dpArray[$m]  = 0.0;
        }

        if ($customer) {
            $invoicesByMonth = SalesInvoice::where('customer_id', $customer->id)
                ->whereYear('date', $year)
                ->selectRaw('MONTH(date) as month, SUM(total) as total_invoice, SUM(COALESCE(paid,0)) as total_paid')
                ->groupBy('month')
                ->get();

            foreach ($invoicesByMonth as $inv) {
                $balArray[$inv->month] = (float) $inv->total_invoice;
                $dpArray[$inv->month]  = (float) $inv->total_paid;
            }
        }

        $balances = array_values($balArray);
        $dps      = array_values($dpArray);
        $totalBal = array_sum($balances);
        $totalDp  = array_sum($dps);
        $txCount  = count(array_filter($balances));

        return view('finance.customers.statistic', compact(
            'customer', 'year', 'months', 'balances', 'dps', 'totalBal', 'totalDp', 'txCount'
        ));
    }

    /**
     * Activity tab — invoice & receipt ledger with running balance.
     */
    public function activity($id = null)
    {
        $customer = $id ? Customer::findOrFail($id) : Customer::orderBy('code')->first();
        $fromDate = request('from_date', now()->startOfMonth()->format('Y-m-d'));
        $thruDate = request('thru_date', now()->format('Y-m-d'));

        $activities = collect();

        if ($customer) {
            // Invoices → Debit
            $invoices = SalesInvoice::where('customer_id', $customer->id)
                ->whereBetween('date', [$fromDate, $thruDate])
                ->orderBy('date')
                ->get()
                ->map(fn($inv) => [
                    'date'      => Carbon::parse($inv->date)->format('d/m/Y'),
                    'userno'    => $inv->invoice_number,
                    'note'      => 'Sales Invoice',
                    'debit'     => (float) $inv->total,
                    'credit'    => 0.0,
                    'balance'   => 0.0,
                    'ref'       => $inv->reference ?? '-',
                    'curr'      => $inv->currency,
                    'sort_date' => $inv->date,
                ]);

            // Cash Receipts → Credit
            $receipts = CashReceipt::where('customer_id', $customer->id)
                ->whereBetween('date', [$fromDate, $thruDate])
                ->orderBy('date')
                ->get()
                ->map(fn($rcv) => [
                    'date'      => Carbon::parse($rcv->date)->format('d/m/Y'),
                    'userno'    => 'CR-' . str_pad($rcv->id, 5, '0', STR_PAD_LEFT),
                    'note'      => 'Cash Receipt',
                    'debit'     => 0.0,
                    'credit'    => (float) $rcv->total,
                    'balance'   => 0.0,
                    'ref'       => $rcv->reference ?? '-',
                    'curr'      => $rcv->currency,
                    'sort_date' => $rcv->date,
                ]);

            // Merge, sort, compute running balance
            $running    = 0.0;
            $activities = $invoices->merge($receipts)
                ->sortBy('sort_date')
                ->values()
                ->map(function ($row) use (&$running) {
                    $running       += $row['debit'] - $row['credit'];
                    $row['balance'] = $running;
                    return $row;
                });
        }

        $totalDebit  = $activities->sum('debit');
        $totalCredit = $activities->sum('credit');

        return view('finance.customers.activity', compact(
            'customer', 'fromDate', 'thruDate', 'activities', 'totalDebit', 'totalCredit'
        ));
    }

    /**
     * Backdate tab — all customer balances as of a historical date.
     */
    public function backdate($id = null)
    {
        $activeCustomer = $id ? Customer::findOrFail($id) : null;
        $backdateStr    = request('backdate', now()->format('Y-m-d'));

        $customers = Customer::orderBy('code')->get()->map(function ($c) use ($backdateStr, $activeCustomer) {
            $totalInvoice = (float) SalesInvoice::where('customer_id', $c->id)
                ->whereDate('date', '<=', $backdateStr)->sum('total');

            $totalPaid = (float) CashReceipt::where('customer_id', $c->id)
                ->whereDate('date', '<=', $backdateStr)->sum('total');

            return [
                'id'      => $c->id,
                'code'    => $c->code,
                'curr'    => $c->currency,
                'name'    => $c->counter_name,
                'balance' => number_format($totalInvoice - $totalPaid, 2),
                'dp'      => number_format((float) ($c->down_payment ?? 0), 2),
                'active'  => $activeCustomer && $activeCustomer->id === $c->id,
            ];
        });

        return view('finance.customers.backdate', compact(
            'customers', 'activeCustomer', 'backdateStr'
        ));
    }

    /**
     * Summary tab — period summary: beg balance, invoice, receipt per customer.
     */
    public function summary($id = null)
    {
        $from = request('from_date', now()->startOfMonth()->format('Y-m-d'));
        $thru = request('thru_date', now()->format('Y-m-d'));

        $rows = Customer::orderBy('code')->get()->map(function ($c) use ($from, $thru) {
            // Beginning balance (before period)
            $begInvoice = (float) SalesInvoice::where('customer_id', $c->id)
                ->whereDate('date', '<', $from)->sum('total');
            $begPaid    = (float) CashReceipt::where('customer_id', $c->id)
                ->whereDate('date', '<', $from)->sum('total');

            $invoice = (float) SalesInvoice::where('customer_id', $c->id)
                ->whereBetween('date', [$from, $thru])->sum('total');

            $receipt = (float) CashReceipt::where('customer_id', $c->id)
                ->whereBetween('date', [$from, $thru])->sum('total');

            return [
                'code'    => $c->code,
                'name'    => $c->counter_name,
                'beg'     => $begInvoice - $begPaid,
                'invoice' => $invoice,
                'return'  => $receipt,
            ];
        })
        // Only show customers with actual activity
        ->filter(fn($r) => $r['beg'] != 0 || $r['invoice'] != 0 || $r['return'] != 0)
        ->values();

        // Chart data — top 5 by invoice amount
        $top5    = $rows->sortByDesc('invoice')->take(5)->values();
        $labels  = $top5->pluck('name');
        $invoice = $top5->pluck('invoice');
        $return  = $top5->pluck('return');

        return view('finance.customers.summary', compact(
            'rows', 'from', 'thru', 'labels', 'invoice', 'return'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────────────────

    private function getNavigation($currentId = null)
    {
        $total = Customer::count();
        $first = Customer::orderBy('id', 'asc')->first();
        $last  = Customer::orderBy('id', 'desc')->first();

        $prev            = null;
        $next            = null;
        $currentPosition = 0;

        if ($currentId) {
            $prev            = Customer::where('id', '<', $currentId)->orderBy('id', 'desc')->first();
            $next            = Customer::where('id', '>', $currentId)->orderBy('id', 'asc')->first();
            $currentPosition = Customer::where('id', '<=', $currentId)->count();
        }

        return [
            'total'           => $total,
            'first'           => $first ? $first->id : null,
            'last'            => $last  ? $last->id  : null,
            'prev'            => $prev  ? $prev->id  : null,
            'next'            => $next  ? $next->id  : null,
            'currentPosition' => $currentPosition,
        ];
    }
}
