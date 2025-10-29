<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        // ユーザーID生成(重複する場合は生成し直し)
        do
        {
            $Id = Str::ulid();
            $isExistUser = User::where('id', $Id)->count();
        } while ($isExistUser != 0);

        //文字数チェック
        $validator = Validator::make($request->all(), ['user_name' => 'required|max:16']);
        
        //バリデーションに失敗したら
        if ($validator->fails())
        {
            return response()->json(['result' => config('common.ERRCODE_VALIDATION')]);
        }
        //成功したら
        $validated = $validator->safe();

        $post = User::create([
            'id'              => $Id,
            'user_name'       => $validated['user_name'],
            'max_stamina'     => config('common.DEFAULT_STAMINA'),
            'last_stamina'    => config('common.DEFAULT_STAMINA'),
            'stamina_updated' => Carbon::now()->format('Y-m-d H:i:s'),
            'last_login'      => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json($post);
    }
}