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
        Schema::create('mission_data', function (Blueprint $table) {
            $table->unsignedInteger('id')->comment('ミッションID');
            $table->unsignedInteger('mission_category')->comment('ミッションカテゴリ');
            $table->unsignedInteger('goal')->comment('ミッション達成条件');
            $table->text('description', 256)->comment('デフォルト値');
            $table->unsignedInteger('reward_category')->comment('報酬カテゴリ');
            $table->string('reward_value', 128)->comment('報酬額');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mission_data');
    }
};
