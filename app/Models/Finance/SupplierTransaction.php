<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierTransaction extends Model
{
    use HasFactory;

    protected $table = 'supplier_transactions';

    protected $fillable = [
        'supplier_id',
        'type',
        'amount',
        'reference_no',
        'original_date',
        'transaction_date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
