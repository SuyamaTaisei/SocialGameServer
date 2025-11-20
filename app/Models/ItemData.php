<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class ItemData extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded =
    [
        'created_at',
    ];

    public static function GetMasterItemData()
    {
        $master_data = MasterDataService::GetMasterData('item_data');
        return $master_data;
    }

    public static function GetMasterDataItemData($id)
    {
        $master_data = self::GetMasterItemData();
        foreach ($master_data as $column)
        {
            $model = new ItemData;
			$model->id            = $column['id'];
            $model->rarity_id     = $column['rarity_id'];
            $model->item_category = $column['item_category'];
            $model->name          = $column['name'];
            $model->description   = $column['description'];
            $model->value         = $column['value'];

			if ($id == $model->id)
            {
				return $model;
            }
        }
        return null;
    }
}
