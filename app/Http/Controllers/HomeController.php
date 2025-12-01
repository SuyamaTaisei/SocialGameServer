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
        $result = 0;
        $response['result'] = config('common.RESPONSE_SUCCESS');

        //ユーザー情報取得
        $userData = User::where('id', $request->id)->first();

        //ユーザー情報があれば
        if ($userData)
        {
            $result = 1;
        }
        
        switch ($result)
        {
            //エラー時
            case 0:
                $response['result'] = -1;
                break;
            //必要な情報を取得
            case 1:
                $response =
                [
                    'users' => User::where('manage_id', $userData->manage_id)->first(),
                    'wallets' => Wallet::where('manage_id', $userData->manage_id)->first(),
                    'item_instances' => ItemInstance::where('manage_id', $userData->manage_id)->get(),
                    'character_instances' => CharacterInstance::where('manage_id', $userData->manage_id)->get(),
                ];
                break;
        }

        return response()->json($response);
    }
}
