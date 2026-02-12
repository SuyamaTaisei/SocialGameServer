<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\MissionInstance;
use App\Models\MissionData;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MissionReceivedService
{
    public function MissionReceived($manageId, $mission)
    {
        DB::transaction(function() use ($manageId, $mission)
        {
            $walletData = Wallet::where('manage_id', $manageId)->lockForUpdate()->first();

            foreach($mission as $data)
            {
                $missionId = $data['mission_id'];
                $category = $data['mission_category'];

                $missionData = MissionData::where('id', $missionId)->lockForUpdate()->first();
                $missionReward = (int)$missionData->reward_value;

                //カテゴリ毎に受け取る
                switch($category)
                {
                    case ($category >= 1001 && $category <= 1003): $this->ReceivedWallet('gem_paid_amount', $walletData, $walletData->gem_paid_amount, $missionReward, $manageId, $missionId);
                    break;
                    case ($category >= 1004 && $category <= 1005): $this->ReceivedWallet('coin_amount', $walletData, $walletData->coin_amount, $missionReward, $manageId, $missionId);
                    break;
                }
            }
        });
    }

    //ウォレット受け取り用メソッド
    public function ReceivedWallet(string $column, $walletData, $currency, $missionReward, $manageId, $missionId)
    {
        //ウォレット増加処理
        $walletData->update([$column => $currency + $missionReward]);

        //受け取った処理
        $missionInstance = MissionInstance::where('manage_id', $manageId)->where('mission_id', $missionId)->lockForUpdate()->first();
        $missionInstance->update([
            'received' => 1,
            'updated_at' => Carbon::now(),
        ]);
    }
}
