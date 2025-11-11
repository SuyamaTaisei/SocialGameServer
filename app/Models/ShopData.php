<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class ShopData extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $guarded =
    [
        'created_at',
    ];

    public static function GetMasterShopData()
    {
        $master_data = MasterDataService::GetMasterData('shop_data');
        return $master_data;
    }

    public static function GetMasterDataShopData($product_id)
    {
        $master_data = self::GetMasterShopData();
        foreach ($master_data as $column)
        {
            $model = new ShopData;
			$model->product_id    = $column['product_id'];
			$model->shop_category = $column['shop_category'];
            $model->name          = $column['name'];
            $model->price         = $column['price'];

			if ($product_id == $model->product_id)
            {
				return $model;
            }
        }
        return null;
    }
}