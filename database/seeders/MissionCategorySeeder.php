<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissionCategorySeeder extends Seeder
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
                'name' => '対戦'
            ],
            [
                'category' => 1002,
                'name' => '強化'
            ],
            [
                'category' => 1003,
                'name' => 'ガチャ'
            ],
            [
                'category' => 1004,
                'name' => 'スタミナ'
            ],
            [
                'category' => 1005,
                'name' => 'プレゼント'
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('mission_categories')->insert($param);
        }
    }
}
