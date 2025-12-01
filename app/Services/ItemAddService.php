<?php

namespace App\Services;

use App\Models\ItemInstance;

//アイテム追加用Serviceクラス
class ItemAddService
{
    public function AddItem(int $manage_id, int $item_id, int $amount_value)
    {
        //item_idを取得
        $exist_item = ItemInstance::where('manage_id', $manage_id)->where('item_id', $item_id)->first();

        //初めてアイテムをもらった場合
        if ($exist_item === null)
        {
            ItemInstance::create([
                'manage_id' => $manage_id,
                'item_id'   => $item_id,
                'amount'    => $amount_value,
            ]);
        }

        //既にアイテムが存在していた場合
        else
        {
            $exist_item->update([
                'amount' => $exist_item->amount + $amount_value,
            ]);
        }
    }
}