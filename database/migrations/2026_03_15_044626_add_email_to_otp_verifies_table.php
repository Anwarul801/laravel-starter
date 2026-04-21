<?php
/**
 * @Author: Anwarul
 * @Date: 2026-03-15 15:51:53
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-04-01 16:27:26
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
        Schema::table('otp_verifies', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otp_verifies', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
