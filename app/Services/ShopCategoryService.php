<?php

namespace App\Services;

use App\Models\ShopReward;

use Illuminate\Support\Facades\DB;

class ShopCategoryService
{
    public function ShopCategory($manageId, $productId, $buyAmount, $shopData, $itemAddService, $paymentService)
    {
        return DB::transaction(function() use ($manageId, $productId, $buyAmount, $shopData, $itemAddService, $paymentService)
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
                    return config('common.RESPONSE_ERROR');
                }
            }
            else if ($shopCategory === config('common.SHOP_CATEGORY_ITEM'))
            {
                if ($type == config('common.PAYMENT_TYPE_GEM'))
                {
                    if (!$paymentService->PaymentGem($manageId, $price, $buyAmount))
                    {
                        return config('common.RESPONSE_FAILED');
                    }
                }
                if ($type == config('common.PAYMENT_TYPE_COIN'))
                {
                    if (!$paymentService->PaymentCoin($manageId, $coinCurrency, $buyAmount))
                    {
                        return config('common.RESPONSE_FAILED');
                    }
                }
            }

            if ($shopCategory === config('common.SHOP_CATEGORY_ITEM'))
            {
                //商品IDに応じてitem_idと貰える数を指定
                $shopReward = ShopReward::where('product_id', $productId)->lockForUpdate()->first();
                $itemId = $shopReward->item_id;
                $itemAddService->AddItem($manageId, $itemId, $buyAmount);
            }

            return config('common.RESPONSE_SUCCESS');
        });
    }
}
