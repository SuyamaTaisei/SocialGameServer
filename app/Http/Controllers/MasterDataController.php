<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopCategory;
use App\Models\ShopData;

use App\Models\CharacterCategory;
use App\Models\CharacterData;
use App\Models\CharacterRarity;

use App\Models\ItemCategory;
use App\Models\ItemData;
use App\Models\ItemRarity;

class MasterDataController extends Controller
{
    public function __invoke(Request $request)
	{
		//クライアント側に送信したいマスターデータを取得
		$shop_category = ShopCategory::GetMasterShopCategory();
        $shop_data = ShopData::GetMasterShopData();

		$character_category = CharacterCategory::GetMasterCharacterCategory();
		$character_data = CharacterData::GetMasterCharacterData();
		$character_rarity = CharacterRarity::GetMasterCharacterRarity();

		$item_category = ItemCategory::GetMasterItemCategory();
		$item_data = ItemData::GetMasterItemData();
		$item_rarity = ItemRarity::GetMasterItemRarity();

		$response = 
        [
			'master_data_version' => config('common.MASTER_DATA_VERSION'),
			'shop_category' => $shop_category,
            'shop_data' => $shop_data,

			'character_category' => $character_category,
			'character_data' => $character_data,
			'character_rarity' => $character_rarity,

			'item_category' => $item_category,
			'item_data' => $item_data,
			'item_rarity' => $item_rarity,
		];

		return response()->json($response);
	}
}