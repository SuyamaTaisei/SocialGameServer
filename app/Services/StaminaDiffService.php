<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

//スタミナ差分計算サービス
class StaminaDiffService
{
    public function StaminaDiff(User $userData, Carbon $lastDateTime, Carbon $currentLogin)
    {
        if ($userData->last_stamina <= config('common.STAMINA_MOST_VALUE'))
        {
            //最終ログインから現在ログインまでの差分を分単位で取得
            $diffValue = (int)$lastDateTime->diffInMinutes($currentLogin, true);
    
            //差分の分数だけスタミナを増やす
            $addDiff = $diffValue * config('common.STAMINA_INCREASE_AUTO_VALUE');
    
            //現在のスタミナに差分の数だけスタミナを足す。現在が最大値であれば常に最大値を返す
            $addStamina = min($userData->last_stamina + $addDiff, config('common.STAMINA_MOST_VALUE'));
        }
        else
        {
            $addStamina = $userData->last_stamina;
        }

        return $addStamina;
    }
}
