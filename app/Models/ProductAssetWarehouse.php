<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAssetWarehouse extends Model
{
    use HasFactory;
    
    protected $fillable = ['product_asset_code', 'warehouse_name', 'stock', 'on_transit'];
}
