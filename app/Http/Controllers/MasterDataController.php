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

use App\Models\GachaPeriod;
use App\Models\GachaData;

class MasterDataController extends Controller
{
    public function __invoke(Request $request)
	{
		//クライアント側に送信したいマスターデータを取得
		$shop_categories = ShopCategory::GetMasterShopCategories();
        $shop_data = ShopData::GetMasterShopData();

		$character_categories = CharacterCategory::GetMasterCharacterCategories();
		$character_data = CharacterData::GetMasterCharacterData();
		$character_rarities = CharacterRarity::GetMasterCharacterRarities();

		$item_categories = ItemCategory::GetMasterItemCategories();
		$item_data = ItemData::GetMasterItemData();
		$item_rarities = ItemRarity::GetMasterItemRarities();

		$gacha_periods = GachaPeriod::GetMasterGachaPeriods();
		$gacha_data = GachaData::GetMasterGachaData();

		$response = 
        [
			'master_data_version' => config('common.MASTER_DATA_VERSION'),
			'shop_categories' => $shop_categories,
            'shop_data' => $shop_data,

			'character_categories' => $character_categories,
			'character_data' => $character_data,
			'character_rarities' => $character_rarities,

			'item_categories' => $item_categories,
			'item_data' => $item_data,
			'item_rarities' => $item_rarities,

			'gacha_periods' => $gacha_periods,
			'gacha_data' => $gacha_data,
		];

		return response()->json($response);
	}
}