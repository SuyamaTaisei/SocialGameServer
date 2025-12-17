<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GachaPeriodSeeder extends Seeder
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
                'name' => '通常ガチャ',
                'single_cost' => 250,
                'multi_cost' => 2500,
                'start' => '2000-01-01 00:00:00',
                'end' => '2038-12-31 23:59:59'
            ]
        ];

        foreach ($params as $param)
        {
            DB::table('gacha_periods')->insert($param);
        }
    }
}
