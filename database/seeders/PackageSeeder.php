<?php

namespace Database\Seeders;

use App\Enums\PackageStatus;
use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * The package price must never be hardcoded into business logic — it
     * lives here, in the database, and is copied onto each application at
     * the moment it's submitted (Phase 5) so a later price change never
     * rewrites history.
     */
    public function run(): void
    {
        Package::query()->firstOrCreate(
            ['code' => 'STD-1000'],
            [
                'name' => 'Standard Membership',
                'slug' => 'standard-membership',
                'description' => 'Our core membership package — a fixed monthly value, full access to your '
                    .'member dashboard, and support from your referral officer.',
                'price' => 1000.00,
                'duration' => 'Monthly',
                'status' => PackageStatus::Active,
                'sort_order' => 1,
            ]
        );
    }
}
