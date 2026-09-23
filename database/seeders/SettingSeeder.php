<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * @var array<string, array{value: string, group: string}>
     */
    protected array $defaults = [
        'company_name' => ['value' => 'Monthly Bazar', 'group' => 'company'],
        'tagline' => ['value' => 'Simple, transparent monthly membership for everyone.', 'group' => 'company'],
        'phone' => ['value' => '+880 1700-000000', 'group' => 'company'],
        'email' => ['value' => 'info@monthlybazar.test', 'group' => 'company'],
        'address' => ['value' => 'House 12, Road 5, Dhanmondi, Dhaka-1209, Bangladesh', 'group' => 'company'],
        'google_map_embed' => ['value' => '', 'group' => 'company'],
        'facebook_url' => ['value' => 'https://facebook.com/', 'group' => 'social'],
        'youtube_url' => ['value' => 'https://youtube.com/', 'group' => 'social'],
        'whatsapp_number' => ['value' => '8801700000000', 'group' => 'social'],
        'footer_text' => ['value' => '© Monthly Bazar. All rights reserved.', 'group' => 'general'],
        'currency_symbol' => ['value' => '৳', 'group' => 'general'],
        'timezone' => ['value' => 'Asia/Dhaka', 'group' => 'general'],
        'logo_path' => ['value' => '', 'group' => 'branding'],
        'favicon_path' => ['value' => '', 'group' => 'branding'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->defaults as $key => $data) {
            Setting::query()->firstOrCreate(['key' => $key], [
                'value' => $data['value'],
                'group' => $data['group'],
            ]);
        }
    }
}
