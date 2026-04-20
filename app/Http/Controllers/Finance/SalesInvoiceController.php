<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Finance\SalesInvoice;
use App\Models\Finance\SalesInvoiceDetail;
use App\Models\Finance\Customer;
use App\Models\Inventory\Product;
use Illuminate\Support\Facades\DB;

class SalesInvoiceController extends Controller
{
    public function index()
    {
        return view('finance.sales-invoice.index');
    }

    public function recordsList()
    {
        $invoices = SalesInvoice::with(['customer', 'user'])->latest()->get();
        return view('finance.sales-invoice.records-list', compact('invoices'));
    }

    public function customGroup(Request $request)
    {
        $groupBy   = $request->get('group_by', 'product');
        $startDate = $request->get('start_date', \Carbon\Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate   = $request->get('end_date', \Carbon\Carbon::now()->format('Y-m-d'));

        $query = SalesInvoiceDetail::with(['salesInvoice.customer', 'product'])
            ->whereHas('salesInvoice', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });

        $details = $query->get();

        if ($groupBy === 'product') {
            $grouped = $details->groupBy(function ($item) {
                return $item->product ? $item->product->code . ' - ' . $item->product->name : 'No Product';
            });
        } elseif ($groupBy === 'customer') {
            $grouped = $details->groupBy(function ($item) {
                return $item->salesInvoice && $item->salesInvoice->customer
                    ? $item->salesInvoice->customer->name
                    : 'No Customer';
            });
        } elseif ($groupBy === 'business_unit') {
            $grouped = $details->groupBy(function ($item) {
                return $item->salesInvoice && $item->salesInvoice->business_unit
                    ? $item->salesInvoice->business_unit
                    : 'No Business Unit';
            });
        } else {
            $grouped = collect([]);
        }

        return view('finance.sales-invoice.custom-group', compact('grouped', 'groupBy', 'startDate', 'endDate'));
    }

