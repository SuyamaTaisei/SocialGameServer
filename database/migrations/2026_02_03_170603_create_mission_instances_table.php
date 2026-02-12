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
        Schema::create('mission_instances', function (Blueprint $table) {
            $table->increments('id')->comment('インスタンスID');
            $table->unsignedBigInteger('manage_id')->comment('管理ID');
            $table->unsignedInteger('mission_id')->comment('ミッションID');
            $table->unsignedInteger('mission_category')->comment('ミッションカテゴリ');
            $table->unsignedInteger('progress')->default(0)->comment('ミッション進捗度合');
            $table->boolean('cleared')->default(0)->comment('クリア済みかどうか');
            $table->boolean('received')->default(0)->comment('受け取り済みかどうか');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->index('manage_id');
            $table->index('mission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mission_instances');
    }
};
