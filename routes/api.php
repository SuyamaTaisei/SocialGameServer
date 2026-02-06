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
Route::post('/payment', App\Http\Controllers\ShopController::class); //ショップ購入API
Route::get('/gacha_execute', App\Http\Controllers\GachaExecuteController::class); //ガチャ実行API
Route::get('/enhance_character', App\Http\Controllers\CharacterEnhanceController::class); //キャラ強化API
Route::get('/stamina_decrease', App\Http\Controllers\StaminaDecreaseController::class); //スタミナ消費API
Route::get('/stamina_increase', App\Http\Controllers\StaminaIncreaseController::class); //スタミナ回復API
Route::post('/stamina_auto_increase', App\Http\Controllers\StaminaAutoIncreaseController::class); //スタミナ自然回復API
Route::get('/present_received', App\Http\Controllers\PresentReceivedController::class); //プレゼント受け取りAPI
Route::post('/present_delete', App\Http\Controllers\PresentDeleteController::class); //プレゼント削除API
Route::get('/mission_received', App\Http\Controllers\MissionReceivedController::class); //ミッション受け取りAPI