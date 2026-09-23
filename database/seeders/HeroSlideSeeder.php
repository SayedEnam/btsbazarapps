<?php

namespace Database\Seeders;

use App\Enums\HeroSlideStatus;
use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Simple, transparent membership — one fixed monthly value.',
                'subtitle' => 'Join Monthly Bazar through a verified Marketing Officer, track your application in real time, and become an active member with full visibility into your account.',
                'button_text' => 'Get Started',
                'button_url' => '/register',
                'sort_order' => 1,
            ],
            [
                'title' => 'Backed by a real, verified referral network.',
                'subtitle' => 'Every Marketing Officer has a unique referral code and link — your relationship with them is recorded permanently, so you always know who to reach out to.',
                'button_text' => 'View Packages',
                'button_url' => '/packages',
                'sort_order' => 2,
            ],
            [
                'title' => 'Track every application from submission to approval.',
                'subtitle' => 'No hidden fees, no confusing paperwork. See exactly where your application stands, any time, from your own dashboard.',
                'button_text' => 'Learn How It Works',
                'button_url' => '/how-it-works',
                'sort_order' => 3,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::query()->firstOrCreate(
                ['title' => $slide['title']],
                $slide + ['status' => HeroSlideStatus::Active]
            );
        }
    }
}
