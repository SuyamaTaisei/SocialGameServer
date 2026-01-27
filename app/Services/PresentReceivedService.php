<?php

namespace App\Services;

use App\Models\ItemInstance;
use App\Models\PresentInstance;
use App\Models\ItemData;
use App\Models\Wallet;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

//プレゼント受け取り用サービス
class PresentReceivedService
{
    public function PresentReceived($manageId, $presents)
    {
        DB::transaction(function() use ($manageId, $presents)
        {
            $walletData = Wallet::where('manage_id', $manageId)->lockForUpdate()->first();

            foreach($presents as $data)
            {
                $id = $data['instance_id'];
                $category = $data['category'];
                $content = $data['content'];
                $amount = $data['amount'];

                switch($category)
                {
                    case 1001: $itemData = ItemData::where('id', $content)->lockForUpdate()->first();
                               $this->ReceivedItem($manageId, $id, $itemData->item_category, $content, $amount);
                        break;
                    case 2001: $walletData->update(['gem_paid_amount' => $walletData->gem_paid_amount + $content * $amount]);
                        break;
                    case 2002: $walletData->update(['coin_amount' => $walletData->coin_amount + $content * $amount]);
                        break;
                }
            }
        });
    }

    //プレゼント(アイテム)受け取り用メソッド
    public function ReceivedItem(int $manageId, $id, $category, int $itemId, int $amountValue)
    {
        //item_idを取得
        $existItem = ItemInstance::where('manage_id', $manageId)->where('item_id', $itemId)->lockForUpdate()->first();

        //現在のアイテム数を取得、何もアイテムが無ければ0を取得
        $currentAmount = $existItem?->amount ?? 0;

        //上限値に応じた追加アイテム数の取得
        $addValue = min($amountValue, config('common.MAX_ITEM_INSTANCE') - $currentAmount);

        //受け取った処理
        $presentData = PresentInstance::where('id', $id)->where('manage_id', $manageId)->where('present_category', $category)->where('content', $itemId)->lockForUpdate()->first();
        $presentData->update([
            'received' => 1,
            'updated_at' => Carbon::now(),
        ]);

        //初めてアイテムをもらった場合
        if ($existItem === null)
        {
            ItemInstance::create([
                'manage_id' => $manageId,
                'item_id'   => $itemId,
                'amount'    => $addValue,
            ]);
        }

        //既にアイテムが存在していた場合
        else
        {
            $existItem->update([
                'amount' => $currentAmount + $addValue,
            ]);
        }
    }
}
