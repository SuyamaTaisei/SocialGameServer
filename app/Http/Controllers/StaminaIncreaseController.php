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

        DB::transaction(function() use (&$result, $userData, $walletData, $paymentService)
        {
            //スタミナが理論値を超えたら回復できない
            if ($userData->last_stamina >= 199)
            {
                $result = config('common.RESPONSE_FAILED');
                return;
            }

            //支払いサービス
            if (!$paymentService->PaymentGem($userData->manage_id, 50, 1))
            {
                $result = config('common.RESPONSE_ERROR');
                return;
            }

            //スタミナ差分計算
            $addValue = min(100, 199 - $userData->last_stamina);

            //スタミナ回復
            $userData->update([
                'last_stamina' => $userData->last_stamina + $addValue,
                'stamina_updated' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            $result = config('common.RESPONSE_SUCCESS');
        });

        switch($result)
        {
            case config('common.RESPONSE_FAILED'):
                $response['result'] = config('common.RESPONSE_ERROR');
                break;
            case config('common.RESPONSE_ERROR'):
                $response =
                [
                    'errcode' => config('common.ERRCODE_NOT_PAYMENT'),
                ];
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
