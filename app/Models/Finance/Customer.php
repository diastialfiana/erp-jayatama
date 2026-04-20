<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'code',
        'counter_name',
        'currency',
        'address',
        'phone',
        'fax',
        'mobile_phone',
        'region',
        'initial_name',
        'invoice_layout',
        'cost_center_id',
        'account_dept_id',
        'default_bank_account_id',
        'is_corporate_group',
        'group_id',
        'receivable_account_id',
        'prepaid_account_id',
        'pph23_account_id',
        'tax_account_id',
        'sales_account_id',
        'sales_return_account_id',
        'balance',
        'down_payment',
    ];

    protected $casts = [
        'is_corporate_group' => 'boolean',
        'balance'            => 'decimal:2',
        'down_payment'       => 'decimal:2',
    ];

    // ✅ FIX: Tambahkan relasi ke transaksi

    /**
     * Sales invoices for this customer.
     */
    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }

    /**
     * Cash receipts (payments) for this customer.
     */
    public function cashReceipts()
    {
        return $this->hasMany(CashReceipt::class);
    }

    /**
     * Computed: total outstanding balance (invoice - paid).
     */
    public function getRealBalanceAttribute(): float
    {
        $totalInvoice = (float) $this->salesInvoices()->sum('total');
        $totalPaid    = (float) $this->salesInvoices()->sum('paid');
        return $totalInvoice - $totalPaid;
    }

    /**
     * Computed: total invoice amount.
     */
    public function getTotalInvoiceAttribute(): float
    {
        return (float) $this->salesInvoices()->sum('total');
    }

    /**
     * Computed: total received.
     */
    public function getTotalReceivedAttribute(): float
    {
        return (float) $this->cashReceipts()->sum('total');
    }
}
