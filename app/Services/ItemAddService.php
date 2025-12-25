<?php

namespace App\Services;

use App\Models\ItemInstance;

//アイテム追加用Serviceクラス
class ItemAddService
{
    public function AddItem(int $manageId, int $itemId, int $amountValue)
    {
        //item_idを取得
        $existItem = ItemInstance::where('manage_id', $manageId)->where('item_id', $itemId)->first();

        //現在のアイテム数を取得、何もアイテムが無ければ0を取得
        $currentAmount = $existItem?->amount ?? 0;

        //上限値を超えた場合は何もしない
        if ($currentAmount >= config('common.MAX_ITEM_INSTANCE'))
        {
            return;
        }

        //上限値に応じた追加アイテム数の取得
        $addValue = min($amountValue, config('common.MAX_ITEM_INSTANCE') - $currentAmount);

        //amountValueが0、上限値 - 最高所持数の場合は何もしない
        if ($addValue <= 0)
        {
            return;
        }

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