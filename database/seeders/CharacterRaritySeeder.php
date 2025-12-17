<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharacterRaritySeeder extends Seeder
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
                'name' => 'NORMAL'
            ],
            [
                'id' => 2000,
                'name' => 'RARE'
            ],
                        [
                'id' => 3000,
                'name' => 'S RARE'
            ],
            [
                'id' => 4000,
                'name' => 'SS RARE'
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('character_rarities')->insert($param);
        }
    }
}
