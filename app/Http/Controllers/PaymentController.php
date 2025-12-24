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
use App\Services\PaymentService;

use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __invoke(Request $request, ItemAddService $itemAddService, PaymentService $paymentService)
    {
        $result = config('common.RESPONSE_FAILED');

        //ユーザー情報取得
        $userData = User::where('id',$request->id)->first();
        $manageId = $userData->manage_id;

        //商品ID情報取得
        $shopData = ShopData::where('product_id', $request->product_id)->first();
        $productId = $shopData->product_id;        

        //購入数
        $buyAmount = $request->amount;

        //計算処理
        DB::transaction(function() use (&$result, $manageId, $productId, $buyAmount, $shopData, $itemAddService, $paymentService)
        {
            //該当商品IDの各詳細情報取得
            $paidCurrency = $shopData->paid_currency;
            $freeCurrency = $shopData->free_currency;
            $coinCurrency = $shopData->coin_currency;
            $shopCategory = $shopData->shop_category;
            $type         = $shopData->type;
            $price        = $shopData->price;

            //各ショップカテゴリと支払いタイプ分岐
            if ($shopCategory === config('common.SHOP_CATEGORY_GEM'))
            {
                //支払いサービス
                if (!$paymentService->PaymentMoney($manageId, $paidCurrency, $freeCurrency))
                {
                    $result = config('common.RESPONSE_ERROR');
                    return;
                }
            }
            else if ($shopCategory === config('common.SHOP_CATEGORY_ITEM'))
            {
                if ($type == config('common.PAYMENT_TYPE_GEM'))
                {
                    if (!$paymentService->PaymentGem($manageId, $price, $buyAmount))
                    {
                        $result = config('common.RESPONSE_FAILED');
                        return;
                    }
                }
                if ($type == config('common.PAYMENT_TYPE_COIN'))
                {
                    if (!$paymentService->PaymentCoin($manageId, $coinCurrency, $buyAmount))
                    {
                        $result = config('common.RESPONSE_FAILED');
                        return;
                    }
                }
            }

            if ($shopCategory === config('common.SHOP_CATEGORY_ITEM'))
            {
                //商品IDに応じてitem_idと貰える数を指定
                $shopReward = ShopReward::where('product_id', $productId)->first();
                $itemId = $shopReward->item_id;
                $itemAddService->AddItem($manageId, $itemId, $buyAmount);
            }

            $result = config('common.RESPONSE_SUCCESS');
        });

        switch($result)
        {
            case config('common.RESPONSE_ERROR'):
                $response =
                [
                    'errcode' => config('common.ERRCODE_LIMIT_WALLETS'),
                ];
                break;
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
                    'wallets' => Wallet::where('manage_id',$manageId)->first(),
                    'item_instances' => ItemInstance::where('manage_id', $manageId)->get(),
                    'character_instances' => CharacterInstance::where('manage_id', $manageId)->get(),
                ];
                break;
        }
       
        return response()->json($response);
    }
}