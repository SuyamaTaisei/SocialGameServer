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
        Schema::create('shop_data', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->primary()->comment('商品ID');
            $table->unsignedInteger('shop_category')->comment('商品カテゴリ');
            $table->string('name', 128)->comment('商品名');
            $table->unsignedInteger('price')->default(0)->comment('商品価格');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_data');
    }
};
