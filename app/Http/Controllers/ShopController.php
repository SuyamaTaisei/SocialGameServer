<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ShopData;
use App\Models\Wallet;
use App\Models\ItemInstance;
use App\Models\CharacterInstance;

use App\Services\ShopCategoryService;
use App\Services\ItemAddService;
use App\Services\PaymentService;

class ShopController extends Controller
{
    public function __invoke(Request $request, ItemAddService $itemAddService, PaymentService $paymentService, ShopCategoryService $shopCategoryService)
    {
        //ユーザー情報取得
        $userData = User::where('id',$request->id)->first();
        $manageId = $userData->manage_id;

        //商品ID情報取得
        $shopData = ShopData::where('product_id', $request->product_id)->first();
        $productId = $shopData->product_id;        

        //購入数
        $buyAmount = $request->amount;

        //ショップカテゴリサービス
        $result = $shopCategoryService->ShopCategory($manageId, $productId, $buyAmount, $shopData, $itemAddService, $paymentService);

        //レスポンスデータ
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