<?php

namespace App\Http\Controllers;

use App\Models\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StaminaAutoIncreaseController extends Controller
{
    public function __invoke(Request $request)
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

        DB::transaction(function() use (&$result, $userData)
        {
            //スタミナ自然回復
            $userData->update([
                'last_stamina' => $userData->last_stamina + config('common.STAMINA_INCREASE_AUTO_VALUE'),
                'stamina_updated' => Carbon::now()->format('Y-m-d H:i:s'),
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
                ];
                break;
        }

        return response()->json($response);
    }
}
