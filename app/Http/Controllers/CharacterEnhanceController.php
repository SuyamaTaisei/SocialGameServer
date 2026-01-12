<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\CharacterInstance;
use App\Models\ItemInstance;
use App\Models\ItemData;

use App\Services\CharacterEnhanceService;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CharacterEnhanceController extends Controller
{
    public function __invoke(Request $request, CharacterEnhanceService $characterEnhanceService)
    {
        $result = config('common.RESPONSE_FAILED');

        //ユーザー情報
        $userData = User::where('id',$request->id)->first();
        $manageId = $userData->manage_id;

        //所有済みのキャラクターID取得
        $characterInstance = CharacterInstance::where('manage_id', $manageId)->where('character_id', $request->character_id)->first();

        //所有済みアイテムID かつ 数量のペア取得
        $items = $request->input('items', []);

        //キャラクター強化サービス
        $characterEnhanceService->CharacterEnhance($result, $manageId, $characterInstance, $items);

        switch($result)
        {
            case config('common.RESPONSE_FAILED');
                $response['result'] = config('common.RESPONSE_ERROR');
                break;
            case config('common.RESPONSE_SUCCESS'):
                $response =
                [
                    'users' => User::where('manage_id', $manageId)->first(),
                    'wallets' => Wallet::where('manage_id',$manageId)->first(),
                    'character_instances' => CharacterInstance::where('manage_id', $manageId)->get(),
                    'item_instances' => ItemInstance::where('manage_id', $manageId)->get(),
                ];
                break;
        }

        return response()->json($response);
    }
}
