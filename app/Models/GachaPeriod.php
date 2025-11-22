<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class GachaPeriod extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded =
    [
        'created_at',
    ];

    public static function GetMasterGachaPeriods()
    {
        $master_data = MasterDataService::GetMasterData('gacha_periods');
        return $master_data;
    }

    public static function GetMasterDataGachaPeriods($id)
    {
        $master_data = self::GetMasterGachaPeriods();
        foreach ($master_data as $column)
        {
            $model = new GachaPeriod;
			$model->id = $column['id'];
            $model->name = $column['name'];
            $model->single_cost = $column['single_cost'];
            $model->multi_cost = $column['multi_cost'];
            $model->start = $column['start'];
            $model->end = $column['end'];

			if ($id == $model->id)
            {
				return $model;
            }
        }
        return null;
    }
}
