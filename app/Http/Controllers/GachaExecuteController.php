<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\GachaData;
use App\Models\GachaPeriod;
use App\Models\CharacterInstance;
use App\Models\CharacterData;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GachaExecuteController extends Controller
{
    public function __invoke(Request $request)
    {
        //ユーザー情報
        $userData = User::where('id',$request->id)->first();
        $manage_id = $userData->manage_id;

        //ガチャ回数
        $gachaCount = $request->gacha_count;

        //ウォレット情報
        $walletData = Wallet::where('manage_id',$manage_id)->first();
       
        //ガチャデータ
        $gachaData = GachaData::where('gacha_id', $request->gacha_id)->get();

        //ガチャ期間データ
        $gachaPeriodData = GachaPeriod::where('id', $request->gacha_id)->first();
        $gachaPeriod = $gachaPeriodData->id;

        //抽選用データ
        $releasedGachaId = [];

        //重みデータ
        $weightData = [];

        //排出用データ
        $gachaResult = '';
        $newCharacterId = [];

        DB::transaction(function() use (&$result, $manage_id, $gachaData, $gachaPeriod, &$weightData, $gachaCount, &$releasedGachaId, &$newCharacterId, &$gachaResult, $userData, $walletData)
        {
            $paidGem = $walletData->gem_paid_amount;
            $freeGem = $walletData->gem_free_amount;
            $paidPay = 0;
            $freePay = 0;

            //ガチャ期間
            switch ($gachaPeriod)
            {
                case config('common.GACHA_NORMAL_PERIOD'): $gachaCost = $gachaCount * config('common.GACHA_NORMAL_PAYMENT');
                break;
            }

            //ウォレット更新(無償ジェム優先)
            $freePay = min($gachaCost, $freeGem);
            $paidPay = $gachaCost - $freePay;

            //マイナス時は購入失敗 (残高不足時)
            if ($paidGem - $paidPay < 0 || $freeGem - $freePay < 0)
            {
                $response =
                [
                    'errcode' => config('common.ERRCODE_NOT_PAYMENT'),
                ];
                return;
            }

            //ウォレット更新
            $result = $walletData->update([
                'gem_paid_amount' => $paidGem - $paidPay,
                'gem_free_amount' => $freeGem - $freePay,
            ]);

            //ガチャデータ取得
            foreach($gachaData as $data)
            {
                $weightData[] =
                [
                    'character_id' => $data->character_id,
                    'weight' => $data->weight,
                ];
            }
        
            //ガチャ計算
            for($i = 0; $i < $gachaCount; $i++)
            {
                $gachaResult = false;
                
                //重み合計
                $total_weight = config('common.GACHA_TOTAL_WEIGHT');

                //乱数生成
                $randomValue = mt_rand(0, $total_weight);

                $weight = 0;

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
                $releasedGachaId[] =
                [
                    'character_id' => $gachaResult,
                ];
            }
        
            foreach($releasedGachaId as $data)
            {
                //回したガチャが所持済みかどうか確認
                $exist = CharacterInstance::where('manage_id',$manage_id)->where('character_id',$data['character_id'])->first();
                if($exist == null)
                {
                    //未所持なら取得したガチャの所持レコードを作成
                    CharacterInstance::create([
                        'manage_id' => $manage_id,
                        'character_id' => $data['character_id'],
                    ]);
                    //新規所持キャラとして配列に追加
                    $newCharacterId[] =
                    [
                        'character_id' => $data['character_id'],
                    ];
                }
                else
                {
                    //排出データを既に所有していれば、キャラクターのレアリティに応じて、アイテム(ガチャ報酬)を増やす処理を書く
                }
            }

            $result = 1;
        });

        switch($result)
        {
            case 0:
                $response =
                [
                    'errcode' => config('common.ERRCODE_NOT_PAYMENT'),
                ];
                break;
            case 1:
                $response =
                [
                    'wallets' => Wallet::where('manage_id', $manage_id)->first(),
                    'character_instance' => CharacterInstance::where('manage_id', $manage_id)->get(),
                    'gacha_result' => $releasedGachaId,
                    'new_characters' => $newCharacterId,
                ];
                break;
        }

        return response()->json($response);
    }
}