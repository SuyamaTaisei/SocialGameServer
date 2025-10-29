<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/gettest', App\Http\Controllers\GetTestController::class);    //接続テストAPI
Route::post('/register', App\Http\Controllers\RegisterController::class); //アカウント登録用API