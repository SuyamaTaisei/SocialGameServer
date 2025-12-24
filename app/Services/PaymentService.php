<?php

namespace App\Services;

use App\Models\Wallet;

//支払い用Serviceクラス
class PaymentService
{
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
}