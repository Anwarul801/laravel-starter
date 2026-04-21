<?php
/**
 * @Author: Anwarul
 * @Date: 2026-01-27 17:24:19
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-27 17:24:40
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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            // Device Identification
            $table->string('device_token')->unique();
            $table->string('device_name')->nullable();   // e.g. Chrome on Windows, Samsung Galaxy S23
            $table->string('device_type')->nullable();   // web, android, ios
            $table->string('platform')->nullable();      // windows, mac, android, ios
            // Security Info
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};
