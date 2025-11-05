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
        $result = 0;

        //ユーザー情報取得
        $userData = User::where('id', $request->id)->first();

        $result = User::where('manage_id', $userData->manage_id)->update([
            'last_login' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        
        $response['result'] = config('common.RESPONSE_SUCCESS');

        //エラー時
        if ($result == 0)
        {
            $response['result'] = -1;
        }
        //ログイン時に必要な情報を取得
        else
        {
            $response = User::where('manage_id', $userData->manage_id)->first();
        }

        Auth::login($userData);

        return response()->json($response);
    }
}