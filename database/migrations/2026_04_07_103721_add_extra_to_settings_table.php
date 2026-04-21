<?php
/**
 * @Author: Anwarul
 * @Date: 2026-04-07 16:37:21
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-04-07 16:37:31
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
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('became_affiliate', 10, 2)->default(0)->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('became_affiliate');
        });
    }
};
