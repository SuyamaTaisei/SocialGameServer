<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\GachaData;
use App\Models\GachaPeriod;
use App\Models\GachaLog;
use App\Models\CharacterInstance;
use App\Models\CharacterData;
use App\Models\ItemInstance;
use App\Models\ItemData;
use App\Services\ItemAddService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GachaExecuteController extends Controller
{
    public function __invoke(Request $request, ItemAddService $itemAddService)
    {
        //ユーザー情報
        $userData = User::where('id',$request->id)->first();
        $manage_id = $userData->manage_id;

        //ガチャ回数
        $gachaCount = $request->gacha_count;

        //ウォレット情報
        $walletData = Wallet::where('manage_id',$manage_id)->first();
       
        //ガチャデータの全てのガチャ取得
        $gachaData = GachaData::where('gacha_id', $request->gacha_id)->get();

        //ガチャデータの期間取得
        $gacha_id = GachaData::where('gacha_id', $request->gacha_id)->first();

        //ガチャデータの価格取得
        $gachaPeriodData = GachaPeriod::where('id', $request->gacha_id)->first();
        $default_cost = $gachaPeriodData->single_cost;

        //抽選用データ
        $releasedGachaId = [];

        //重みデータ
        $weightData = [];

        //排出用データ
        $gachaResult = '';
        $newCharacterId = [];
        $singleExchangeItem = [];
        $exchangeItem = [];

        DB::transaction(function() use (&$result, $manage_id, $gachaData, $gacha_id, $default_cost, &$weightData, $gachaCount, &$releasedGachaId, &$newCharacterId, &$exchangeItem, &$singleExchangeItem, &$gachaResult, $userData, $walletData, $itemAddService)
        {
            $paidGem = $walletData->gem_paid_amount;
            $freeGem = $walletData->gem_free_amount;
            $paidPay = 0;
            $freePay = 0;

            //ガチャ期間に応じた取得
            $gachaCost = $gachaCount * $default_cost;

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
                    //排出したガチャのレアリティを取得
                    $gachaRarityData = CharacterData::where('id', $data['character_id'])->first();
                    $rarity_id = ItemData::where('rarity_id', $gachaRarityData->rarity_id)->first();

                    $item_id = $rarity_id->id;
                    $amount_value = 1;

                    $itemAddService->AddItem($manage_id, $item_id, $amount_value);

                    //ガチャ報酬単一表示用レスポンス
                    $singleExchangeItem[] =
                    [
                        'item_id' => $item_id,
                        'amount'  => $amount_value,
                    ];

                    //ガチャ実行時、配列にアイテム変換されたitem_idが存在しなければ変数を初期化
                    if (!isset($exchangeItem[$item_id]))
                    {
                        $exchangeItem[$item_id] =
                        [
                            'item_id' => $item_id,
                            'amount' => 0,
                        ];
                    }

                    //ガチャ報酬集計表示用レスポンス。変換したitem_id内のamountに合計値を設定
                    $exchangeItem[$item_id]['amount'] += $amount_value;
                }

                //回した分ガチャ履歴作成
                GachaLog::create([
                    'manage_id' => $manage_id,
                    'gacha_id' => $gacha_id->gacha_id,
                    'character_id' => $data['character_id'],
                ]);
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
                    'users' => User::where('manage_id', $manage_id)->first(),
                    'wallets' => Wallet::where('manage_id', $manage_id)->first(),
                    'character_instances' => CharacterInstance::where('manage_id', $manage_id)->get(),
                    'item_instances' => ItemInstance::where('manage_id', $manage_id)->get(),
                    'gacha_results' => $releasedGachaId,
                    'new_characters' => $newCharacterId,
                    'single_exchange_items' => $singleExchangeItem,
                    'total_exchange_items' => array_values($exchangeItem), //連想配列を数字添え字の形に変換して返す
                    'gacha_logs' => GachaLog::where('manage_id', $manage_id)->get(),
                ];
                break;
        }

        return response()->json($response);
    }
}