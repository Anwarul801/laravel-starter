<?php
/**
 * @Author: Anwarul
 * @Date: 2026-01-15 11:32:54
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-02-17 15:15:15
 * @Description: Innova IT
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('logo')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('favicon')->nullable();
            $table->integer('delivery_cost_in_dhaka')->default(60)->nullable();
            $table->integer('delivery_cost_outside_dhaka')->default(120)->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('telegram')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('app_section_title')->nullable();
            $table->text('app_section_description')->nullable();
            $table->string('app_play_store_link')->nullable();
            $table->string('app_app_store_link')->nullable();
            $table->text('footer_description')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
