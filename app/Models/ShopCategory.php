<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class ShopCategory extends Model
{
    //マスタデータ作成のデータ生成クラス
    use HasFactory;

    protected $primaryKey = 'category';

    //更新しないリスト
    protected $guarded =
    [
        'created_at',
    ];

    //ショップカテゴリのテーブル情報の取得
    public static function GetMasterShopCategories()
    {
        $master_data = MasterDataService::GetMasterData('shop_categories');
        return $master_data;
    }

    //ショップカテゴリのモデル情報の取得
    public static function GetMasterDataShopCategories($category)
    {
        $master_data = self::GetMasterShopCategories();
        foreach ($master_data as $column)
        {
            $model = new ShopCategory;
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