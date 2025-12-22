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
        $manageId = $userData->manage_id;

        //ガチャ回数
        $gachaCount = $request->gacha_count;

        //ウォレット情報
        $walletData = Wallet::where('manage_id',$manageId)->first();
       
        //ガチャデータの全てのガチャ取得
        $gachaData = GachaData::where('gacha_id', $request->gacha_id)->get();

        //ガチャデータの期間取得
        $gachaId = GachaData::where('gacha_id', $request->gacha_id)->first();

        //ガチャデータの価格取得
        $gachaPeriodData = GachaPeriod::where('id', $request->gacha_id)->first();
        $defaultCost = $gachaPeriodData->single_cost;

        //抽選用データ
        $getCharacterId = [];

        //重みデータ
        $weightData = [];

        //排出用データ
        $newCharacterId = [];
        $singleExchangeItem = [];
        $exchangeItem = [];

        DB::transaction(function() use (&$result, $manageId, $gachaData, $gachaId, $defaultCost, &$weightData, $gachaCount, &$getCharacterId, &$newCharacterId, &$exchangeItem, &$singleExchangeItem, $userData, $walletData, $itemAddService)
        {
            $paidGem = $walletData->gem_paid_amount;
            $freeGem = $walletData->gem_free_amount;

            //ガチャ期間に応じた取得
            $gachaCost = $gachaCount * $defaultCost;

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
                $totalWeight = config('common.GACHA_TOTAL_WEIGHT');

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
        
            foreach($getCharacterId as $data)
            {
                //回したガチャが所持済みかどうか確認
                $exist = CharacterInstance::where('manage_id',$manageId)->where('character_id',$data['character_id'])->first();
                if($exist == null)
                {
                    //未所持なら取得したガチャの所持レコードを作成
                    CharacterInstance::create([
                        'manage_id' => $manageId,
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
                    $rarityId = ItemData::where('rarity_id', $gachaRarityData->rarity_id)->first();

                    $itemId = $rarityId->id;
                    $amountValue = 1;

                    $itemAddService->AddItem($manageId, $itemId, $amountValue);

                    //ガチャ報酬単一表示用レスポンス
                    $singleExchangeItem[] =
                    [
                        'item_id' => $itemId,
                        'amount'  => $amountValue,
                    ];

                    //ガチャ実行時、配列にアイテム変換されたitem_idが存在しなければ変数を初期化
                    if (!isset($exchangeItem[$itemId]))
                    {
                        $exchangeItem[$itemId] =
                        [
                            'item_id' => $itemId,
                            'amount' => 0,
                        ];
                    }

                    //ガチャ報酬集計表示用レスポンス。変換したitem_id内のamountに合計値を設定
                    $exchangeItem[$itemId]['amount'] += $amountValue;
                }

                //回した分ガチャ履歴作成
                GachaLog::create([
                    'manage_id' => $manageId,
                    'gacha_id' => $gachaId->gacha_id,
                    'character_id' => $data['character_id'],
                ]);
            }

            $result = config('common.RESPONSE_SUCCESS');
        });

        switch($result)
        {
            case config('common.RESPONSE_FAILED'):
                $response =
                [
                    'errcode' => config('common.ERRCODE_NOT_PAYMENT'),
                ];
                break;
            case config('common.RESPONSE_SUCCESS'):
                $response =
                [
                    'users' => User::where('manage_id', $manageId)->first(),
                    'wallets' => Wallet::where('manage_id', $manageId)->first(),
                    'character_instances' => CharacterInstance::where('manage_id', $manageId)->get(),
                    'item_instances' => ItemInstance::where('manage_id', $manageId)->get(),
                    'gacha_results' => $getCharacterId,
                    'new_characters' => $newCharacterId,
                    'single_exchange_items' => $singleExchangeItem,
                    'total_exchange_items' => array_values($exchangeItem), //連想配列を数字添え字の形に変換して返す
                    'gacha_logs' => GachaLog::where('manage_id', $manageId)->get(),
                ];
                break;
        }

        return response()->json($response);
    }
}