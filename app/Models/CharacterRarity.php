<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class CharacterRarity extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded =
    [
        'created_at',
    ];

    public static function GetMasterCharacterRarity()
    {
        $master_data = MasterDataService::GetMasterData('character_rarities');
        return $master_data;
    }

    public static function GetMasterDataCharacterRarity($id)
    {
        $master_data = self::GetMasterCharacterRarity();
        foreach ($master_data as $column)
        {
            $model = new CharacterRarity;
			$model->id   = $column['id'];
            $model->name = $column['name'];

			if ($id == $model->id)
            {
				return $model;
            }
        }
        return null;
    }
}
