<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class ItemCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'category';

    protected $guarded =
    [
        'created_at',
    ];

    public static function GetMasterItemCategories()
    {
        $master_data = MasterDataService::GetMasterData('item_categories');
        return $master_data;
    }

    public static function GetMasterDataItemCategories($category)
    {
        $master_data = self::GetMasterItemCategories();
        foreach ($master_data as $column)
        {
            $model = new ItemCategory;
			$model->category = $column['category'];
            $model->name     = $column['name'];

			if ($category == $model->category)
            {
				return $model;
            }
        }
        return null;
    }
}
