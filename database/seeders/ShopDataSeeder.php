<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = 
        [
            [
                'product_id' => 10001,
                'shop_category' => 1001,
                'name' => '500ジェム',
                'price' => 500
            ],
            [
                'product_id' => 10002,
                'shop_category' => 1001,
                'name' => '1000ジェム + 無償300ジェム',
                'price' => 1000
            ],
            [
                'product_id' => 10003,
                'shop_category' => 1001,
                'name' => '2000ジェム + 無償600ジェム',
                'price' => 2000
            ],
            [
                'product_id' => 10004,
                'shop_category' => 1001,
                'name' => '4000ジェム + 無償1500ジェム',
                'price' => 4000
            ],
            [
                'product_id' => 10005,
                'shop_category' => 1002,
                'name' => 'レベル1獲得チケット',
                'price' => 200,
            ],
            [
                'product_id' => 10006,
                'shop_category' => 1002,
                'name' => 'レベル3獲得チケット',
                'price' => 500,
            ],
                        [
                'product_id' => 10007,
                'shop_category' => 1002,
                'name' => 'レベル6獲得チケット',
                'price' => 2500,
            ],
            [
                'product_id' => 10008,
                'shop_category' => 1002,
                'name' => 'レベル10獲得チケット',
                'price' => 5000,
            ],
                        [
                'product_id' => 10009,
                'shop_category' => 1002,
                'name' => 'レベル1獲得チケット',
                'price' => 20,
            ],
            [
                'product_id' => 10010,
                'shop_category' => 1002,
                'name' => 'レベル3獲得チケット',
                'price' => 50,
            ],
            [
                'product_id' => 10011,
                'shop_category' => 1002,
                'name' => 'レベル6獲得チケット',
                'price' => 250,
            ],
            [
                'product_id' => 10012,
                'shop_category' => 1002,
                'name' => 'レベル10獲得チケット',
                'price' => 500,
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('shop_data')->insert($param);
        }
    }
}
