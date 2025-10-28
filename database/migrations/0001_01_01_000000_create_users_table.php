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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('manage_id')->comment('管理ID');
            $table->ulid('id')->comment('ユーザーID')->unique();
            $table->string('user_name', 16)->comment('アカウント名');
            $table->unsignedSmallInteger('max_stamina')->default(20)->comment('最大スタミナ');
            $table->unsignedSmallInteger('last_stamina')->default(20)->comment('最終更新時スタミナ');
            $table->dateTime('stamina_updated')->useCurrent()->comment('スタミナ更新日時');
            $table->dateTime('last_login')->useCurrent()->comment('最終ログイン');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
