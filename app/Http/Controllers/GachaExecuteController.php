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
use App\Services\GachaCalcService;
use App\Services\PaymentService;
use App\Services\GachaResultService;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GachaExecuteController extends Controller
{
    public function __invoke(Request $request, ItemAddService $itemAddService, GachaCalcService $gachaCalcService, PaymentService $paymentService, GachaResultService $gachaResultService)
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

        //ガチャデータ取得
        foreach($gachaData as $data)
        {
            $weightData[] =
            [
                'character_id' => $data->character_id,
                'weight' => $data->weight,
            ];
        }

        //ガチャ抽選計算サービス
        $getCharacterId = $gachaCalcService->GachaCalculate($gachaCount, $weightData);

        //支払いサービス
        $paymentData = $paymentService->PaymentGem($walletData, $defaultCost, $gachaCount);

        DB::transaction(function() use (&$result, $manageId, $gachaId, $getCharacterId, &$newCharacterId, &$exchangeItem, &$singleExchangeItem, $walletData, $itemAddService, $paymentData, $gachaResultService)
        {
            $paidGem = $paymentData['paidGem'];
            $freeGem = $paymentData['freeGem'];
            $paidPay = $paymentData['paidPay'];
            $freePay = $paymentData['freePay'];

            //マイナス時は購入失敗 (残高不足時)
            if ($paidGem - $paidPay < 0 || $freeGem - $freePay < 0)
            {
                $result = config('common.RESPONSE_FAILED');
                return;
            }

            //ウォレット更新
            $result = $walletData->update([
                'gem_paid_amount' => $paidGem - $paidPay,
                'gem_free_amount' => $freeGem - $freePay,
            ]);

            //ガチャ結果サービス
            $gachaResultService->GachaResult($manageId, $gachaId, $getCharacterId, $newCharacterId, $exchangeItem, $singleExchangeItem, $itemAddService);

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