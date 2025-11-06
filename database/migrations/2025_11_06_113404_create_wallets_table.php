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
        Schema::create('wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->comment('管理ID');
            $table->unsignedInteger('coin_amount')->default(0)->comment('コイン');
            $table->unsignedInteger('gem_free_amount')->default(0)->comment('無償ジェム');
            $table->unsignedInteger('gem_paid_amount')->default(0)->comment('有償ジェム');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('manage_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};