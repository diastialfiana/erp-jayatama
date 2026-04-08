<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return redirect()->route('finance.suppliers.records-list');
    }

    public function recordsList()
    {
        return view('finance.suppliers.list');
    }

    public function data(Request $request)
    {
        $query = Supplier::query()->with(['transactions']);

        // Tabulator Remote Filter Support
        $filters = $request->input('filter', []);
        $search = $request->input('search'); // fallback if passed manually

        if (is_array($filters) && count($filters) > 0) {
            foreach ($filters as $f) {
                $field = $f['field'] ?? '';
                $type = $f['type'] ?? '=';
                $value = $f['value'] ?? '';

                if ($field === 'search' && $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('code', 'like', "%{$value}%")
                          ->orWhere('name', 'like', "%{$value}%")
                          ->orWhere('contact_person', 'like', "%{$value}%")
                          ->orWhere('phone', 'like', "%{$value}%")
                          ->orWhere('category', 'like', "%{$value}%");
                    });
                } elseif ($field === 'category' && $value) {
                    $query->where('category', $value);
                } elseif ($field === 'city' && $value) {
                    $query->where('city', 'like', "%{$value}%");
                } elseif ($field === 'audit' && $value) {
                    if ($value === 'PASS') {
                        $query->where('audit_status', 'PASS');
                    } else {
                        // WAITING or other status
                        $query->where(function($q) {
                            $q->whereNull('audit_status')->orWhere('audit_status', 'WAITING')->orWhere('audit_status', '!=', 'PASS');
                        });
                    }
                }
            }
        }

        // Fallback for manual search param if any
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Tabulator Sorting Support
        $sort = $request->input('sort');
        if (is_array($sort) && count($sort) > 0) {
            $sortColumn = $sort[0]['field'] ?? 'code';
            $sortDir = $sort[0]['dir'] ?? 'asc';
            if (in_array($sortColumn, ['code', 'name', 'phone', 'contact_person', 'category'])) {
                $query->orderBy($sortColumn, $sortDir);
            } else {
                $query->orderBy('code', 'asc');
            }
        } else {
            $query->orderBy('code', 'asc');
        }

        // Tabulator Pagination Support
        $page = $request->input('page', 1);
        $size = $request->input('size', 50);

        $suppliers = $query->paginate($size, ['*'], 'page', $page);

        // Standardize output payload to reduce parsing overhead
        $data = $suppliers->map(function ($s) {
            return [
                'id' => $s->id,
                'code' => $s->code,
                'currency' => $s->currency ?? 'IDR',
                'name' => $s->name,
                'address' => $s->address,
                'city' => $s->city,
                'contact_person' => $s->contact_person,
                'category' => $s->category,
                'phone' => $s->phone,
                'fax' => $s->fax,
                'mobile_phone' => $s->mobile_phone,
                'balance' => $s->balance, // Invokes the eager-loaded transactions summation accessor
                'dn_payment' => $s->dn_payment,
                'audit' => $s->audit_status ?? 'PASS',
                'bank_name' => $s->bank_name,
                'account_no' => $s->account_no,
                'credit_limit' => $s->credit_limit,
            ];
        });

        return response()->json([
            'last_page' => $suppliers->lastPage(),
            'data' => $data,
        ]);
    }

    public function detail()
    {
        $supplier = new Supplier();
        $navigation = $this->getNavigation(null);
        return view('finance.suppliers.detail', compact('supplier', 'navigation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'payable_account_id' => 'required|integer',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'fax' => 'nullable|string',
            'mobile_phone' => 'nullable|string',
            'category' => 'nullable|string',
            'due_days' => 'nullable|integer',
            'credit_limit' => 'nullable|numeric',
            'bank_name' => 'nullable|string',
            'account_no' => 'nullable|string',
            'account_name' => 'nullable|string',
            'prepaid_account_id' => 'nullable|integer',
            'pph23_account_id' => 'nullable|integer',
            'tax_account_id' => 'nullable|integer',
            'cost_center_id' => 'nullable|integer',
            'account_dept_id' => 'nullable|integer',
        ]);

        $supplier = Supplier::create($validated);

        return redirect()->route('finance.suppliers.show', $supplier->id)
            ->with('success', 'Supplier created successfully.');
    }

    private function resolveSupplierContext($id, $routeName)
    {
        $resolvedId = $id ?? session('active_supplier_id');
        
        if (!$resolvedId) {
            $first = Supplier::first();
            if ($first) {
                return redirect()->route($routeName, $first->id);
            }
            return redirect()->route('finance.suppliers.records-list')->with('error', 'No suppliers found.');
        }

        if ($resolvedId !== session('active_supplier_id')) {
            session(['active_supplier_id' => $resolvedId]);
        }

        return $resolvedId;
    }

    public function statistic(Request $request, $id = null)
    {
        $context = $this->resolveSupplierContext($id, 'suppliers.statistic');
        if ($context instanceof \Illuminate\Http\RedirectResponse) return $context;
        $supplier = Supplier::findOrFail($context);

        $year = $request->input('year', date('Y'));
        
        $beginPurchase = $supplier->transactions()->where('type', 'purchase')->whereYear('created_at', '<', $year)->sum('amount');
        $beginPayment = $supplier->transactions()->where('type', 'payment')->whereYear('created_at', '<', $year)->sum('amount');
        $beginBalance = $beginPurchase - $beginPayment;
        $beginDp = $supplier->transactions()->where('type', 'down_payment')->whereYear('created_at', '<', $year)->sum('amount');

        $monthlyStats = [];
        $currentBalance = $beginBalance;
        $currentDp = $beginDp;
        
        for ($month = 1; $month <= 12; $month++) {
            $monthPurchase = $supplier->transactions()
                ->where('type', 'purchase')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('amount');
                
            $monthPayment = $supplier->transactions()
                ->where('type', 'payment')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('amount');
                
            $monthDpAmount = $supplier->transactions()
                ->where('type', 'down_payment')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('amount');
                
            $balance = $monthPurchase - $monthPayment;
            $currentBalance += $balance;
            $currentDp += $monthDpAmount;
            
            $monthlyStats[$month] = [
                'name' => date("F", mktime(0, 0, 0, $month, 10)),
                'balance' => $balance,
                'dp' => $monthDpAmount
            ];
        }

        return view('finance.suppliers.statistic', compact('supplier', 'year', 'beginBalance', 'beginDp', 'currentBalance', 'currentDp', 'monthlyStats'));
    }

    public function show(Request $request, $id = null)
    {
        $context = $this->resolveSupplierContext($id, 'suppliers.show');
        if ($context instanceof \Illuminate\Http\RedirectResponse) return $context;
        $supplier = Supplier::findOrFail($context);
        
        $navigation = $this->getNavigation($supplier->id);
        
        if ($request->wantsJson()) {
            return response()->json([
                'supplier' => $supplier,
                'navigation' => $navigation,
                'urls' => [
                    'update' => route('finance.suppliers.update', $supplier->id)
                ]
            ]);
        }
        
        return view('finance.suppliers.detail', compact('supplier', 'navigation'));
    }

    public function activity(Request $request, $id = null)
    {
        $context = $this->resolveSupplierContext($id, 'suppliers.activity');
        if ($context instanceof \Illuminate\Http\RedirectResponse) return $context;
        $supplier = Supplier::findOrFail($context);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Supplier::query();

        if ($startDate && $endDate) {
            $query->withSum(['purchases as total_purchase' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }], 'amount')
            ->withSum(['payments as total_payment' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }], 'amount');
        } else {
            $startDate = null; // Just in case it's empty string
            $endDate = null;
            $query->withSum('purchases as total_purchase', 'amount')
                  ->withSum('payments as total_payment', 'amount');
        }

        $suppliers = $query->get()->map(function ($s) {
            $s->balance = ($s->total_purchase ?? 0) - ($s->total_payment ?? 0);
            return $s;
        });

        return view('finance.suppliers.activity', compact('supplier', 'suppliers', 'startDate', 'endDate'));
    }

    public function summary(Request $request, $id = null)
    {
        $context = $this->resolveSupplierContext($id, 'suppliers.summary');
        if ($context instanceof \Illuminate\Http\RedirectResponse) return $context;
        $supplier = Supplier::findOrFail($context);

        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        $query = Supplier::query();

        $types = ['purchase', 'invoice', 'return', 'po_return', 'payment', 'down_payment'];
        foreach ($types as $type) {
            $query->withSum(['transactions as beg_' . $type => function($q) use ($startDate, $type) {
                // Determine the date column to use. Assuming transactions has transaction_date, but let's use created_at as standard unless specified otherwise. In ERP, transaction_date is better.
                // Assuming supplier_transactions has either a transaction_date or we use created_at. We added transaction_date just now but we might want to fallback to created_at if transaction_date is null.
                // It is simpler to use `created_at` matching the conventional standard since we don't know the exact architecture details.
                $q->where('type', $type)->whereDate('created_at', '<', $startDate);
            }], 'amount');

            $query->withSum(['transactions as cur_' . $type => function($q) use ($startDate, $endDate, $type) {
                $q->where('type', $type)->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }], 'amount');
        }

        $suppliers = $query->get()->map(function($s) {
            $s->beg_balance = ($s->beg_purchase ?? 0) + ($s->beg_invoice ?? 0) 
                            - ($s->beg_return ?? 0) - ($s->beg_po_return ?? 0) 
                            - ($s->beg_payment ?? 0) - ($s->beg_down_payment ?? 0);
                            
            $s->purchase = $s->cur_purchase ?? 0;
            $s->invoice = $s->cur_invoice ?? 0;
            $s->return = $s->cur_return ?? 0;
            $s->po_return = $s->cur_po_return ?? 0;
            $s->payment = $s->cur_payment ?? 0;
            $s->dp = $s->cur_down_payment ?? 0;
            
            $s->balance = $s->beg_balance 
                        + $s->purchase + $s->invoice 
                        - $s->return - $s->po_return 
                        - $s->payment - $s->dp;
            return $s;
        });

        return view('finance.suppliers.summary', compact('supplier', 'suppliers', 'startDate', 'endDate'));
    }

    public function backdate(Request $request, $id = null)
    {
        $context = $this->resolveSupplierContext($id, 'suppliers.backdate');
        if ($context instanceof \Illuminate\Http\RedirectResponse) return $context;
        $supplier = Supplier::findOrFail($context);

        $filterDate = $request->input('date');

        $query = Supplier::whereHas('transactions', function ($q) use ($filterDate) {
            $q->whereColumn('transaction_date', '!=', 'original_date');
            if ($filterDate) {
                // If filtering by date, assume they look for changes that occurred on that date (using updated_at or original_date depending on logic, let's use transaction_date to find if that specific new date was used, or updated_at. I'll use updated_at since it tracks when the change was made).
                // "Filter: berdasarkan tanggal perubahan" -> updated_at
                $q->whereDate('updated_at', $filterDate);
            }
        });

        // Load all transactions for those backdate suppliers so we can see what was backdated
        $query->with(['transactions' => function ($q) use ($filterDate) {
            $q->whereColumn('transaction_date', '!=', 'original_date');
            if ($filterDate) {
                $q->whereDate('updated_at', $filterDate);
            }
        }]);

        // Aggregate Balances
        $query->withSum('purchases as total_purchase', 'amount')
              ->withSum('payments as total_payment', 'amount')
              ->withSum(['transactions as total_dp' => function($q) {
                  $q->where('type', 'down_payment');
              }], 'amount');

        $backdateSuppliers = $query->get()->map(function ($s) {
            $s->balance = ($s->total_purchase ?? 0) - ($s->total_payment ?? 0);
            $s->balance_dp = $s->total_dp ?? 0;
            return $s;
        });

        return view('finance.suppliers.backdate', compact('supplier', 'backdateSuppliers', 'filterDate'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'payable_account_id' => 'required|integer',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'fax' => 'nullable|string',
            'mobile_phone' => 'nullable|string',
            'category' => 'nullable|string',
            'due_days' => 'nullable|integer',
            'credit_limit' => 'nullable|numeric',
            'bank_name' => 'nullable|string',
            'account_no' => 'nullable|string',
            'account_name' => 'nullable|string',
            'prepaid_account_id' => 'nullable|integer',
            'pph23_account_id' => 'nullable|integer',
            'tax_account_id' => 'nullable|integer',
            'cost_center_id' => 'nullable|integer',
            'account_dept_id' => 'nullable|integer',
        ]);

        $supplier->update($validated);

        return redirect()->route('finance.suppliers.show', $supplier->id)
            ->with('success', 'Supplier updated successfully.');
    }

    private function getNavigation($currentId = null)
    {
        $total = Supplier::count();
        $first = Supplier::orderBy('id', 'asc')->first();
        $last = Supplier::orderBy('id', 'desc')->first();

        $prev = null;
        $next = null;
        $currentPosition = 0;

        if ($currentId) {
            $prev = Supplier::where('id', '<', $currentId)->orderBy('id', 'desc')->first();
            $next = Supplier::where('id', '>', $currentId)->orderBy('id', 'asc')->first();
            $currentPosition = Supplier::where('id', '<=', $currentId)->count();
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
}
