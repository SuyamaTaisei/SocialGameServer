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
        Schema::table('shop_data', function (Blueprint $table) {
            $table->unsignedInteger('paid_currency')->default(0)->comment('支払い有償ジェム')->after('name');
            $table->unsignedInteger('free_currency')->default(0)->comment('支払い無償ジェム')->after('paid_currency');
            $table->unsignedInteger('coin_currency')->default(0)->comment('支払いコイン')->after('free_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_data', function (Blueprint $table) {
            $table->dropColumn(['paid_currency', 'free_currency', 'coin_currency']);
        });
    }
};
