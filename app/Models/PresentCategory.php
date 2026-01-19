<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class PresentCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'category';

    protected $guarded =
    [
        'created_at',
    ];

    //プレゼントカテゴリのテーブル情報の取得
    public static function GetMasterPresentCategories()
    {
        $masterData = MasterDataService::GetMasterData('present_categories');
        return $masterData;
    }

    //プレゼントカテゴリのモデル情報の取得
    public static function GetMasterDataPresentCategories($category)
    {
        $masterData = self::GetMasterPresentCategories();
        foreach ($masterData as $column)
        {
            $model = new PresentCategory;
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
