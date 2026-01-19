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
        //ユーザー情報
        $userData = User::where('id',$request->id)->first();

        //ウォレット情報
        $walletData = Wallet::where('manage_id', $userData->manage_id)->first();

        if (!$userData || !$walletData)
        {
            return response()->json(['errcode' => config('common.RESPONSE_ERROR')]);
        }

        //スタミナが対戦時必要量を下回ったら何もしない
        if ($userData->last_stamina < config('common.STAMINA_DECREASE_VALUE'))
        {
            return response()->json(['errcode' => config('common.RESPONSE_ERROR')]);
        }

        DB::transaction(function() use ($userData, $walletData)
        {
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
