<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Customer;
use Illuminate\Http\Request;

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
     * Show the form for creating/editing a customer detail
     */
    public function detail()
    {
        $customer = new Customer();
        $navigation = $this->getNavigation(null);
        return view('finance.customers.detail', compact('customer', 'navigation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:customers',
            'counter_name' => 'required|string',
            'currency' => 'required|string',
            'receivable_account_id' => 'required|integer',
            // Allow other fields
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'fax' => 'nullable|string',
            'mobile_phone' => 'nullable|string',
            'region' => 'nullable|string',
            'initial_name' => 'nullable|string',
            'invoice_layout' => 'nullable|string',
            'cost_center_id' => 'nullable|integer',
            'account_dept_id' => 'nullable|integer',
            'default_bank_account_id' => 'nullable|integer',
            'is_corporate_group' => 'boolean',
            'group_id' => 'nullable|integer',
            'prepaid_account_id' => 'nullable|integer',
            'pph23_account_id' => 'nullable|integer',
            'tax_account_id' => 'nullable|integer',
            'sales_account_id' => 'nullable|integer',
            'sales_return_account_id' => 'nullable|integer',
        ]);

        $validated['is_corporate_group'] = $request->has('is_corporate_group');

        $customer = Customer::create($validated);

        return redirect()->route('finance.customers.detail.show', $customer->id)
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $navigation = $this->getNavigation($customer->id);

        return view('finance.customers.detail', compact('customer', 'navigation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:customers,code,' . $customer->id,
            'counter_name' => 'required|string',
            'currency' => 'required|string',
            'receivable_account_id' => 'required|integer',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'fax' => 'nullable|string',
            'mobile_phone' => 'nullable|string',
            'region' => 'nullable|string',
            'initial_name' => 'nullable|string',
            'invoice_layout' => 'nullable|string',
            'cost_center_id' => 'nullable|integer',
            'account_dept_id' => 'nullable|integer',
            'default_bank_account_id' => 'nullable|integer',
            'is_corporate_group' => 'boolean',
            'group_id' => 'nullable|integer',
            'prepaid_account_id' => 'nullable|integer',
            'pph23_account_id' => 'nullable|integer',
            'tax_account_id' => 'nullable|integer',
            'sales_account_id' => 'nullable|integer',
            'sales_return_account_id' => 'nullable|integer',
        ]);

        $validated['is_corporate_group'] = $request->has('is_corporate_group');

        $customer->update($validated);

        return redirect()->route('finance.customers.detail.show', $customer->id)
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Get Navigation Details (First, Prev, Next, Last)
     */
    private function getNavigation($currentId = null)
    {
        $total = Customer::count();
        $first = Customer::orderBy('id', 'asc')->first();
        $last = Customer::orderBy('id', 'desc')->first();

        $prev = null;
        $next = null;
        $currentPosition = 0;

        if ($currentId) {
            $prev = Customer::where('id', '<', $currentId)->orderBy('id', 'desc')->first();
            $next = Customer::where('id', '>', $currentId)->orderBy('id', 'asc')->first();
            $currentPosition = Customer::where('id', '<=', $currentId)->count();
        }

        return [
            'total' => $total,
            'first' => $first ? $first->id : null,
            'last' => $last ? $last->id : null,
            'prev' => $prev ? $prev->id : null,
            'next' => $next ? $next->id : null,
            'currentPosition' => $currentPosition
        ];
    }

    // Placed tabs logic without {customer} parameters per user request
    public function statistic()
    {
        return view('finance.customers.statistic');
    }
    public function activity()
    {
        return view('finance.customers.activity');
    }
    public function backdate()
    {
        return view('finance.customers.backdate');
    }
    public function summary()
    {
        $rows = [
            ['code'=>'001','name'=>'PT. JASA SWADAYA UTAMA','beg'=>12500000,'invoice'=>28750000,'return'=>1250000],
            ['code'=>'002','name'=>'PT. GLOBAL MANDIRI',    'beg'=>4250000, 'invoice'=>15000000,'return'=>500000],
            ['code'=>'003','name'=>'CV. CIPTA KARYA',       'beg'=>0,       'invoice'=>6500000, 'return'=>0],
            ['code'=>'004','name'=>'PT. SINAR JAYA ABADI',  'beg'=>25750000,'invoice'=>42000000,'return'=>3200000],
            ['code'=>'005','name'=>'EUROTECH INDONESIA',     'beg'=>1200000, 'invoice'=>8800000, 'return'=>800000],
            ['code'=>'006','name'=>'UD. MAJU BERSAMA',       'beg'=>6300000, 'invoice'=>12500000,'return'=>250000],
            ['code'=>'007','name'=>'PT. NUSA BANGSA',        'beg'=>0,       'invoice'=>3200000, 'return'=>0],
            ['code'=>'008','name'=>'LION CITY TRADING',      'beg'=>5400000, 'invoice'=>19500000,'return'=>1500000],
            ['code'=>'009','name'=>'BINTANG GEMILANG',       'beg'=>8900000, 'invoice'=>22000000,'return'=>700000],
            ['code'=>'010','name'=>'KOPERASI SEJAHTERA',     'beg'=>1150000, 'invoice'=>5000000, 'return'=>0],
        ];

        $labels = collect($rows)->pluck('name')->take(5)->values();
        $invoice = collect($rows)->pluck('invoice')->take(5)->values();
        $return = collect($rows)->pluck('return')->take(5)->values();

        return view('finance.customers.summary', [
            'rows' => $rows,
            'labels' => $labels,
            'invoice' => $invoice,
            'return' => $return
        ]);
    }
}
