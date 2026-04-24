<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAsset;

class ProductAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductAsset::query()->delete();
        \App\Models\ProductAssetActivity::query()->delete();
        \App\Models\ProductAssetWarehouse::query()->delete();

        $products = [
            [
                'code' => '006671113', 'product_name' => '3M CREAM CLEANSER MILL', 'cost_price' => 123.750, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 16, 'on_order' => 0, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 16, 'warehouse_name' => 'HEAD OFFICE - CHEMICAL'
            ],
            [
                'code' => '0060281', 'product_name' => '5 EXTRA POWDER', 'cost_price' => 300.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 5, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'HEAD OFFICE - CHEMICAL'
            ],
            [
                'code' => '008950196', 'product_name' => 'A-PAD MERAH', 'cost_price' => 828.754, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 38, 'on_order' => 9, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 38, 'warehouse_name' => 'HEAD OFFICE - CHEMICAL'
            ],
            [
                'code' => '009950028', 'product_name' => 'A-BOWL CLEANER', 'cost_price' => 592.176, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 290, 'on_order' => 17, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 290, 'warehouse_name' => 'HEAD OFFICE - CHEMICAL'
            ],
            [
                'code' => '009950518', 'product_name' => 'A-BRUSH TANGKAI', 'cost_price' => 25.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 3, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'HEAD OFFICE - CHEMICAL'
            ],
            [
                'code' => '0210269804', 'product_name' => 'AC COIL CLEANER STANDART', 'cost_price' => 24.500, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 120, 'on_order' => 0, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 120, 'warehouse_name' => 'HEAD OFFICE - CHEMICAL'
            ],
            [
                'code' => '009140269150', 'product_name' => 'AC DAIKIN 1 PK FTC25NV14', 'cost_price' => 3950.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 1, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'BRANCH DOK'
            ],
            [
                'code' => '008140269205', 'product_name' => 'AC DAIKIN 1 PK THAILAND FTC...', 'cost_price' => 3600.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 2, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'BRANCH DOK'
            ],
            [
                'code' => '018140275154', 'product_name' => 'AC DAIKIN FREON R 410', 'cost_price' => 35.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 200, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'BRANCH DOK'
            ],
            [
                'code' => '018140865645', 'product_name' => 'AC DAIKIN FTKQ50 2PK', 'cost_price' => 8350.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 1, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'BRANCH DOK'
            ],
            [
                'code' => '018141182941', 'product_name' => 'AC DAIKIN INDOOR WALL MOU...', 'cost_price' => 13302.500, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 1, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'BRANCH DOK'
            ],
            [
                'code' => '018140463', 'product_name' => 'AC DAIKIN PERBAIKAN PIPA KA...', 'cost_price' => 1700.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 1, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'BRANCH DOK'
            ],
            [
                'code' => '001140358', 'product_name' => 'ACCESSORIES', 'cost_price' => 1.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 1, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'HEAD OFFICE - CHEMICAL'
            ],
            [
                'code' => '0010328', 'product_name' => 'ACCESSORIES KUAS 3"', 'cost_price' => 15.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 0, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'HEAD OFFICE - CHEMICAL'
            ],
            [
                'code' => '001000857810', 'product_name' => 'ACCESSORIES Z POMPA GRUN...', 'cost_price' => 5700.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 0, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'HEAD OFFICE - CHEMICAL'
            ],
            [
                'code' => '008670835', 'product_name' => 'ACRYLIC URINOIR', 'cost_price' => 45.000, 
                'price_gr' => null, 'price_ec' => null, 'min_stock' => 1, 'stock' => 0, 'on_order' => 15, 'discontinue' => false,
                'jan_val' => 0, 'feb_val' => 0, 'mar_val' => 0, 'apr_val' => 0, 'may_val' => 0, 'jun_val' => 0,
                'jul_val' => 0, 'aug_val' => 0, 'sep_val' => 0, 'oct_val' => 0, 'nov_val' => 0, 'dec_val' => 0,
                'wh_stock' => 0, 'warehouse_name' => 'HEAD OFFICE - CHEMICAL'
            ]
        ];

        foreach ($products as $product) {
            $stock = $product['stock'];
            
            // Randomly populate months if stock > 0
            if ($stock > 0) {
                $product['jan_val'] = max(0, $stock - rand(5, 10));
                $product['feb_val'] = max(0, $stock - rand(0, 5));
                $product['mar_val'] = $stock;
                
                $product['last_received'] = now()->subDays(rand(1, 30));
                $product['last_issued'] = now()->subDays(rand(2, 40));
                $product['last_sold'] = now()->subDays(rand(5, 60));
                $product['ytd_received'] = $stock + rand(10, 50);
                $product['ytd_issued'] = rand(0, 50);
            }

            ProductAsset::create($product);

            // Populate warehouse
            \App\Models\ProductAssetWarehouse::create([
                'product_asset_code' => $product['code'],
                'warehouse_name' => $product['warehouse_name'],
                'stock' => $stock,
                'on_transit' => 0,
            ]);

            // Add second warehouse if stock > 10
            if ($stock > 10) {
                $split = floor($stock / 3);
                \App\Models\ProductAssetWarehouse::where('product_asset_code', $product['code'])->update(['stock' => $stock - $split]);
                \App\Models\ProductAssetWarehouse::create([
                    'product_asset_code' => $product['code'],
                    'warehouse_name' => 'BRANCH - RETAIL',
                    'stock' => $split,
                    'on_transit' => 0,
                ]);
            }

            // Populate activity if stock > 0
            if ($stock > 0) {
                \App\Models\ProductAssetActivity::create([
                    'product_asset_code' => $product['code'],
                    'date' => now()->subDays(rand(1, 15))->format('Y-m-d'),
                    'ref_no' => 'RCV-' . substr($product['code'], 0, 5) . rand(10, 99),
                    'type' => 'RECEIVE',
                    'qty' => rand(5, 20)
                ]);

                \App\Models\ProductAssetActivity::create([
                    'product_asset_code' => $product['code'],
                    'date' => now()->subDays(rand(15, 30))->format('Y-m-d'),
                    'ref_no' => 'ISS-' . substr($product['code'], 0, 5) . rand(10, 99),
                    'type' => 'ISSUE',
                    'qty' => rand(1, 10)
                ]);
            }
        }
    }
}
