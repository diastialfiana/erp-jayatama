<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAsset extends Model
{
    protected $fillable = [
        'code', 'product_name', 'cost_price', 'price_gr', 'price_ec',
        'min_stock', 'stock', 'on_order', 'discontinue',
        'jan_val', 'feb_val', 'mar_val', 'apr_val', 'may_val', 'jun_val',
        'jul_val', 'aug_val', 'sep_val', 'oct_val', 'nov_val', 'dec_val',
        'last_received', 'last_issued', 'last_sold', 'ytd_received', 'ytd_issued',
        'wh_stock', 'warehouse_name'
    ];
    use HasFactory;

    public function activities()
    {
        return $this->hasMany(ProductAssetActivity::class, 'product_asset_code', 'code');
    }

    public function warehouses()
    {
        return $this->hasMany(ProductAssetWarehouse::class, 'product_asset_code', 'code');
    }
}
