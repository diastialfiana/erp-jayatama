<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashReceiptDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_id',
        'invoice_id',
        'amount',
        'discount',
        'prepaid',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'prepaid' => 'decimal:2',
    ];

    public function receipt()
    {
        return $this->belongsTo(CashReceipt::class, 'receipt_id');
    }

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_id');
    }
}
