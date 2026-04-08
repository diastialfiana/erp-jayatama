<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CashReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'reference',
        'customer_id',
        'bank_id',
        'currency',
        'rate',
        'total',
        'note',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'rate' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(CashReceiptDetail::class, 'receipt_id');
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'receipt_id');
    }
}
