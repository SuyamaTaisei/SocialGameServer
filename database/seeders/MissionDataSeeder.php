<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissionDataSeeder extends Seeder
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
                'mission_category' => 1001,
                'goal' => 50,
                'description' => '50回対戦する',
                'reward_category' => 1001,
                'reward_value' => '50'
            ],
            [
                'id' => 1002,
                'mission_category' => 1002,
                'goal' => 50,
                'description' => '任意のキャラクターを50回強化する',
                'reward_category' => 1001,
                'reward_value' => '500'
            ],
            [
                'id' => 1003,
                'mission_category' => 1003,
                'goal' => 10,
                'description' => 'ガチャを10回引く',
                'reward_category' => 1001,
                'reward_value' => '1000'
            ],
            [
                'id' => 1004,
                'mission_category' => 1004,
                'goal' => 30,
                'description' => 'スタミナを30回回復する',
                'reward_category' => 1002,
                'reward_value' => '2500'
            ],
            [
                'id' => 1005,
                'mission_category' => 1005,
                'goal' => 10,
                'description' => 'プレゼントを10回開ける',
                'reward_category' => 1002,
                'reward_value' => '2000'
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('mission_data')->insert($param);
        }
    }
}
