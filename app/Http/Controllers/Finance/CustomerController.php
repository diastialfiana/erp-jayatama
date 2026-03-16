<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all customers to display on the list page
        $customers = Customer::orderBy('id', 'asc')->get();
        return view('finance.customers.list', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function detail()
    {
        $customer = new Customer();

        $navigation = $this->getNavigation(null);

        // Required master data can be passed here (like COA list, cost centers, etc)
        // For now, we will pass empty arrays or fetch from respective models if they existed.

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

    // Placeholders for other tabs
    public function list(Customer $customer)
    {
        return view('finance.customers.list', compact('customer'));
    }
    public function statistic(Customer $customer)
    {
        return view('finance.customers.statistic', compact('customer'));
    }
    public function activity(Customer $customer)
    {
        return view('finance.customers.activity', compact('customer'));
    }
    public function backdate(Customer $customer)
    {
        return view('finance.customers.backdate', compact('customer'));
    }
    public function summary(Customer $customer)
    {
        return view('finance.customers.summary', compact('customer'));
    }
}
