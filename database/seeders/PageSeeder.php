<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => 'This is the About Us page content.',
                'status' => 'Active',
                'deletable' => 0,
            ],
            [
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'content' => 'This is the Terms and Conditions page content.',
                'status' => 'Active',
                'deletable' => 0,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => 'This is the Privacy Policy page content.',
                'status' => 'Active',
                'deletable' => 0,
            ],
            [
                'title' => 'Refund Policy',
                'slug' => 'refund-policy',
                'content' => 'This is the Refund Policy page content.',
                'status' => 'Active',
                'deletable' => 0,
            ],
        ];


        Page::truncate();
        Page::insert($pages);
    }
}
