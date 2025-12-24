<?php

namespace App\Services;

use App\Models\GachaData;

//ガチャ抽選計算用Serviceクラス
class GachaCalcService
{
    public function GachaCalculate(int $gachaCount, int $gachaId): array
    {
        //ガチャデータの全てのガチャ取得
        $gachaData = GachaData::where('gacha_id', $gachaId)->get();

        //抽選用データ
        $getCharacterId = [];

        //重みデータ
        $weightData = [];

        //ガチャデータ取得
        foreach($gachaData as $data)
        {
            $weightData[] =
            [
                'character_id' => $data->character_id,
                'weight' => $data->weight,
            ];
        }

        //weightカラムの値を全て取得して合計値にする
        $totalWeight = array_sum(array_column($weightData, 'weight'));

        //ガチャ計算
        for($i = 0; $i < $gachaCount; $i++)
        {
            $gachaResult = false;
            
            //乱数生成
            $randomValue = mt_rand(1, $totalWeight);

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