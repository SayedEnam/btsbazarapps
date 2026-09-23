<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::query()->firstOrCreate(
            ['slug' => Page::ABOUT],
            [
                'title' => 'About Us',
                'meta_description' => 'Learn about Monthly Bazar, our mission, and how our membership platform works.',
                'content' => <<<'HTML'
                    <p>Monthly Bazar is a membership platform that makes it simple and transparent for
                    people to join, pay a fixed monthly package value, and become part of a growing
                    community — supported by a dedicated network of Marketing Officers across the country.</p>

                    <p>We started with one goal: remove the confusion and paperwork that usually comes
                    with membership programs. Every package price is fixed and published up front, every
                    application is tracked from submission to approval, and every member can see exactly
                    where they stand at any time from their own dashboard.</p>

                    <h3>Our Mission</h3>
                    <p>To build a transparent, technology-driven membership system that is easy to join,
                    easy to manage, and fair to everyone — members and officers alike.</p>

                    <h3>What Makes Us Different</h3>
                    <ul>
                        <li>Fixed, published package pricing — no hidden fees.</li>
                        <li>A referral network of verified Marketing Officers.</li>
                        <li>Full visibility into your application and membership status.</li>
                        <li>A modern, secure platform built for growth.</li>
                    </ul>
                    HTML,
            ]
        );

        Page::query()->firstOrCreate(
            ['slug' => Page::TERMS],
            [
                'title' => 'Terms & Conditions',
                'meta_description' => 'The terms and conditions governing membership with Monthly Bazar.',
                'content' => <<<'HTML'
                    <p>These Terms &amp; Conditions govern your use of Monthly Bazar and your membership
                    application. By registering or applying for a package, you agree to the terms below.</p>

                    <h3>1. Membership Application</h3>
                    <p>Submitting an application does not guarantee approval. Applications are reviewed by
                    an authorized Marketing Officer or Administrator and may be approved, rejected, or
                    returned for more information.</p>

                    <h3>2. Package Pricing</h3>
                    <p>The package value shown on your application is fixed at the time you apply and will
                    not change even if the published price changes afterward.</p>

                    <h3>3. Payment</h3>
                    <p>Monthly Bazar does not process online payments through this platform. Payment
                    arrangements are handled separately and are recorded in your account once confirmed.</p>

                    <h3>4. Referral Relationships</h3>
                    <p>If you registered through a referral link, that referral relationship is permanent
                    and will not change, regardless of future activity.</p>

                    <h3>5. Account Status</h3>
                    <p>Monthly Bazar reserves the right to suspend or deactivate an account that violates
                    these terms or provides false information during registration.</p>

                    <h3>6. Changes to These Terms</h3>
                    <p>We may update these terms from time to time. Continued use of the platform after an
                    update constitutes acceptance of the revised terms.</p>
                    HTML,
            ]
        );

        Page::query()->firstOrCreate(
            ['slug' => Page::PRIVACY],
            [
                'title' => 'Privacy Policy',
                'meta_description' => 'How Monthly Bazar collects, uses, and protects your personal information.',
                'content' => <<<'HTML'
                    <p>This Privacy Policy explains how Monthly Bazar collects, uses, and protects the
                    personal information you provide when you register, apply for a package, or contact us.</p>

                    <h3>Information We Collect</h3>
                    <p>Name, contact details, address, national ID number, and other information you submit
                    through registration, application, or contact forms.</p>

                    <h3>How We Use Your Information</h3>
                    <ul>
                        <li>To process your membership application and manage your account.</li>
                        <li>To connect your account with the correct Marketing Officer, when applicable.</li>
                        <li>To respond to inquiries submitted through our Contact page.</li>
                        <li>To maintain accurate records for administrative and reporting purposes.</li>
                    </ul>

                    <h3>How We Protect Your Information</h3>
                    <p>Access to member data is restricted by role-based permissions, and all administrative
                    actions are logged for accountability.</p>

                    <h3>Your Rights</h3>
                    <p>You may contact us at any time to review, correct, or request removal of your
                    personal information, subject to our record-keeping obligations.</p>
                    HTML,
            ]
        );
    }
}
