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
        Schema::create('character_instances', function (Blueprint $table) {
            $table->increments('id')->comment('インスタンスID');
            $table->unsignedBigInteger('manage_id')->comment('管理ID');
            $table->unsignedInteger('character_id')->comment('キャラクターID');
            $table->unsignedTinyInteger('level')->default(1)->comment('現在レベル');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->unique('manage_id');
            $table->unique('character_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_instances');
    }
};
