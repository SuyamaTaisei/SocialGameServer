<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = config('common.RESPONSE_FAILED');
        $response['result'] = config('common.RESPONSE_SUCCESS');

        //ユーザー情報取得
        $userData = User::where('id', $request->id)->first();

        //最終ログイン時間更新
        $result = User::where('manage_id', $userData->manage_id)->update([
            'last_login' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        
        switch ($result)
        {
            //エラー時
            case config('common.RESPONSE_FAILED'):
                $response['result'] = config('common.RESPONSE_ERROR');
                break;
            //ログイン時に必要な情報を取得
            case config('common.RESPONSE_SUCCESS'):
                $response = 
                [
                    'users' => User::where('manage_id', $userData->manage_id)->first(),
                ];
                break;
        }

        Auth::login($userData);

        return response()->json($response);
    }
}