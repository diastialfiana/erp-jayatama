<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suppliers';

    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'contact_person',
        'phone',
        'fax',
        'mobile_phone',
        'category',
        'due_days',
        'credit_limit',
        'bank_name',
        'account_no',
        'account_name',
        'payable_account_id',
        'prepaid_account_id',
        'pph23_account_id',
        'tax_account_id',
        'cost_center_id',
        'account_dept_id',
    ];

    protected $casts = [
        'due_days' => 'integer',
        'credit_limit' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                $lastSupplier = self::orderBy('id', 'desc')->first();
                $lastNumber = $lastSupplier ? intval(str_replace('SUP-', '', $lastSupplier->code)) : 0;
                $model->code = 'SUP-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($model) {
            \App\Models\ActivityLog::create([
                'module' => 'supplier',
                'reference_id' => $model->id,
                'model_type' => self::class,
                'model_id' => $model->id,
                'action' => 'Create',
                'description' => 'Created supplier ' . $model->name,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'changes' => $model->toArray(),
            ]);
        });

        static::updated(function ($model) {
            \App\Models\ActivityLog::create([
                'module' => 'supplier',
                'reference_id' => $model->id,
                'model_type' => self::class,
                'model_id' => $model->id,
                'action' => 'Update',
                'description' => 'Updated supplier data for ' . $model->name,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'changes' => $model->getChanges(),
            ]);
        });

        static::deleted(function ($model) {
            \App\Models\ActivityLog::create([
                'module' => 'supplier',
                'reference_id' => $model->id,
                'model_type' => self::class,
                'model_id' => $model->id,
                'action' => 'Delete',
                'description' => 'Deleted supplier ' . ($model->name ?? 'Record'),
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'changes' => $model->toArray(),
            ]);
        });
    }

    public function transactions()
    {
        return $this->hasMany(SupplierTransaction::class);
    }

    public function purchases()
    {
        return $this->hasMany(SupplierTransaction::class)->where('type', 'purchase');
    }

    public function payments()
    {
        return $this->hasMany(SupplierTransaction::class)->where('type', 'payment');
    }

    public function getBalanceAttribute()
    {
        if ($this->relationLoaded('transactions')) {
            $purchases = $this->transactions->where('type', 'purchase')->sum('amount');
            $payments = $this->transactions->where('type', 'payment')->sum('amount');
            return $purchases - $payments;
        }
        return 0;
    }

    public function getDnPaymentAttribute()
    {
        if ($this->relationLoaded('transactions')) {
            return $this->transactions->where('type', 'down_payment')->sum('amount');
        }
        return 0;
    }

    public function getAvailableLimitAttribute()
    {
        return $this->credit_limit - $this->balance;
    }
}
