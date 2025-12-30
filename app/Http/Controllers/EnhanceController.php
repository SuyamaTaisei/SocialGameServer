<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CharacterInstance;
use App\Models\ItemInstance;
use App\Models\ItemData;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EnhanceController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = config('common.RESPONSE_FAILED');

        //ユーザー情報
        $userData = User::where('id',$request->id)->first();
        $manageId = $userData->manage_id;

        //所有済みのキャラクターID取得
        $characterInstance = CharacterInstance::where('manage_id', $manageId)->where('character_id', $request->character_id)->first();

        //所有済みアイテムID かつ 数量のペア取得
        $items = $request->input('items', []);

        DB::transaction(function() use (&$result, $manageId, $characterInstance, $items)
        {
            //上限値を超えた場合は何もしない
            if ($characterInstance->level >= config('common.MAX_CHARACTER_LEVEL'))
            {
                $result = config('common.RESPONSE_FAILED');
                return;
            }

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

                $itemInstance = ItemInstance::where('manage_id', $manageId)->where('item_id', $itemId)->first();
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

                //amountValueが0、上限値 - 最高レベル数の場合は何もしない
                if ($addValue <= 0)
                {
                    return;
                }

                //指定キャラのレベル、指定アイテムの数量レコード更新
                $characterInstance->update([
                    'level' => $characterInstance->level + $addValue,
                ]);
            }

            $result = config('common.RESPONSE_SUCCESS');
        });

        switch($result)
        {
            case config('common.RESPONSE_FAILED');
                $response['result'] = config('common.RESPONSE_ERROR');
                break;
            case config('common.RESPONSE_SUCCESS'):
                $response =
                [
                    'users' => User::where('manage_id', $manageId)->first(),
                    'character_instances' => CharacterInstance::where('manage_id', $manageId)->get(),
                    'item_instances' => ItemInstance::where('manage_id', $manageId)->get(),
                ];
                break;
        }

        return response()->json($response);
    }
}
