<?php

namespace App\Services;

//ガチャ抽選計算用Serviceクラス
class GachaCalcService
{
    public function GachaCalculate(int $gachaCount, array $weightData): array
    {
        //抽選用データ
        $getCharacterId = [];

        //weightカラムの値を全て取得して合計値にする
        $totalWeight = array_sum(array_column($weightData, 'weight'));

        //ガチャ計算
        for($i = 0; $i < $gachaCount; $i++)
        {
            $gachaResult = false;
            
            //乱数生成
            $randomValue = mt_rand(0, $totalWeight);

            //重みと乱数を比較
            foreach($weightData as $data)
            {
                $weight = $data['weight'];
                if($weight >= $randomValue)
                {
                    //ガチャ結果のIDを保存
                    $gachaResult = (int)$data['character_id'];
                    break;
                }
                $randomValue -= $weight;
            }

            //ガチャで排出したデータの追加
            $getCharacterId[] =
            [
                'character_id' => $gachaResult,
            ];
        }

        return $getCharacterId;
    }
}