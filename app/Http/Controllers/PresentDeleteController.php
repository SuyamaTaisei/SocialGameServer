<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PresentInstance;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PresentDeleteController extends Controller
{
    public function __invoke(Request $request)
    {
        //ユーザー情報
        $userData = User::where('id', $request->id)->first();

        //プレゼント情報
        $presentData = PresentInstance::where('manage_id', $userData->manage_id)->first();

        //受け取っていない かつ 現在時刻が受取期限を過ぎたら削除
        if ($presentData->received == 0 && Carbon::now() > $presentData->period)
        {
            DB::transaction(function() use ($presentData)
            {
                $presentData->delete();
            });
        }

        //レスポンスデータ
        $response =
        [
            'present_instances' => PresentInstance::where('manage_id', $userData->manage_id)->get(),
        ];

        return response()->json($response);
    }
}
