<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = 
        [
            [
                'id' => 1001,
                'rarity_id' => 1000,
                'item_category' => 1001,
                'name' => 'レベル1獲得チケット',
                'description' => 'キャラクターレベルが1上昇する',
                'value' => 1
            ],
            [
                'id' => 1002,
                'rarity_id' => 2000,
                'item_category' => 1001,
                'name' => 'レベル3獲得チケット',
                'description' => 'キャラクターレベルが3上昇する',
                'value' => 3
            ],
            [
                'id' => 1003,
                'rarity_id' => 3000,
                'item_category' => 1001,
                'name' => 'レベル6獲得チケット',
                'description' => 'キャラクターレベルが6上昇する',
                'value' => 6
            ],
            [
                'id' => 1004,
                'rarity_id' => 4000,
                'item_category' => 1001,
                'name' => 'レベル10獲得チケット',
                'description' => 'キャラクターレベルが10上昇する',
                'value' => 10
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('item_data')->insert($param);
        }
    }
}
