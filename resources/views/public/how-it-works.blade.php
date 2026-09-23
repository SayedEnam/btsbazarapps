@extends('layouts.public')

@section('title', 'How It Works')
@section('meta_description', 'How to apply, get approved, and become an active Monthly Bazar member.')

@section('content')
    <section class="py-5 bg-light border-bottom">
        <div class="container text-center">
            <h1 class="display-6 fw-bold mb-2">How It Works</h1>
            <p class="text-muted mb-0">From application to active membership.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    @foreach ([
                        [
                            'step' => '1',
                            'title' => 'Choose a package and apply',
                            'text' => 'Browse our published packages and submit your application — directly, or through a Marketing Officer\'s referral link. If you use a referral link, that officer is permanently linked to your account.',
                            'icon' => 'bi-clipboard-plus',
                        ],
                        [
                            'step' => '2',
                            'title' => 'Your application is reviewed',
                            'text' => 'Your assigned officer, or an administrator, reviews your details. Every status change — pending, under review, approved, or rejected — is recorded with a timestamp.',
                            'icon' => 'bi-search',
                        ],
                        [
                            'step' => '3',
                            'title' => 'Approval activates your membership',
                            'text' => 'Once approved, your account status becomes Active and your membership package is locked in at the price you originally applied for.',
                            'icon' => 'bi-patch-check',
                        ],
                        [
                            'step' => '4',
                            'title' => 'Arrange payment',
                            'text' => 'Monthly Bazar does not process online payments through the platform. Payment is arranged separately with your officer or the office and recorded on your account once confirmed.',
                            'icon' => 'bi-wallet2',
                        ],
                        [
                            'step' => '5',
                            'title' => 'Track everything from your dashboard',
                            'text' => 'Once logged in, your dashboard always shows your current membership status, package, application history, and referral officer.',
                            'icon' => 'bi-speedometer2',
                        ],
                    ] as $item)
                        <div class="d-flex gap-4 mb-5">
                            <div class="flex-shrink-0">
                                <div class="icon-circle" style="width:64px;height:64px;font-size:1.75rem;">
                                    <i class="bi {{ $item['icon'] }}"></i>
                                </div>
                            </div>
                            <div>
                                <div class="text-brand small fw-semibold mb-1">STEP {{ $item['step'] }}</div>
                                <h4 class="fw-bold">{{ $item['title'] }}</h4>
                                <p class="text-muted mb-0">{{ $item['text'] }}</p>
                            </div>
                        </div>
                    @endforeach

                    <div class="text-center mt-5">
                        <a href="{{ route('register') }}" class="btn btn-brand btn-lg" wire:navigate>Get Started</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
