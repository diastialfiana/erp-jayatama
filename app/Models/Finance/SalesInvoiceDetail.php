<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class SalesInvoiceDetail extends Model
{
    protected $fillable = [
        'sales_invoice_id', 'product_id', 'qty', 'price', 
        'tax_percent', 'pph23_percent', 'amount', 'description'
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class);
    }
}
