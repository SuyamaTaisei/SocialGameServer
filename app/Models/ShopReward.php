<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MasterDataService;

class ShopReward extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded =
    [
        'created_at',
    ];

    //書き込み可能なホワイトリスト
    protected $fillable =
    [
        'product_id',
        'item_id',
        'amount',
    ];

    public static function GetMasterShopRewards()
    {
        $masterData = MasterDataService::GetMasterData('shop_rewards');
        return $masterData;
    }

    public static function GetMasterDataShopRewards($id)
    {
        $masterData = self::GetMasterShopRewards();
        foreach ($masterData as $column)
        {
            $model = new ShopReward;
			$model->id = $column['id'];
            $model->product_id = $column['product_id'];
            $model->item_id = $column['item_id'];
            $model->amount = $column['amount'];

			if ($id == $model->id)
            {
				return $model;
            }
        }
        return null;
    }
}