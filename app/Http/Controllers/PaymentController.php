<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ShopData;
use App\Models\ShopReward;
use App\Models\Wallet;
use App\Models\ItemInstance;
use App\Models\CharacterInstance;
use App\Services\ItemAddService;

use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __invoke(Request $request, ItemAddService $itemAddService)
    {
        //ユーザー情報取得
        $userData = User::where('id',$request->id)->first();
        $manageId = $userData->manage_id;

        //商品ID情報取得
        $shopData = ShopData::where('product_id', $request->product_id)->first();
        $productId = $shopData->product_id;        

        //購入数
        $buyAmount = $request->amount;

        //該当商品IDの各詳細情報取得
        $paidCurrency = $shopData->paid_currency;
        $freeCurrency = $shopData->free_currency;
        $coinCurrency = $shopData->coin_currency;
        $shopCategory = $shopData->shop_category;
        $type          = $shopData->type;
        $price         = $shopData->price;

        //計算処理
        DB::transaction(function() use (&$result, $manageId, $productId, $buyAmount, $paidCurrency, $freeCurrency, $coinCurrency, $shopCategory, $type, $price, $itemAddService)
        {
            //ウォレット情報取得
            $walletsData = Wallet::where('manage_id', $manageId)->first();

            //初期化
            $paidGem  = $walletsData->gem_paid_amount;
            $freeGem  = $walletsData->gem_free_amount;
            $paidPay  = 0;
            $freePay  = 0;
            $paidCoin = $walletsData->coin_amount;
            $maxCount = config('common.MAX_CURRENCY_VALUE');

            //各ショップカテゴリと支払いタイプ分岐
            if ($shopCategory === config('common.SHOP_CATEGORY_GEM'))
            {
                $paidGem += $paidCurrency;
                $freeGem += $freeCurrency;
            }
            else if ($shopCategory === config('common.SHOP_CATEGORY_ITEM'))
            {
                if ($type == config('common.PAYMENT_TYPE_GEM'))
                {
                    $totalPrice = $price * $buyAmount;
                    $freePay = min($totalPrice, $freeGem);
                    $paidPay = $totalPrice - $freePay;
                }
                if ($type == config('common.PAYMENT_TYPE_COIN'))
                {
                    $paidCoin -= $coinCurrency * $buyAmount;
                }
            }

            //マイナス時は購入失敗 (残高不足時)
            if ($paidGem - $paidPay < 0 || $freeGem - $freePay < 0 || $paidCoin < 0)
            {
                $result = 0;
                return;
            }

            //残高上限を超えたら購入失敗
            if ($paidGem > $maxCount || $freeGem > $maxCount || $paidCoin > $maxCount)
            {
                $result = -1;
                return;
            }

            if ($shopCategory === config('common.SHOP_CATEGORY_ITEM'))
            {
                //商品IDに応じてitem_idと貰える数を指定
                $shopReward = ShopReward::where('product_id', $productId)->first();
                $itemId = $shopReward->item_id;
                $itemAddService->AddItem($manageId, $itemId, $buyAmount);
            }

            $result = $walletsData->update([
                'gem_paid_amount' => $paidGem - $paidPay,
                'gem_free_amount' => $freeGem - $freePay,
                'coin_amount'     => $paidCoin,
            ]);

            $result = 1;
        });

        switch($result)
        {
            case -1:
                $response =
                [
                    'errcode' => config('common.ERRCODE_LIMIT_WALLETS'),
                ];
                break;
            case 0:
                $response =
                [
                    'errcode' => config('common.ERRCODE_NOT_PAYMENT'),
                ];
                break;
            case 1:
                $response =
                [
                    'users' => User::where('manage_id', $manageId)->first(),
                    'wallets' => Wallet::where('manage_id',$manageId)->first(),
                    'item_instances' => ItemInstance::where('manage_id', $manageId)->get(),
                    'character_instances' => CharacterInstance::where('manage_id', $manageId)->get(),
                ];
                break;
        }
       
        return response()->json($response);
    }
}