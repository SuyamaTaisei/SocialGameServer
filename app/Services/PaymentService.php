<?php

namespace App\Services;

use App\Models\Wallet;

//支払い用Serviceクラス
class PaymentService
{
    public function PaymentGem(Wallet $walletData, int $cost, int $count): array
    {
        $paidGem = $walletData->gem_paid_amount;
        $freeGem = $walletData->gem_free_amount;

        //支払い計算(無償ジェム優先)
        $totalCost = $cost * $count;
        $freePay = min($totalCost, $freeGem);
        $paidPay = $totalCost - $freePay;

        return
        [
            'paidGem' => $paidGem,
            'freeGem' => $freeGem,
            'paidPay' => $paidPay,
            'freePay' => $freePay,
        ];
    }
}