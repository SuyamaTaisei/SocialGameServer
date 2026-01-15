<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GachaLog;

use App\Services\StaminaDiffService;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request, StaminaDiffService $staminaDiffService)
    {
        //ユーザー情報取得
        $userData = User::where('id', $request->id)->first();

        //ユーザー情報がなければエラーを返して何もしない
        if (!$userData)
        {
            return response()->json(['errcode' => config('common.RESPONSE_ERROR')]);
        }

        //現在ログイン時刻
        $currentLogin = Carbon::now();
        //最終スタミナ更新時刻
        $lastStaminaUpdated = Carbon::parse($userData->stamina_updated);

        //スタミナ差分計算サービス
        $staminaDiffData = $staminaDiffService->StaminaDiff($userData, $lastStaminaUpdated, $currentLogin);

        DB::transaction(function() use ($userData, $staminaDiffData, $currentLogin)
        {
            $userData->update([
                'last_login' => $currentLogin->format('Y-m-d H:i:s'),
                'last_stamina' => $staminaDiffData,
                'stamina_updated' => $currentLogin->format('Y-m-d H:i:s'),
            ]);
        });

        //レスポンスデータ
        $response = 
        [
            'users' => User::where('manage_id', $userData->manage_id)->first(),
            'gacha_logs' => GachaLog::where('manage_id', $userData->manage_id)->get(),
        ];

        Auth::login($userData);

        return response()->json($response);
    }
}