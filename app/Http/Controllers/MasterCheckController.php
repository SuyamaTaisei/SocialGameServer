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
			return config('common.ERRCODE_MASTER_DATA_UPDATE');
        }
		$response = 
        [
			'master_data_version' => config('common.MASTER_DATA_VERSION'),
        ];
        return response()->json($response);
    }
}