<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalMessages = 25;

        for ($i = 1; $i <= $totalMessages; $i++) {
            DB::table('contact_messages')->insert([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'phone' => '01' . rand(3,9) . rand(10000000, 99999999),
                'subject' => 'Test Message Subject ' . $i,
                'message' => 'This is a dummy contact message generated for testing purpose. Message no: ' . $i,
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
