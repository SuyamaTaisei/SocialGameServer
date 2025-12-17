<?php

namespace App\Services;
use App\Models\ShopCategory;
use App\Models\ShopData;
use App\Models\ShopReward;

use App\Models\CharacterCategory;
use App\Models\CharacterData;
use App\Models\CharacterRarity;

use App\Models\ItemCategory;
use App\Models\ItemData;
use App\Models\ItemRarity;

use App\Models\GachaPeriod;
use App\Models\GachaData;

class MasterDataService
{
    //マスタデータ作成処理
    public static function CreateMasterData($version)
    {
        //指定バージョン(1)のファイルを作成
        touch(__DIR__ . '/' . $version);
        chmod(__DIR__ . '/' . $version, 0666);

        //master_dataを追加
        $master_data_list = array();
        $master_data_list['shop_categories'] = ShopCategory::all();
        $master_data_list['shop_data'] = ShopData::all();
        $master_data_list['shop_rewards'] = ShopReward::all();

        $master_data_list['character_categories'] = CharacterCategory::all();
        $master_data_list['character_data'] = CharacterData::all();
        $master_data_list['character_rarities'] = CharacterRarity::all();

        $master_data_list['item_categories'] = ItemCategory::all();
        $master_data_list['item_data'] = ItemData::all();
        $master_data_list['item_rarities'] = ItemRarity::all();

        $master_data_list['gacha_periods'] = GachaPeriod::all();
        $master_data_list['gacha_data'] = GachaData::all();

        //マスタデータをJSON形式で作成
        $json = json_encode($master_data_list);
        file_put_contents(__DIR__ . '/' . $version, $json);
    }

    //マスタデータ取得処理
    public static function GetMasterData($table_name)
    {
        //マスタデータのファイル取得
        $file = fopen(__DIR__ . '/' . config('common.MASTER_DATA_VERSION'), "r");
        if (!$file)
        {
            $response = array('message' => 'not file');
            return $response;
        }

        //JSON形式のマスタデータの取得
        $json = array();
        while ($line = fgets($file))
        {
            $json = json_decode($line, true);
        }

        //テーブルに対して、マスタデータのJson配列が存在するか確認
        if (!array_key_exists($table_name, $json))
        {
            $response = array('message' => 'Failed');
            return $response;
        }

        return $json[$table_name];
    }

    //マスタバージョンチェック処理
    public static function CheckMasterDataVersion($client_master_version)
    {
        return config('common.MASTER_DATA_VERSION') <= $client_master_version;
    }
}