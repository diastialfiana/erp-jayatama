<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'module',
        'reference_id',
        'model_type',
        'model_id',
        'action',
        'description',
        'amount',
        'user_id',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
