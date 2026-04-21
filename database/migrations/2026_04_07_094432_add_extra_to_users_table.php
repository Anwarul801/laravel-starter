<?php
/**
 * @Author: Anwarul
 * @Date: 2026-04-07 15:44:32
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-04-07 15:44:40
 * @Description: Innova IT
 */

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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_affiliate')->default(0)->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_affiliate');
        });
    }
};
