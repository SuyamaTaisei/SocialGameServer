<?php

namespace App\Services;

use App\Models\ItemInstance;
use App\Models\ItemData;

use Illuminate\Support\Facades\DB;

//キャラクター強化サービス
class CharacterEnhanceService
{
    public function CharacterEnhance($manageId, $characterInstance, $items)
    {
        DB::transaction(function() use ($manageId, $characterInstance, $items)
        {
            $itemTotalAmount = 0;

            foreach($items as $data)
            {
                //指定された同じアイテムIDに対応する数量のペア
                $itemId = $data['item_id'];
                $itemAmount = $data['amount'];
                if ($itemAmount <= 0)
                {
                    continue; //数量が0だった場合はスキップ
                }

                $itemInstance = ItemInstance::where('manage_id', $manageId)->where('item_id', $itemId)->lockForUpdate()->first();
                if (!$itemInstance)
                {
                    continue; //指定された所持アイテムが無ければスキップ
                }

                $itemData = ItemData::where('id', $itemInstance->item_id)->first();
                if (!$itemData)
                {
                    continue; //アイテムデータに無ければスキップ
                }

                $itemInstance->update([
                    'amount' => $itemInstance->amount - $itemAmount,
                ]);

                //アイテムを使い切ったら該当レコード削除
                if ($itemInstance->amount <= 0)
                {
                    $itemInstance->delete();
                }

                $currentLevel = $characterInstance->level;
                $itemTotalAmount = $itemData->value * $itemAmount;

                //上限値に応じた追加レベル数の取得
                $addValue = min($itemTotalAmount, config('common.MAX_CHARACTER_LEVEL') - $currentLevel);

                //指定キャラのレベル、指定アイテムの数量レコード更新
                $characterInstance->update([
                    'level' => $characterInstance->level + $addValue,
                ]);
            }
        });
    }
}
