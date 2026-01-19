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
        Schema::create('present_instances', function (Blueprint $table) {
            $table->increments('id')->comment('インスタンスID');
            $table->unsignedBigInteger('manage_id')->comment('管理ID');
            $table->unsignedInteger('present_category')->comment('プレゼントカテゴリ');
            $table->string('present_name', 128)->comment('プレゼント名');
            $table->unsignedInteger('content')->comment('プレゼント内容');
            $table->unsignedInteger('amount')->default(1)->comment('プレゼント数量');
            $table->boolean('received')->default(0)->comment('受け取り済みかどうか');
            $table->dateTime('period')->comment('受取期限');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->index('manage_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('present_instances');
    }
};
