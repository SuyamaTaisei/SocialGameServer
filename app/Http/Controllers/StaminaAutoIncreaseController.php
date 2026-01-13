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
        $result = config('common.RESPONSE_FAILED');

        //ユーザー情報
        $userData = User::where('id',$request->id)->first();

        //ユーザー情報がなければエラーを返して何もしない
        if (!$userData)
        {
            $result = config('common.RESPONSE_FAILED');
            return;
        }

        //スタミナが理論値になったら自然回復を停止
        if ($userData->last_stamina >= config('common.STAMINA_MAX_VALUE'))
        {
            return;
        }

        //現在ログイン時刻
        $currentLogin = Carbon::now();
        //最終スタミナ更新時刻
        $lastStaminaUpdated = Carbon::parse($userData->stamina_updated);

        //スタミナ差分計算サービス
        $staminaDiffData = $staminaDiffService->StaminaDiff($userData, $lastStaminaUpdated, $currentLogin);

        DB::transaction(function() use (&$result, $userData, $staminaDiffData, $currentLogin)
        {
            $userData->update([
                'last_stamina' => $staminaDiffData,
                'stamina_updated' => $currentLogin->format('Y-m-d H:i:s'),
            ]);

            $result = config('common.RESPONSE_SUCCESS');
        });

        switch($result)
        {
            case config('common.RESPONSE_FAILED'):
                $response['result'] = config('common.RESPONSE_ERROR');
                break;
            case config('common.RESPONSE_SUCCESS'):
                $response =
                [
                    'users' => User::where('manage_id', $userData->manage_id)->first(),
                    'wallets' => Wallet::where('manage_id', $userData->manage_id)->first(),
                ];
                break;
        }

        return response()->json($response);
    }
}
