<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $fillable = [
        'invoice_number', 'date', 'due_date', 'customer_id', 
        'currency', 'rate', 'reference', 'subtotal', 'tax', 
        'pph23', 'total', 'approved', 'note', 'user_id', 
        'business_unit', 'paid', 'discount', 'balance', 
        'po_customer', 'quotation', 'tax_no', 'receipt_no', 'audit'
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'approved' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Finance\Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function details()
    {
        return $this->hasMany(SalesInvoiceDetail::class);
    }
}
