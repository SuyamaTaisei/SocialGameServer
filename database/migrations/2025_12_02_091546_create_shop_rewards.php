<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_rewards', function (Blueprint $table) {
            $table->increments('id')->comment('ショップ報酬ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('item_id')->comment('アイテムID');
            $table->unsignedInteger('amount')->default(1)->comment('数量');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->index('product_id');
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_rewards');
    }
};
