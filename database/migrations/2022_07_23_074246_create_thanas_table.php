<?php
/**
 * @Author: Anwarul
 * @Date: 2025-11-05 14:21:09
 * @LastEditors: Anwarul
 * @LastEditTime: 2025-11-05 16:13:25
 * @Description: Innova IT
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thanas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bn_name')->nullable();
            $table->string('url')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('district_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('thanas');
    }
}
