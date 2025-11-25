<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ShopData;
use App\Models\Wallet;

use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __invoke(Request $request)
    {
        //ユーザー情報取得
        $userData = User::where('id',$request->id)->first();

        //商品ID情報取得
        $shopData = ShopData::where('product_id', $request->product_id)->first();
        
        //該当商品IDの各詳細情報取得
        $paid_currency = $shopData->paid_currency;
        $free_currency = $shopData->free_currency;
        $coin_currency = $shopData->coin_currency;
        $shop_category = $shopData->shop_category;
        $type          = $shopData->type;
        $price         = $shopData->price;

        //計算処理
        DB::transaction(function() use (&$result, $userData, $paid_currency, $free_currency, $coin_currency, $shop_category, $type, $price)
        {
            //ウォレット情報取得
            $walletsData = Wallet::where('manage_id', $userData->manage_id)->first();

            //初期化
            $paidGem  = $walletsData->gem_paid_amount;
            $freeGem  = $walletsData->gem_free_amount;
            $paidPay  = 0;
            $freePay  = 0;
            $paidCoin = $walletsData->coin_amount;
            $maxCount = config('common.MAX_CURRENCY_VALUE');

            //各ショップカテゴリと支払いタイプ分岐
            if ($shop_category === config('common.SHOP_CATEGORY_GEM'))
            {
                $paidGem += $paid_currency;
                $freeGem += $free_currency;
            }
            else if ($shop_category === config('common.SHOP_CATEGORY_ITEM'))
            {
                if ($type == config('common.PAYMENT_TYPE_GEM'))
                {
                    $freePay = min($price, $freeGem);
                    $paidPay = $price - $freePay;
                }
                if ($type == config('common.PAYMENT_TYPE_COIN'))
                {
                    $paidCoin -= $coin_currency;
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
                    'users' => User::where('manage_id', $userData->manage_id)->first(),
                    'wallets' => Wallet::where('manage_id',$userData->manage_id)->first(),
                ];
                break;
        }
       
        return response()->json($response);
    }
}