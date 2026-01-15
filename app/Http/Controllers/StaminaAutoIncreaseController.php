<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;

use App\Services\StaminaDiffService;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StaminaAutoIncreaseController extends Controller
{
    public function __invoke(Request $request, StaminaDiffService $staminaDiffService)
    {
        //ユーザー情報
        $userData = User::where('id',$request->id)->first();

        if (!$userData)
        {
            return response()->json(['errcode' => config('common.RESPONSE_ERROR')]);
        }

        //スタミナが理論値になったら自然回復を停止
        if ($userData->last_stamina >= config('common.STAMINA_MAX_VALUE'))
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
                'last_stamina' => $staminaDiffData,
                'stamina_updated' => $currentLogin->format('Y-m-d H:i:s'),
            ]);
        });

        //レスポンスデータ
        $response =
        [
            'users' => User::where('manage_id', $userData->manage_id)->first(),
            'wallets' => Wallet::where('manage_id', $userData->manage_id)->first(),
        ];

        return response()->json($response);
    }
}
