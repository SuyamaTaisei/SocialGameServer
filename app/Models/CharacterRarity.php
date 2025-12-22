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

    public static function GetMasterCharacterRarities()
    {
        $masterData = MasterDataService::GetMasterData('character_rarities');
        return $masterData;
    }

    public static function GetMasterDataCharacterRarities($id)
    {
        $masterData = self::GetMasterCharacterRarities();
        foreach ($masterData as $column)
        {
            $model = new CharacterRarity;
			$model->id = $column['id'];
            $model->name = $column['name'];

			if ($id == $model->id)
            {
				return $model;
            }
        }
        return null;
    }
}
