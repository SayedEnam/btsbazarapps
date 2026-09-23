<?php

namespace Database\Seeders;

use App\Enums\TestimonialStatus;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'customer_name' => 'Farida Yasmin',
                'role_or_company' => 'Member since 2024',
                'quote' => 'The application process was simple and transparent from start to finish. I always knew exactly where my application stood.',
                'rating' => 5,
                'sort_order' => 1,
            ],
            [
                'customer_name' => 'Kamal Hossain',
                'role_or_company' => 'Member since 2025',
                'quote' => 'My referral officer kept me updated every step of the way. No hidden fees, no confusion — exactly what was promised.',
                'rating' => 5,
                'sort_order' => 2,
            ],
            [
                'customer_name' => 'Shirin Akter',
                'role_or_company' => 'Member since 2023',
                'quote' => "I can check my membership status anytime from my own dashboard. It's the kind of transparency I haven't seen elsewhere.",
                'rating' => 4,
                'sort_order' => 3,
            ],
            [
                'customer_name' => 'Mizanur Rahman',
                'role_or_company' => 'Member since 2025',
                'quote' => 'Fixed pricing that never changes after you apply — that alone made the decision easy for me.',
                'rating' => 5,
                'sort_order' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::query()->firstOrCreate(
                ['customer_name' => $testimonial['customer_name']],
                $testimonial + ['status' => TestimonialStatus::Active]
            );
        }
    }
}
