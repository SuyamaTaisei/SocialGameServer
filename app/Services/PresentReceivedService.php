<?php

namespace App\Services;

use App\Models\User;
use App\Models\ItemInstance;
use App\Models\PresentInstance;
use App\Models\ItemData;
use App\Models\Wallet;

use App\Services\MissionProgressService;

use Carbon\Carbon;
use Throwable;

use Illuminate\Support\Facades\DB;

//プレゼント受け取り用サービス
class PresentReceivedService
{
    public function PresentReceived(&$result, $manageId, $missionId, $presents, $missionProgressService)
    {
        try
        {
            DB::transaction(function() use (&$result, $manageId, $missionId, $presents, $missionProgressService)
            {
                $walletData = Wallet::where('manage_id', $manageId)->lockForUpdate()->first();
    
                foreach($presents as $data)
                {
                    $id = $data['instance_id'];
                    $category = $data['category'];
                    $content = $data['content'];
                    $amount = $data['amount'];
    
                    $presentData = PresentInstance::where('id', $id)->where('manage_id', $manageId)->where('present_category', $category)->where('content', $content)->lockForUpdate()->first();

                    //受け取っていない かつ 現在時刻が受取期限を過ぎたら削除
                    if ($presentData->received == 0 && Carbon::now() > $presentData->period)
                    {
                        throw new \RuntimeException("プレゼント受取期限を超過している");
                    }

                    //カテゴリ毎に受け取る
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

                    //ミッション進捗追加
                    $missionProgressService->MissionProgress($manageId, $missionId, 1);
                }

                $result = config('common.RESPONSE_SUCCESS');
            });
        }
        catch (Throwable $e)
        {
            //一括受取時に期限内と期限超えの両方が含まれる場合に処理を無かった事にする必要があるため、自動ロールバック
            PresentInstance::where('manage_id', $manageId)->where('received', 0)->where('period', '<', now())->delete();
            $result = config('common.RESPONSE_ERROR');
        }
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
