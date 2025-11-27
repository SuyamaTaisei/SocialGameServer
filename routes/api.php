<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/gettest', App\Http\Controllers\GetTestController::class); //接続テストAPI
Route::post('/register', App\Http\Controllers\RegisterController::class); //アカウント登録用API
Route::post('/login', App\Http\Controllers\LoginController::class); //ログインAPI
Route::post('/home', App\Http\Controllers\HomeController::class); //ホーム情報取得API
Route::post('/check_master_data', App\Http\Controllers\MasterCheckController::class); //マスタデータチェック取得API
Route::post('/get_master_data', App\Http\Controllers\MasterDataController::class); //マスタデータ取得API
Route::post('/payment', App\Http\Controllers\PaymentController::class); //ショップ購入API
Route::post('/gacha_execute', App\Http\Controllers\GachaExecuteController::class); //ガチャ実行API