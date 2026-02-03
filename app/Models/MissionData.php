<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class MissionData extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded =
    [
        'created_at',
    ];

    public static function GetMasterMissionData()
    {
        $masterData = MasterDataService::GetMasterData('mission_data');
        return $masterData;
    }

    public static function GetMasterDataMissionData($id)
    {
        $masterData = self::GetMasterMissionData();
        foreach ($masterData as $column)
        {
            $model = new MissionData;
			$model->id = $column['id'];
            $model->mission_category = $column['mission_category'];
            $model->goal = $column['goal'];
            $model->description = $column['description'];
            $model->reward_category = $column['reward_category'];
            $model->reward_value = $column['reward_value'];

			if ($id == $model->id)
            {
				return $model;
            }
        }
        return null;
    }
}
