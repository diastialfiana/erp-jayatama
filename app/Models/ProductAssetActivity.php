<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAssetActivity extends Model
{
    use HasFactory;
    
    protected $fillable = ['product_asset_code', 'date', 'ref_no', 'type', 'qty'];
}
