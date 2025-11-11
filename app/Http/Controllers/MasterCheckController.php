<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MasterDataService;

class MasterCheckController extends Controller
{
    public function __invoke(Request $request)
    {
        //マスターデータチェック
        $client_master_version = $request->client_master_version;
        if (!MasterDataService::CheckMasterDataVersion($client_master_version)) {
			return config('common.ERROR_MASTER_DATA_UPDATE');
        }
        $response = array('message' => 'MasterData Version Success');
        return $response;
    }
}