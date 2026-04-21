<?php
/**
 * @Author: Anwarul
 * @Date: 2026-01-15 11:32:54
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-15 12:33:04
 * @Description: Innova IT
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
     protected $fillable = [
        'site_title',
        'phone',
        'email',
        'address',
        'whatsapp_number',
        'logo',
        'footer_logo',
        'favicon',
        'facebook',
        'twitter',
        'youtube',
        'telegram',
        'instagram',
        'linkedin',
        'became_affiliate',
        'app_section_title',
        'app_section_description',
        'app_play_store_link',
        'app_app_store_link',
        'footer_description',
         'delivery_cost_in_dhaka',
         'delivery_cost_outside_dhaka',
        'badge_text',
        'home_title',
        'home_description',
        'home_banner',
        'button_text',
        'button_link',
        'button_text2',
         'button_link2',
         'about_top_title',
         'about_top_text',
        'about_top_banner',
        'about_bottom_title',
        'about_bottom_banner',
        'about_bottom_text',
        'about_featured_title',
        'about_featured_text',
        'about_featured_title2',
         'about_featured_text2',
    ];
}
