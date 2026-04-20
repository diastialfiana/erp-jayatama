<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_account_id',
        'type',
        'reference_id',
        'reference_type',
        'account_id',
        'department_id',
        'cost_center_id',
        'date',
        'reference',
        'description',
        'amount',
        'debit',
        'credit',
        'currency',
        'rate',
        'created_by',
    ];

    protected $casts = [
        'date'            => 'date',
        'amount'          => 'decimal:2',
        'debit'           => 'decimal:2',
        'credit'          => 'decimal:2',
        'rate'            => 'decimal:2',
    ];

    /**
     * The bank account this transaction belongs to.
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * The user who created this transaction.
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Scope: filter by transaction type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: balance as of a given date.
     */
    public function scopeAsOfDate($query, string $date)
    {
        return $query->whereDate('date', '<=', $date);
    }

    /**
     * Helper: record a new bank transaction. No static balance updates.
     */
    public static function record(array $data): self
    {
        return self::create([
            'bank_account_id' => $data['bank_account_id'],
            'type'            => $data['type'],
            'reference_id'    => $data['reference_id'] ?? null,
            'reference_type'  => $data['reference_type'] ?? null,
            'account_id'      => $data['account_id'] ?? null,
            'department_id'   => $data['department_id'] ?? null,
            'cost_center_id'  => $data['cost_center_id'] ?? null,
            'date'            => $data['date'],
            'reference'       => $data['reference'] ?? '',
            'description'     => $data['description'] ?? '',
            'amount'          => $data['amount'] ?? 0,
            'debit'           => $data['debit'] ?? 0,
            'credit'          => $data['credit'] ?? 0,
            'currency'        => $data['currency'] ?? 'IDR',
            'rate'            => $data['rate'] ?? 1,
            'created_by'      => $data['created_by'] ?? auth()->id(),
        ]);
    }
}
