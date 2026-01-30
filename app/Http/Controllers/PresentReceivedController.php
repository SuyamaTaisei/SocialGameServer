<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\CharacterInstance;
use App\Models\ItemInstance;
use App\Models\PresentInstance;

use App\Services\ItemAddService;
use App\Services\PresentReceivedService;

use Illuminate\Http\Request;

class PresentReceivedController extends Controller
{
    public function __invoke(Request $request, PresentReceivedService $presentReceivedService, ItemAddService $itemAddService)
    {
        //ユーザー情報
        $userData = User::where('id',$request->id)->first();
        $manageId = $userData->manage_id;

        //カテゴリ、内容、数量のペア取得
        $presents = $request->input('presents', []);

        //プレゼント受け取り用サービス
        $presentReceivedService->PresentReceived($manageId, $presents, $itemAddService);

        //レスポンスデータ
        $response =
        [
            'users' => User::where('manage_id', $manageId)->first(),
            'wallets' => Wallet::where('manage_id', $manageId)->first(),
            'item_instances' => ItemInstance::where('manage_id', $manageId)->get(),
            'present_instances' => PresentInstance::where('manage_id', $manageId)->get(),
        ];

        return response()->json($response);
    }
}
