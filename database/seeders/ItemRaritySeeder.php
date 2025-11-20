<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemRaritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = 
        [
            [
                'id' => 1000,
                'name' => '星1'
            ],
            [
                'id' => 2000,
                'name' => '星2'
            ],
                        [
                'id' => 3000,
                'name' => '星3'
            ],
            [
                'id' => 4000,
                'name' => '星4'
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('item_rarities')->insert($param);
        }
    }
}
