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
        Schema::create('gacha_periods', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary()->comment('ガチャID');
            $table->string('name', 128)->comment('ガチャ名');
            $table->unsignedInteger('single_cost')->comment('単発価格');
            $table->unsignedInteger('multi_cost')->comment('連発価格');
            $table->dateTime('start')->default('2000-01-01 00:00:00')->comment('期限開始日時');
            $table->dateTime('end')->default('2038-12-31 23:59:59')->comment('期限終了日時');
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
        Schema::dropIfExists('gacha_periods');
    }
};
