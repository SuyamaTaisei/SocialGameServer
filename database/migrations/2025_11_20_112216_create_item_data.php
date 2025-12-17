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
        Schema::create('item_data', function (Blueprint $table) {
            $table->unsignedInteger('id')->comment('アイテムID');
            $table->unsignedInteger('rarity_id')->comment('アイテムレアリティID');
            $table->unsignedInteger('item_category')->comment('アイテムカテゴリ');
            $table->string('name', 128)->comment('アイテム名');
            $table->text('description', 256)->comment('アイテム説明');
            $table->unsignedInteger('value')->default(0)->comment('アイテム効果値');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('id');
            $table->index('rarity_id');
            $table->index('item_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_data');
    }
};
