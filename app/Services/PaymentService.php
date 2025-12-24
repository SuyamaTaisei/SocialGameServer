<?php

namespace App\Services;

use App\Models\Wallet;

//支払い用Serviceクラス
class PaymentService
{
    //現金での支払い処理
    public function PaymentMoney($manageId, int $paidCurrency, int $freeCurrency): bool
    {
        $walletData = Wallet::where('manage_id',$manageId)->first();
        $maxCount = config('common.MAX_CURRENCY_VALUE');

        $paidGem = $walletData->gem_paid_amount + $paidCurrency;
        $freeGem = $walletData->gem_free_amount + $freeCurrency;

        //残高上限を超えたら購入失敗
        if ($paidGem > $maxCount || $freeGem > $maxCount)
        {
            return false;
        }

        return $walletData->update([
            'gem_paid_amount' => $paidGem,
            'gem_free_amount' => $freeGem,
        ]);
    }

    //ジェムでの支払い処理
    public function PaymentGem($manageId, int $cost, int $count): bool
    {
        //ウォレット情報
        $walletData = Wallet::where('manage_id',$manageId)->first();

        $paidGem = $walletData->gem_paid_amount;
        $freeGem = $walletData->gem_free_amount;

        //支払い計算(無償ジェム優先)
        $totalCost = $cost * $count;
        $freePay = min($totalCost, $freeGem);
        $paidPay = $totalCost - $freePay;

        //マイナス時は購入失敗 (残高不足時)
        if ($paidGem - $paidPay < 0 || $freeGem - $freePay < 0)
        {
            return false;
        }

        //ウォレット更新
        return $walletData->update([
            'gem_paid_amount' => $paidGem - $paidPay,
            'gem_free_amount' => $freeGem - $freePay,
        ]);
    }

    //コインでの支払い処理
    public function PaymentCoin($manageId, int $coinCurrency, int $count): bool
    {
        $walletData = Wallet::where('manage_id',$manageId)->first();

        $paidCoin = $walletData->coin_amount;

        $paidCoin -= $coinCurrency * $count;

        if ($paidCoin < 0)
        {
            return false;
        }

        return $walletData->update([
            'coin_amount' => $paidCoin,
        ]);
    }
}