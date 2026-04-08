<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }
}
