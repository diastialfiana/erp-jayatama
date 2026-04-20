<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EbankingRequest extends Model
{
    use HasFactory, SoftDeletes; // ✅ FIX: tambahkan SoftDeletes

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
