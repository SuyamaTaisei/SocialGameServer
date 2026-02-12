<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\MissionInstance;
use App\Services\MissionReceivedService;

use Illuminate\Http\Request;

class MissionReceivedController extends Controller
{
    public function __invoke(Request $request, MissionReceivedService $missionReceivedService)
    {
        //ユーザー情報
        $userData = User::where('id',$request->id)->first();
        $manageId = $userData->manage_id;

        //カテゴリ、内容、数量のペア取得
        $mission = $request->input('mission', []);

        //ミッション受け取り用サービス
        $missionReceivedService->MissionReceived($manageId, $mission);

        $response =
        [
            'users' => User::where('manage_id', $manageId)->first(),
            'wallets' => Wallet::where('manage_id', $manageId)->first(),
            'mission_instances' => MissionInstance::where('manage_id', $manageId)->get(),
        ];

        return response()->json($response);
    }
}
