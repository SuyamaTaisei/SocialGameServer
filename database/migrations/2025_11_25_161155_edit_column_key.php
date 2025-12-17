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
        Schema::table('item_instances', function (Blueprint $table) {
            $table->dropUnique(['manage_id']);
            $table->dropUnique(['item_id']);
            $table->index('manage_id');
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_instances', function (Blueprint $table) {
            $table->dropIndex(['manage_id']);
            $table->dropIndex(['item_id']);
            $table->unique('manage_id');
            $table->unique('item_id');
        });
    }
};
