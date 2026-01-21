<?php

namespace App\Services;

use App\Models\ItemInstance;
use App\Models\PresentCategory;
use App\Models\ItemData;
use App\Models\Wallet;

use Illuminate\Support\Facades\DB;

class PresentReceivedService
{
    public function PresentReceived($manageId, $presents, $itemAddService)
    {
        DB::transaction(function() use ($manageId, $presents, $itemAddService)
        {
            $walletData = Wallet::where('manage_id', $manageId)->lockForUpdate()->first();

            foreach($presents as $data)
            {
                $category = $data['category'];
                $content = $data['content'];
                $amount = $data['amount'];

                switch($category)
                {
                    case 1001: $itemData = ItemData::where('id', $content)->first();
                               $itemAddService->AddItem($manageId, $itemData->item_category, $itemData->name, $content, $amount);
                        break;
                    case 2001: $walletData->update(['gem_paid_amount' => $walletData->gem_paid_amount + $content * $amount]);
                        break;
                    case 2002: $walletData->update(['coin_amount' => $walletData->coin_amount + $content * $amount]);
                        break;
                }
            }
        });
    }
}
