<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopCategory;
use App\Models\ShopData;

class MasterDataController extends Controller
{
    public function __invoke(Request $request)
	{
		//クライアント側に送信したいマスターデータを取得
		$shop_category = ShopCategory::GetMasterShopCategory();
        $shop_data = ShopData::GetMasterShopData();

		$response = 
        [
			'master_data_version' => config('common.MASTER_DATA_VERSION'),
			'shop_category' => $shop_category,
            'shop_data' => $shop_data,
        ];

		return response()->json($response);
	}
}