    public function detailList(Request $request)
    {
        $startDate = $request->get('start_date', \Carbon\Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate   = $request->get('end_date', \Carbon\Carbon::now()->format('Y-m-d'));

        $invoices = SalesInvoice::with(['customer', 'user'])
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        return view('finance.sales-invoice.detail-list', compact('invoices', 'startDate', 'endDate'));
    }

    /**
     * Show form for creating a new invoice (redirect to index with create mode).
     */
    public function create()
    {
        // ✅ FIX: Arahkan ke halaman yang benar (index berisi form)
        return view('finance.sales-invoice.index');
    }

    public function apiCustomers()
    {
        return response()->json(Customer::select('id', 'name', 'counter_name', 'code', 'currency')->get());
    }

    public function apiProducts()
    {
        return response()->json(Product::select('id', 'code', 'name', 'default_price')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'        => 'required|date',
            'due_date'    => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'currency'    => 'required|string',
            'rate'        => 'required|numeric',
            'details'     => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $details = json_decode($request->details, true);
            if (empty($details)) {
                throw new \Exception("Invoice must have at least one detail row.");
            }

            // Generate Invoice Number INV/YYYY/MM/XXXX
            $date  = \Carbon\Carbon::parse($request->date);
            $year  = $date->format('Y');
            $month = $date->format('m');

            $latest = SalesInvoice::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->orderBy('id', 'desc')
                ->first();

            $nextSequence = 1;
            if ($latest) {
                $parts = explode('/', $latest->invoice_number);
                if (isset($parts[3])) {
                    $nextSequence = intval($parts[3]) + 1;
                }
            }
            $invoiceNumber = sprintf("INV/%s/%s/%04d", $year, $month, $nextSequence);

            $subtotal   = 0;
            $totalTax   = 0;
            $totalPph23 = 0;

            foreach ($details as $row) {
                $subtotal   += $row['amount'];
                $totalTax   += ($row['amount'] * ($row['tax_percent'] / 100));
                $totalPph23 += ($row['amount'] * ($row['pph23_percent'] / 100));
            }

            $total = $subtotal + $totalTax - $totalPph23;

            $invoice = SalesInvoice::create([
                'invoice_number' => $invoiceNumber,
                'date'           => $request->date,
                'due_date'       => $request->due_date,
                'customer_id'    => $request->customer_id,
                'currency'       => $request->currency,
                'rate'           => $request->rate,
                'reference'      => $request->reference ?? $invoiceNumber,
                'subtotal'       => $subtotal,
                'tax'            => $totalTax,
                'pph23'          => $totalPph23,
                'total'          => $total,
                'balance'        => $total, // initial balance = full amount
                'paid'           => 0,
                'approved'       => $request->has('approved'),
                'note'           => $request->note,
                'user_id'        => auth()->id(),
            ]);

            foreach ($details as $row) {
                SalesInvoiceDetail::create([
                    'sales_invoice_id' => $invoice->id,
                    'product_id'       => $row['product_id'] ?? null,
                    'qty'              => $row['qty'],
                    'price'            => $row['price'],
                    'tax_percent'      => $row['tax_percent'] ?? 0,
                    'pph23_percent'    => $row['pph23_percent'] ?? 0,
                    'amount'           => $row['amount'],
                    'description'      => $row['description'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', "Sales Invoice {$invoiceNumber} created successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Failed creating invoice: " . $e->getMessage());
        }
    }

    /**
     * ✅ FIX: show() — tampilkan detail satu invoice
     */
    public function show(SalesInvoice $salesInvoice)
    {
        $salesInvoice->load(['customer', 'user', 'details.product']);
        return view('finance.sales-invoice.index', compact('salesInvoice'));
    }

    /**
     * ✅ FIX: edit() — form edit invoice
     */
    public function edit(SalesInvoice $salesInvoice)
    {
        $salesInvoice->load(['customer', 'details.product']);
        return view('finance.sales-invoice.index', compact('salesInvoice'));
    }

    /**
     * ✅ FIX: update() — simpan perubahan invoice
     */
    public function update(Request $request, SalesInvoice $salesInvoice)
    {
        $request->validate([
            'date'        => 'required|date',
            'due_date'    => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'currency'    => 'required|string',
            'rate'        => 'required|numeric',
            'details'     => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $details = json_decode($request->details, true);
            if (empty($details)) {
                throw new \Exception("Invoice must have at least one detail row.");
            }

            $subtotal   = 0;
            $totalTax   = 0;
            $totalPph23 = 0;

            foreach ($details as $row) {
                $subtotal   += $row['amount'];
                $totalTax   += ($row['amount'] * ($row['tax_percent'] / 100));
                $totalPph23 += ($row['amount'] * ($row['pph23_percent'] / 100));
            }

            $total      = $subtotal + $totalTax - $totalPph23;
            $totalPaid  = (float) ($salesInvoice->paid ?? 0);
            $newBalance = max(0, $total - $totalPaid);

            $salesInvoice->update([
                'date'        => $request->date,
                'due_date'    => $request->due_date,
                'customer_id' => $request->customer_id,
                'currency'    => $request->currency,
                'rate'        => $request->rate,
                'reference'   => $request->reference ?? $salesInvoice->reference,
                'subtotal'    => $subtotal,
                'tax'         => $totalTax,
                'pph23'       => $totalPph23,
                'total'       => $total,
                'balance'     => $newBalance,
                'approved'    => $request->has('approved'),
                'note'        => $request->note,
            ]);

            // Delete old details and recreate
            $salesInvoice->details()->delete();

            foreach ($details as $row) {
                SalesInvoiceDetail::create([
                    'sales_invoice_id' => $salesInvoice->id,
                    'product_id'       => $row['product_id'] ?? null,
                    'qty'              => $row['qty'],
                    'price'            => $row['price'],
                    'tax_percent'      => $row['tax_percent'] ?? 0,
                    'pph23_percent'    => $row['pph23_percent'] ?? 0,
                    'amount'           => $row['amount'],
                    'description'      => $row['description'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', "Invoice {$salesInvoice->invoice_number} updated successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Failed updating invoice: " . $e->getMessage());
        }
    }

    /**
     * ✅ FIX: destroy() — hapus invoice (soft delete / hard delete)
     */
    public function destroy(SalesInvoice $salesInvoice)
    {
        // Pastikan invoice belum pernah dibayar
        if ((float) ($salesInvoice->paid ?? 0) > 0) {
            return back()->with('error',
                "Cannot delete invoice {$salesInvoice->invoice_number}. It has been partially or fully paid.");
        }

        try {
            DB::beginTransaction();
            $salesInvoice->details()->delete();
            $salesInvoice->delete();
            DB::commit();

            return redirect()->route('finance.sales-invoice.records-list')
                ->with('success', "Invoice {$salesInvoice->invoice_number} deleted.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Failed deleting invoice: " . $e->getMessage());
        }
    }
}
