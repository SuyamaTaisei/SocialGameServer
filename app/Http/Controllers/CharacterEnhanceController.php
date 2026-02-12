<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\CharacterInstance;
use App\Models\ItemInstance;
use App\Models\ItemData;
use App\Models\MissionInstance;

use App\Services\CharacterEnhanceService;
use App\Services\MissionProgressService;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CharacterEnhanceController extends Controller
{
    public function __invoke(Request $request, CharacterEnhanceService $characterEnhanceService, MissionProgressService $missionProgressService)
    {
        //ユーザー情報
        $userData = User::where('id',$request->id)->first();
        $manageId = $userData->manage_id;

        //ミッションID
        $missionId = $request->mission_id;

        //所有済みのキャラクターID取得
        $characterInstance = CharacterInstance::where('manage_id', $manageId)->where('character_id', $request->character_id)->first();

        //所有済みアイテムID かつ 数量のペア取得
        $items = $request->input('items', []);

        //上限値を超えた場合は何もしない
        if ($characterInstance->level >= config('common.MAX_CHARACTER_LEVEL'))
        {
            return response()->json(['errcode' => config('common.RESPONSE_ERROR')]);
        }

        //キャラクター強化サービス
        $characterEnhanceService->CharacterEnhance($manageId, $missionId, $characterInstance, $items, $missionProgressService);

        //レスポンスデータ
        $response =
        [
            'users' => User::where('manage_id', $manageId)->first(),
            'wallets' => Wallet::where('manage_id',$manageId)->first(),
            'character_instances' => CharacterInstance::where('manage_id', $manageId)->get(),
            'item_instances' => ItemInstance::where('manage_id', $manageId)->get(),
            'mission_instances' => MissionInstance::where('manage_id', $manageId)->get(),
        ];

        return response()->json($response);
    }
}
