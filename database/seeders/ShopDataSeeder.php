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
                'type' => "Gem",
                'name' => '500ジェム',
                'paid_currency' => 500,
                'free_currency' => 0,
                'coin_currency' => 0,
                'price' => 500
            ],
            [
                'product_id' => 10002,
                'shop_category' => 1001,
                'type' => "Gem",
                'name' => '1000ジェム + 無償300ジェム',
                'paid_currency' => 1000,
                'free_currency' => 300,
                'coin_currency' => 0,
                'price' => 1000
            ],
            [
                'product_id' => 10003,
                'shop_category' => 1001,
                'type' => "Gem",
                'name' => '2000ジェム + 無償600ジェム',
                'paid_currency' => 2000,
                'free_currency' => 600,
                'coin_currency' => 0,
                'price' => 2000
            ],
            [
                'product_id' => 10004,
                'shop_category' => 1001,
                'type' => "Gem",
                'name' => '4000ジェム + 無償1500ジェム',
                'paid_currency' => 4000,
                'free_currency' => 1500,
                'coin_currency' => 0,
                'price' => 4000
            ],
            [
                'product_id' => 10005,
                'shop_category' => 1002,
                'type' => "Coin",
                'name' => 'レベル1獲得チケット',
                'paid_currency' => 0,
                'free_currency' => 0,
                'coin_currency' => 200,
                'price' => 200,
            ],
            [
                'product_id' => 10006,
                'shop_category' => 1002,
                'type' => "Coin",
                'name' => 'レベル3獲得チケット',
                'paid_currency' => 0,
                'free_currency' => 0,
                'coin_currency' => 500,
                'price' => 500,
            ],
            [
                'product_id' => 10007,
                'shop_category' => 1002,
                'type' => "Coin",
                'name' => 'レベル6獲得チケット',
                'paid_currency' => 0,
                'free_currency' => 0,
                'coin_currency' => 2500,
                'price' => 2500,
            ],
            [
                'product_id' => 10008,
                'shop_category' => 1002,
                'type' => "Coin",
                'name' => 'レベル10獲得チケット',
                'paid_currency' => 0,
                'free_currency' => 0,
                'coin_currency' => 5000,
                'price' => 5000,
            ],
            [
                'product_id' => 10009,
                'shop_category' => 1002,
                'type' => "Gem",
                'name' => 'レベル1獲得チケット',
                'paid_currency' => 20,
                'free_currency' => 20,
                'coin_currency' => 0,
                'price' => 20,
            ],
            [
                'product_id' => 10010,
                'shop_category' => 1002,
                'type' => "Gem",
                'name' => 'レベル3獲得チケット',
                'paid_currency' => 50,
                'free_currency' => 50,
                'coin_currency' => 0,
                'price' => 50,
            ],
            [
                'product_id' => 10011,
                'shop_category' => 1002,
                'type' => "Gem",
                'name' => 'レベル6獲得チケット',
                'paid_currency' => 250,
                'free_currency' => 250,
                'coin_currency' => 0,
                'price' => 250,
            ],
            [
                'product_id' => 10012,
                'shop_category' => 1002,
                'type' => "Gem",
                'name' => 'レベル10獲得チケット',
                'paid_currency' => 500,
                'free_currency' => 500,
                'coin_currency' => 0,
                'price' => 500,
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('shop_data')->insert($param);
        }
    }
}
