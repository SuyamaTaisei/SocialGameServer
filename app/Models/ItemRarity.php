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
        $masterData = MasterDataService::GetMasterData('item_rarities');
        return $masterData;
    }

    public static function GetMasterDataItemRarities($id)
    {
        $masterData = self::GetMasterItemRarities();
        foreach ($masterData as $column)
        {
            $model = new ItemRarity;
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
