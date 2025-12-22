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
        $masterData = MasterDataService::GetMasterData('item_categories');
        return $masterData;
    }

    public static function GetMasterDataItemCategories($category)
    {
        $masterData = self::GetMasterItemCategories();
        foreach ($masterData as $column)
        {
            $model = new ItemCategory;
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
