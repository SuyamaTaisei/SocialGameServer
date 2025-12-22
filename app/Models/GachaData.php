<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class GachaData extends Model
{
    use HasFactory;

    protected $primaryKey = 'gacha_id';

    protected $guarded =
    [
        'created_at',
    ];

    public static function GetMasterGachaData()
    {
        $masterData = MasterDataService::GetMasterData('gacha_data');
        return $masterData;
    }

    public static function GetMasterDataGachaData($gacha_id)
    {
        $masterData = self::GetMasterGachaData();
        foreach ($masterData as $column)
        {
            $model = new GachaData;
			$model->gacha_id = $column['gacha_id'];
            $model->character_id = $column['character_id'];
            $model->weight = $column['weight'];

			if ($gacha_id == $model->gacha_id)
            {
				return $model;
            }
        }
        return null;
    }
}
