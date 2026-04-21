<?php
/**
 * @Author: Anwarul
 * @Date: 2025-11-17 14:53:56
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-22 15:37:37
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 191)->nullable()->unique();
            $table->string('phone', 50)->nullable()->unique();
            $table->string('status')->default('Active')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('password')->nullable();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('profession')->nullable();
            $table->string('profile_image')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
           $table->id(); // makes 'id' BIGINT AUTO_INCREMENT PRIMARY KEY
            $table->string('email', 191);
            $table->string('token', 255);
            $table->timestamp('created_at')->nullable();
            $table->index('email'); // optional if you query by email
        });

        Schema::create('sessions', function (Blueprint $table) {
             $table->string('id', 191)->primary(); // 191*4=764 bytes < 767 limit
            $table->foreignId('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
