<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\ItemInstance;
use App\Models\CharacterInstance;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        //ユーザー情報取得
        $userData = User::where('id', $request->id)->first();

        if (!$userData)
        {
            return response()->json(['errcode' => config('common.RESPONSE_ERROR')]);
        }
        
        //レスポンスデータ
        $response =
        [
            'users' => User::where('manage_id', $userData->manage_id)->first(),
            'wallets' => Wallet::where('manage_id', $userData->manage_id)->first(),
            'item_instances' => ItemInstance::where('manage_id', $userData->manage_id)->get(),
            'character_instances' => CharacterInstance::where('manage_id', $userData->manage_id)->get(),
        ];

        return response()->json($response);
    }
}
