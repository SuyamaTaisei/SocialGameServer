<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Wallet;

use App\Services\RegisterValidationService;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __invoke(Request $request, RegisterValidationService $RegisterValidationService)
    {
        //ユーザーデータ
        $userData = 0;

        //ユーザーID生成(重複する場合は生成し直し)
        do
        {
            $Id = Str::ulid();
            $isExistUser = User::where('id', $Id)->count();
        } while ($isExistUser != 0);

        //登録バリデーションサービス
        $userValidated = $RegisterValidationService->RegisterValidation($request);

        DB::transaction(function() use ($Id, &$userData, $userValidated)
        {
            //アカウント登録
            $accountData = User::create([
                'id'              => $Id,
                'user_name'       => $userValidated['user_name'],
                'max_stamina'     => config('common.DEFAULT_STAMINA'),
                'last_stamina'    => config('common.DEFAULT_STAMINA'),
                'stamina_updated' => Carbon::now()->format('Y-m-d H:i:s'),
                'last_login'      => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            //ユーザー情報取得
            $userData = User::where('id', $Id)->lockForUpdate()->first();

            //ウォレット登録
            $walletData = Wallet::create([
                'manage_id'       => $userData->manage_id,
                'coin_amount'     => config('common.COIN_AMOUNT'),
                'gem_free_amount' => config('common.GEM_FREE_AMOUNT'),
                'gem_paid_amount' => config('common.GEM_PAID_AMOUNT'),
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