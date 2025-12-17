<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GachaDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = 
        [
            [
                'gacha_id' => 1001,
                'character_id' => 1001,
                'weight' => 7000
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 1002,
                'weight' => 7000
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 1003,
                'weight' => 7000
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 1004,
                'weight' => 7000
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 1005,
                'weight' => 7000
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 1006,
                'weight' => 7000
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 1007,
                'weight' => 7000
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 1008,
                'weight' => 7000
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 1009,
                'weight' => 7000
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 1010,
                'weight' => 7000
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 2001,
                'weight' => 5500
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 2002,
                'weight' => 5500
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 2003,
                'weight' => 5500
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 2004,
                'weight' => 5500
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 2005,
                'weight' => 5500
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 3001,
                'weight' => 800
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 3002,
                'weight' => 800
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 3003,
                'weight' => 800
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 4001,
                'weight' => 50
            ],
            [
                'gacha_id' => 1001,
                'character_id' => 4002,
                'weight' => 50
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('gacha_data')->insert($param);
        }
    }
}
