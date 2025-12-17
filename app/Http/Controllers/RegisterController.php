<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Wallet;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        $response['result'] = config('common.RESPONSE_SUCCESS');

        // ユーザーID生成(重複する場合は生成し直し)
        do
        {
            $Id = Str::ulid();
            $isExistUser = User::where('id', $Id)->count();
        } while ($isExistUser != 0);

        //文字数チェック
        $validator = Validator::make($request->all(), ['user_name' => 'required|max:10']);
        
        //バリデーションに失敗したら
        if ($validator->fails())
        {
            return response()->json(['result' => config('common.ERRCODE_VALIDATION')]);
        }
        //成功したら
        $validated = $validator->safe();

        //アカウント登録
        $accountData = User::create([
            'id'              => $Id,
            'user_name'       => $validated['user_name'],
            'max_stamina'     => config('common.DEFAULT_STAMINA'),
            'last_stamina'    => config('common.DEFAULT_STAMINA'),
            'stamina_updated' => Carbon::now()->format('Y-m-d H:i:s'),
            'last_login'      => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        //ユーザー情報取得
        $userData = User::where('id', $Id)->first();

        //ウォレット登録
        $walletData = Wallet::create([
            'manage_id'       => $userData->manage_id,
            'coin_amount'     => config('common.COIN_AMOUNT'),
            'gem_free_amount' => config('common.GEM_FREE_AMOUNT'),
            'gem_paid_amount' => config('common.GEM_PAID_AMOUNT'),
        ]);

        //ユーザー情報 と ウォレット情報があれば
        if ($userData && $walletData)
        {
            $result = 1;
        }

        switch ($result)
        {
            case 0:
                $response['result'] = config('common.ERRCODE_REGISTER');
                break;
            case 1:
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