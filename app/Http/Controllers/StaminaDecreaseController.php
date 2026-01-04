<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StaminaDecreaseController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = config('common.RESPONSE_FAILED');

        //ユーザー情報
        $userData = User::where('id',$request->id)->first();

        //ウォレット情報
        $walletData = Wallet::where('manage_id', $userData->manage_id)->first();

        //ユーザー情報がなければエラーを返して何もしない
        if (!$userData || !$walletData)
        {
            $result = config('common.RESPONSE_FAILED');
            return;
        }

        DB::transaction(function() use (&$result, $userData, $walletData)
        {
            //スタミナが対戦時必要量を下回ったら何もしない
            if ($userData->last_stamina < config('common.STAMINA_DECREASE_VALUE'))
            {
                $result = config('common.RESPONSE_FAILED');
                return;
            }

            //コイン差分計算
            $addValue = min(50, config('common.MAX_CURRENCY_VALUE') - $walletData->coin_amount);

            //スタミナ消費
            $userData->update([
                'last_stamina' => $userData->last_stamina - config('common.STAMINA_DECREASE_VALUE'),
                'stamina_updated' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $walletData->update([
                'coin_amount' => $walletData->coin_amount + $addValue,
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
