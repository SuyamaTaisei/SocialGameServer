<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class MissionCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'category';

    protected $guarded =
    [
        'created_at',
    ];

    //ミッションカテゴリのテーブル情報の取得
    public static function GetMasterMissionCategories()
    {
        $masterData = MasterDataService::GetMasterData('mission_categories');
        return $masterData;
    }

    //ミッションカテゴリのモデル情報の取得
    public static function GetMasterDataMissionCategories($category)
    {
        $masterData = self::GetMasterMissionCategories();
        foreach ($masterData as $column)
        {
            $model = new MissionCategory;
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
