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

        //初めてアイテムをもらった場合
        if ($existItem === null)
        {
            ItemInstance::create([
                'manage_id' => $manageId,
                'item_id'   => $itemId,
                'amount'    => $amountValue,
            ]);
        }

        //既にアイテムが存在していた場合
        else
        {
            $existItem->update([
                'amount' => $existItem->amount + $amountValue,
            ]);
        }
    }
}