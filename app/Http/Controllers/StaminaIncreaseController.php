<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;

use App\Services\PaymentService;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StaminaIncreaseController extends Controller
{
    public function __invoke(Request $request, PaymentService $paymentService)
    {
        //ユーザー情報
        $userData = User::where('id',$request->id)->first();

        //ウォレット情報
        $walletData = Wallet::where('manage_id', $userData->manage_id)->first();

        if (!$userData || !$walletData)
        {
            return response()->json(['errcode' => config('common.RESPONSE_ERROR')]);
        }

        //スタミナが最大値を超えたら回復できない
        if ($userData->last_stamina >= config('common.STAMINA_MAX_VALUE'))
        {
            return response()->json(['errcode' => config('common.RESPONSE_ERROR')]);
        }

        DB::transaction(function() use ($userData, $walletData, $paymentService)
        {
            //支払いサービス
            if (!$paymentService->PaymentGem($userData->manage_id, 50, 1))
            {
                return response()->json(['errcode' => config('common.ERRCODE_NOT_PAYMENT')]);
            }

            //スタミナ差分計算
            $addValue = min(config('common.STAMINA_INCREASE_VALUE'), config('common.STAMINA_MAX_VALUE') - $userData->last_stamina);

            //スタミナ回復
            $userData->update([
                'last_stamina' => $userData->last_stamina + $addValue,
                'stamina_updated' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        });

        $response =
        [
            'users' => User::where('manage_id', $userData->manage_id)->first(),
            'wallets' => Wallet::where('manage_id', $userData->manage_id)->first(),
        ];

        return response()->json($response);
    }
}
