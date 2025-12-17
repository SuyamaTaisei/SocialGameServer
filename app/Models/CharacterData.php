<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class CharacterData extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded =
    [
        'created_at',
    ];

    public static function GetMasterCharacterData()
    {
        $master_data = MasterDataService::GetMasterData('character_data');
        return $master_data;
    }

    public static function GetMasterDataCharacterData($id)
    {
        $master_data = self::GetMasterCharacterData();
        foreach ($master_data as $column)
        {
            $model = new CharacterData;
			$model->id = $column['id'];
            $model->rarity_id = $column['rarity_id'];
            $model->character_category = $column['character_category'];
            $model->name = $column['name'];

			if ($id == $model->id)
            {
				return $model;
            }
        }
        return null;
    }
}
