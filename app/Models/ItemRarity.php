<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class ItemRarity extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded =
    [
        'created_at',
    ];

    public static function GetMasterItemRarities()
    {
        $master_data = MasterDataService::GetMasterData('item_rarities');
        return $master_data;
    }

    public static function GetMasterDataItemRarities($id)
    {
        $master_data = self::GetMasterItemRarities();
        foreach ($master_data as $column)
        {
            $model = new ItemRarity;
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
