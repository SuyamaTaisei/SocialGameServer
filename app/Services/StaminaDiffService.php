<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

//スタミナ差分計算サービス
class StaminaDiffService
{
    public function StaminaDiff(User $userData, Carbon $lastLogin, Carbon $currentLogin)
    {
        //最終ログインから現在ログインまでの差分を分単位で取得
        $diffValue = (int)$lastLogin->diffInMinutes($currentLogin, true);

        //差分の分数だけスタミナを増やす
        $addDiff = $diffValue * config('common.STAMINA_INCREASE_AUTO_VALUE');

        //現在のスタミナに差分の数だけスタミナを足す。現在が最大値であれば常に最大値を返す
        $addStamina = min($userData->last_stamina + $addDiff, config('common.STAMINA_MAX_VALUE'));

        return $addStamina;
    }
}
