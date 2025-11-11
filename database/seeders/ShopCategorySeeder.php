<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = 
        [
            [
                'category' => 1001,
                'name' => 'ジェム'
            ],
            [
                'category' => 1002,
                'name' => '強化アイテム'
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('shop_categories')->insert($param);
        }
    }
}