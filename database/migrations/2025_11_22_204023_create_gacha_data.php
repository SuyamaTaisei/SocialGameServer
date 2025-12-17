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
        Schema::create('gacha_data', function (Blueprint $table) {
            $table->unsignedInteger('gacha_id')->comment('ガチャID');
            $table->unsignedInteger('character_id')->comment('キャラクターID');
            $table->unsignedInteger('weight')->comment('重み');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary(['gacha_id', 'character_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gacha_data');
    }
};
