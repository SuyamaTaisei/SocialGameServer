<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class CharacterCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'category';

    protected $guarded =
    [
        'created_at',
    ];

    public static function GetMasterCharacterCategories()
    {
        $masterData = MasterDataService::GetMasterData('character_categories');
        return $masterData;
    }

    public static function GetMasterDataCharacterCategories($category)
    {
        $masterData = self::GetMasterCharacterCategories();
        foreach ($masterData as $column)
        {
            $model = new CharacterCategory;
			$model->category = $column['category'];
            $model->name = $column['name'];

			if ($category == $model->category)
            {
				return $model;
            }
        }
        return null;
    }
}
