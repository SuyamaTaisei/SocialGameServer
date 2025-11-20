<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharacterDataSeeder extends Seeder
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
                'character_category' => 1001,
                'name' => 'ロゼ'
            ],
            [
                'id' => 1002,
                'rarity_id' => 1000,
                'character_category' => 1001,
                'name' => 'ベラ'
            ],
            [
                'id' => 1003,
                'rarity_id' => 1000,
                'character_category' => 1001,
                'name' => 'ディラン'
            ],
            [
                'id' => 1004,
                'rarity_id' => 1000,
                'character_category' => 1002,
                'name' => 'グラン'
            ],
            [
                'id' => 1005,
                'rarity_id' => 1000,
                'character_category' => 1002,
                'name' => 'フレア'
            ],
            [
                'id' => 1006,
                'rarity_id' => 1000,
                'character_category' => 1002,
                'name' => 'ルミナ'
            ],
            [
                'id' => 1007,
                'rarity_id' => 1000,
                'character_category' => 1002,
                'name' => 'ノクティール'
            ],
            [
                'id' => 1008,
                'rarity_id' => 1000,
                'character_category' => 1003,
                'name' => 'カイロス'
            ],
            [
                'id' => 1009,
                'rarity_id' => 1000,
                'character_category' => 1003,
                'name' => 'オルガ'
            ],
            [
                'id' => 1010,
                'rarity_id' => 1000,
                'character_category' => 1003,
                'name' => 'サイファード'
            ],
            [
                'id' => 2001,
                'rarity_id' => 2000,
                'character_category' => 1004,
                'name' => 'フィオラ'
            ],
            [
                'id' => 2002,
                'rarity_id' => 2000,
                'character_category' => 1004,
                'name' => 'レオルド'
            ],
            [
                'id' => 2003,
                'rarity_id' => 2000,
                'character_category' => 1004,
                'name' => 'トリスティン'
            ],
            [
                'id' => 2004,
                'rarity_id' => 2000,
                'character_category' => 1004,
                'name' => 'シュト'
            ],
            [
                'id' => 2005,
                'rarity_id' => 2000,
                'character_category' => 1005,
                'name' => 'ミリス'
            ],
            [
                'id' => 3001,
                'rarity_id' => 3000,
                'character_category' => 1005,
                'name' => 'ヴェイル'
            ],
            [
                'id' => 3002,
                'rarity_id' => 3000,
                'character_category' => 1005,
                'name' => 'ヴァレンティア'
            ],
            [
                'id' => 3003,
                'rarity_id' => 3000,
                'character_category' => 1006,
                'name' => 'ユズハ'
            ],
            [
                'id' => 4001,
                'rarity_id' => 4000,
                'character_category' => 1006,
                'name' => 'ユキナ'
            ],
            [
                'id' => 4002,
                'rarity_id' => 4000,
                'character_category' => 1006,
                'name' => 'ルシェリス'
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('character_data')->insert($param);
        }
    }
}
