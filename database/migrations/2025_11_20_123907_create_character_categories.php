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
        Schema::create('character_categories', function (Blueprint $table) {
            $table->unsignedInteger('category')->comment('キャラクターカテゴリ');
            $table->string('name', 128)->comment('カテゴリ名');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_categories');
    }
};
