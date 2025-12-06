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
        Schema::table('gacha_periods', function (Blueprint $table) {
            $table->unsignedInteger('single_count')->after('name')->comment('単発回数');
            $table->unsignedInteger('multi_count')->after('single_count')->comment('連発回数');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gacha_periods', function (Blueprint $table) {
            $table->dropColumn(['single_count', 'multi_count']);
        });
    }
};
