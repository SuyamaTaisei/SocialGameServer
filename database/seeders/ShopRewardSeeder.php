<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopRewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = 
        [
            [
                'product_id' => 10005,
                'item_id' => 1001,
                'amount' => 1,
            ],
            [
                'product_id' => 10006,
                'item_id' => 1002,
                'amount' => 1,
            ],
            [
                'product_id' => 10007,
                'item_id' => 1003,
                'amount' => 1,
            ],
            [
                'product_id' => 10008,
                'item_id' => 1004,
                'amount' => 1,
            ],
            [
                'product_id' => 10009,
                'item_id' => 1001,
                'amount' => 1,
            ],
            [
                'product_id' => 10010,
                'item_id' => 1002,
                'amount' => 1,
            ],
            [
                'product_id' => 10011,
                'item_id' => 1003,
                'amount' => 1,
            ],
            [
                'product_id' => 10012,
                'item_id' => 1004,
                'amount' => 1,
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('shop_rewards')->insert($param);
        }
    }
}
