<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//アカウント登録バリデーションサービス
class RegisterValidationService
{
    public function RegisterValidation(Request $request)
    {
        //文字数チェック
        $validator = Validator::make($request->all(), ['user_name' => 'required|max:10']);
        
        //バリデーションに失敗したら
        if ($validator->fails())
        {
            return response()->json(['result' => config('common.ERRCODE_VALIDATION')]);
        }
        //成功したら
        $validated = $validator->safe();

        return $validated;
    }
}
