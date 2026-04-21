<?php
/**
 * @Author: Anwarul
 * @Date: 2026-01-05 17:21:38
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-22 17:31:11
 * @Description: Innova IT
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        // ... Some Truncate Query
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        $permissions = array(
            array('name' => 'dashboard','guard_name' => 'admin'),

            array('name' => 'users.index','guard_name' => 'admin'),
            array('name' => 'users.show','guard_name' => 'admin'),
            array('name' => 'users.edit','guard_name' => 'admin'),
            array('name' => 'users.create','guard_name' => 'admin'),
            array('name' => 'users.store','guard_name' => 'admin'),
            array('name' => 'users.update','guard_name' => 'admin',),
            array('name' => 'users.destroy','guard_name' => 'admin'),

            array('name' => 'admin.index','guard_name' => 'admin'),
            array('name' => 'admin.show','guard_name' => 'admin'),
            array('name' => 'admin.edit','guard_name' => 'admin'),
            array('name' => 'admin.create','guard_name' => 'admin'),
            array('name' => 'admin.store','guard_name' => 'admin'),
            array('name' => 'admin.update','guard_name' => 'admin',),
            array('name' => 'admin.destroy','guard_name' => 'admin'),

            array('name' => 'roles.index','guard_name' => 'admin'),
            array('name' => 'roles.edit','guard_name' => 'admin'),
            array('name' => 'roles.create','guard_name' => 'admin'),
            array('name' => 'roles.show','guard_name' => 'admin'),
            array('name' => 'roles.store','guard_name' => 'admin'),
            array('name' => 'roles.update','guard_name' => 'admin'),
            array('name' => 'roles.destroy','guard_name' => 'admin'),

            array('name' => 'permissions.index','guard_name' => 'admin'),
            array('name' => 'permissions.edit','guard_name' => 'admin'),
            array('name' => 'permissions.create','guard_name' => 'admin'),
            array('name' => 'permissions.show','guard_name' => 'admin'),
            array('name' => 'permissions.store','guard_name' => 'admin'),
            array('name' => 'permissions.update','guard_name' => 'admin'),
            array('name' => 'permissions.destroy','guard_name' => 'admin'),


            array('name' => 'setting.index','guard_name' => 'admin'),
            array('name' => 'setting.edit','guard_name' => 'admin'),
            array('name' => 'setting.create','guard_name' => 'admin'),
            array('name' => 'setting.show','guard_name' => 'admin'),
            array('name' => 'setting.store','guard_name' => 'admin'),
            array('name' => 'setting.update','guard_name' => 'admin'),
            array('name' => 'setting.destroy','guard_name' => 'admin'),

            array('name' => 'division.index','guard_name' => 'admin'),
            array('name' => 'division.store','guard_name' => 'admin'),
            array('name' => 'division.update','guard_name' => 'admin'),
            array('name' => 'division.destroy','guard_name' => 'admin'),

            array('name' => 'district.index','guard_name' => 'admin'),
            array('name' => 'district.store','guard_name' => 'admin'),
            array('name' => 'district.update','guard_name' => 'admin'),
            array('name' => 'district.destroy','guard_name' => 'admin'),

            array('name' => 'thana.index','guard_name' => 'admin'),
            array('name' => 'thana.store','guard_name' => 'admin'),
            array('name' => 'thana.update','guard_name' => 'admin'),
            array('name' => 'thana.destroy','guard_name' => 'admin'),

            array('name' => 'page.index','guard_name' => 'admin'),
            array('name' => 'page.create','guard_name' => 'admin'),
            array('name' => 'page.store','guard_name' => 'admin'),
            array('name' => 'page.show','guard_name' => 'admin'),
            array('name' => 'page.edit','guard_name' => 'admin'),
            array('name' => 'page.update','guard_name' => 'admin'),
            array('name' => 'page.destroy','guard_name' => 'admin'),
        );

        DB::table('permissions')->insert($permissions);
    }
}
