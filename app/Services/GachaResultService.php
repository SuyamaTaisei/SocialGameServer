<?php

namespace App\Services;

use App\Models\GachaLog;
use App\Models\CharacterInstance;
use App\Models\CharacterData;
use App\Models\ItemData;
use App\Services\ItemAddService;

class GachaResultService
{
    public function GachaResult($manageId, $gachaId, $getCharacterId, &$newCharacterId, &$exchangeItem, &$singleExchangeItem, ItemAddService $itemAddService)
    {
        foreach($getCharacterId as $data)
        {
            //排出されたキャラが所持済みかどうか確認
            $exist = CharacterInstance::where('manage_id',$manageId)->where('character_id',$data['character_id'])->first();
            if($exist == null)
            {
                //未所持なら取得したキャラの所持レコードを作成
                CharacterInstance::create([
                    'manage_id' => $manageId,
                    'character_id' => $data['character_id'],
                ]);
                //新規所持キャラとして配列に追加
                $newCharacterId[] =
                [
                    'character_id' => $data['character_id'],
                ];
            }
            else
            {
                //排出したキャラのレアリティを取得
                $gachaRarityData = CharacterData::where('id', $data['character_id'])->first();
                $rarityId = ItemData::where('rarity_id', $gachaRarityData->rarity_id)->first();

                $itemId = $rarityId->id;
                $amountValue = 1;

                //アイテム追加サービス
                $itemAddService->AddItem($manageId, $itemId, $amountValue);

                //ガチャ報酬単一表示用レスポンス
                $singleExchangeItem[] =
                [
                    'item_id' => $itemId,
                    'amount'  => $amountValue,
                ];

                //ガチャ実行時、配列にアイテム変換されたitem_idが存在しなければ変数を初期化
                if (!isset($exchangeItem[$itemId]))
                {
                    $exchangeItem[$itemId] =
                    [
                        'item_id' => $itemId,
                        'amount' => 0,
                    ];
                }

                //ガチャ報酬集計表示用レスポンス。変換したitem_id内のamountに合計値を設定
                $exchangeItem[$itemId]['amount'] += $amountValue;
            }

            //回した分ガチャ履歴作成
            GachaLog::create([
                'manage_id' => $manageId,
                'gacha_id' => $gachaId->gacha_id,
                'character_id' => $data['character_id'],
            ]);
        }
    }
}