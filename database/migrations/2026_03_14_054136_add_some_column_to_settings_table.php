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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('badge_text')->nullable()->after('site_title');
            $table->string('home_title')->nullable()->after('badge_text');
            $table->text('home_description')->nullable()->after('home_title');
            $table->string('home_banner')->nullable()->after('home_description');
            $table->string('button_text')->nullable()->after('home_banner');
            $table->string('button_link')->nullable()->after('button_text');
            $table->string('button_text2')->nullable()->after('button_link');
            $table->string('button_link2')->nullable()->after('button_text2');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('badge_text');
            $table->dropColumn('home_title');
            $table->dropColumn('home_description');
            $table->dropColumn('home_banner');
            $table->dropColumn('button_text');
            $table->dropColumn('button_link');
            $table->dropColumn('button_text2');
            $table->dropColumn('button_link2');
        });
    }
};
