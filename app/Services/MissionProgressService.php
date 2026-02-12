<?php

namespace App\Services;

use App\Models\MissionInstance;
use App\Models\MissionData;

class MissionProgressService
{
    public function MissionProgress($manageId, $missionId, $progressValue)
    {
        $missionInstance = MissionInstance::where('manage_id', $manageId)->where('mission_id', $missionId)->lockForUpdate()->first();
        $missionData = MissionData::where('id', $missionId)->first();

        //初めてミッションを進めた場合
        if ($missionInstance === null)
        {
            MissionInstance::create([
                'manage_id' => $manageId,
                'mission_id' => $missionId,
                'mission_category' => $missionId,
                'progress' => $progressValue
            ]);
        }

        //既にミッションが存在していた場合
        else
        {
            $value = min($progressValue, $missionData->goal - $missionInstance->progress);
            $missionInstance->update([
                'progress' => $missionInstance->progress + $value,
            ]);
        }
    }

    //ミッション進捗のリセット処理を書く
}
