<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharacterCategorySeeder extends Seeder
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
                'name' => 'アサシン'
            ],
            [
                'category' => 1002,
                'name' => 'ハンター'
            ],
                        [
                'category' => 1003,
                'name' => 'ジャイアント'
            ],
            [
                'category' => 1004,
                'name' => 'ウィザード'
            ],
            [
                'category' => 1005,
                'name' => 'ヴァンパイア'
            ],
            [
                'category' => 1006,
                'name' => '天使'
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('character_categories')->insert($param);
        }
    }
}
