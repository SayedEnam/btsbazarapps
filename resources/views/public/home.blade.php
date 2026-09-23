@extends('layouts.public')

@section('title', 'Home')
@section('meta_description', 'Join Monthly Bazar — a transparent, fixed-price membership platform backed by a nationwide referral network.')

@section('content')
    @php
        use App\Models\Setting;
        $currency = Setting::get('currency_symbol', '৳');
    @endphp

    {{-- Hero --}}
    @if ($heroSlides->isNotEmpty())
        <section class="hero overflow-hidden position-relative">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
                <div class="carousel-inner">
                    @foreach ($heroSlides as $index => $slide)
                        <div
                            class="carousel-item {{ $index === 0 ? 'active' : '' }}"
                            @if ($slide->showsText())
                                @if ($slide->imageUrl())
                                    style="background-image: linear-gradient(135deg, rgba(15,61,62,.8) 0%, rgba(20,99,86,.65) 100%), url('{{ $slide->imageUrl() }}'); background-size: cover; background-position: center;"
                                @endif
                            @else
                                style="background-image: url('{{ $slide->imageUrl() }}'); background-size: cover; background-position: center;"
                            @endif
                        >
                            @if ($slide->showsText())
                                <div class="container py-5">
                                    <div class="row align-items-center py-4">
                                        <div class="col-lg-7">
                                            <span class="badge bg-white text-brand mb-3 px-3 py-2">Trusted Membership Platform</span>
                                            <h1 class="display-5 fw-bold mb-3">{{ $slide->title }}</h1>
                                            @if ($slide->subtitle)
                                                <p class="lead mb-4" style="opacity:.9;">{{ $slide->subtitle }}</p>
                                            @endif
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ $slide->button_url ?: route('register') }}" class="btn btn-light btn-lg text-brand fw-semibold" wire:navigate>{{ $slide->button_text ?: 'Get Started' }}</a>
                                                <a href="{{ route('packages') }}" class="btn btn-outline-light btn-lg" wire:navigate>View Packages</a>
                                            </div>
                                        </div>
                                        <div class="col-lg-5 d-none d-lg-block text-center">
                                            <i class="bi bi-shop" style="font-size: 14rem; opacity:.15;"></i>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @if ($heroSlides->count() > 1)
                <div class="d-flex justify-content-center align-items-center gap-3 pb-3 position-absolute bottom-0 start-50 translate-middle-x">
                    <!-- <button type="button" class="btn btn-outline-light hero-nav-btn rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <i class="bi bi-chevron-left"></i>
                        <span class="visually-hidden">Previous</span>
                    </button> -->
                    <div class="carousel-indicators hero-indicators position-relative m-0">
                        @foreach ($heroSlides as $index => $slide)
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" @if ($index === 0) aria-current="true" @endif aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                    <!-- <button type="button" class="btn btn-outline-light hero-nav-btn rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <i class="bi bi-chevron-right"></i>
                        <span class="visually-hidden">Next</span>
                    </button> -->
                </div>
            @endif
        </section>
    @else
        <section class="hero">
            <div class="container py-5">
                <div class="row align-items-center py-4">
                    <div class="col-lg-7">
                        <span class="badge bg-white text-brand mb-3 px-3 py-2">Trusted Membership Platform</span>
                        <h1 class="display-5 fw-bold mb-3">Simple, transparent membership — one fixed monthly value.</h1>
                        <p class="lead mb-4" style="opacity:.9;">
                            Join Monthly Bazar through a verified Marketing Officer, track your application in
                            real time, and become an active member with full visibility into your account —
                            every step of the way.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg text-brand fw-semibold" wire:navigate>Get Started</a>
                            <a href="{{ route('packages') }}" class="btn btn-outline-light btn-lg" wire:navigate>View Packages</a>
                        </div>
                    </div>
                    <div class="col-lg-5 d-none d-lg-block text-center">
                        <i class="bi bi-shop" style="font-size: 14rem; opacity:.15;"></i>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Company intro --}}
    <section class="section">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="section-title">Membership, made transparent</h2>
                    <p class="text-muted">
                        No hidden fees, no confusing paperwork. Every package price is published up front,
                        every application is tracked from submission to approval, and every member can see
                        exactly where they stand — from their own dashboard, any time.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Packages --}}
    @if ($packages->isNotEmpty())
        <section class="section bg-light">
            <div class="container">
                <div class="row justify-content-center text-center mb-5">
                    <div class="col-lg-7">
                        <h2 class="section-title">Choose your membership package</h2>
                        <p class="text-muted">Every package is fixed-price and published up front — pick the one that fits you.</p>
                    </div>
                </div>
                <div class="row g-4 justify-content-center">
                    @foreach ($packages as $index => $package)
                        <div class="col-md-6 col-lg-4">
                            <div class="card card-feature h-100 d-flex flex-column text-center {{ $index === 0 ? 'border border-2' : '' }}" @if ($index === 0) style="border-color: var(--brand) !important;" @endif>
                                @if ($package->imageUrl())
                                    <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}" class="w-100" style="height: 180px; object-fit: cover;">
                                @endif
                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    @if ($index === 0)
                                        <span class="badge bg-brand text-white mx-auto mb-3 px-3 py-2" style="width:fit-content;">Featured Package</span>
                                    @endif
                                    <h4 class="fw-bold mb-1">{{ $package->name }}</h4>
                                    <div class="display-6 fw-bold text-brand my-2">
                                        {{ $currency }}{{ number_format((float) $package->price) }}
                                        @if ($package->duration)
                                            <span class="fs-6 text-muted fw-normal">/ {{ strtolower($package->duration) }}</span>
                                        @endif
                                    </div>
                                    <p class="text-muted flex-grow-1">{{ $package->description }}</p>
                                    @auth
                                        @if (auth()->user()->hasRole(\App\Models\Role::CUSTOMER))
                                            <a href="{{ route('customer.apply') }}" class="btn btn-brand mt-3" wire:navigate>Apply Now</a>
                                        @endif
                                    @else
                                        <a href="{{ route('register') }}" class="btn btn-brand mt-3" wire:navigate>Apply Now</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="row justify-content-center text-center mb-5">
                    <div class="col-lg-7">
                        <h2 class="section-title">What our members say</h2>
                    </div>
                </div>
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000">
                    <div class="carousel-inner">
                        @foreach ($testimonials as $index => $testimonial)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <div class="card card-feature p-4 p-md-5 text-center">
                                            @if ($testimonial->photoUrl())
                                                <img src="{{ $testimonial->photoUrl() }}" alt="{{ $testimonial->customer_name }}" class="rounded-circle mx-auto mb-3" style="width:72px;height:72px;object-fit:cover;">
                                            @else
                                                <div class="icon-circle mx-auto mb-3"><i class="bi bi-person-fill"></i></div>
                                            @endif
                                            @if ($testimonial->rating)
                                                <div class="text-warning mb-2">
                                                    {{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}
                                                </div>
                                            @endif
                                            <p class="lead fst-italic text-muted mb-3">&ldquo;{{ $testimonial->quote }}&rdquo;</p>
                                            <div class="fw-bold">{{ $testimonial->customer_name }}</div>
                                            @if ($testimonial->role_or_company)
                                                <div class="text-muted small">{{ $testimonial->role_or_company }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($testimonials->count() > 1)
                        <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                            <button type="button" class="btn btn-outline-brand testimonial-nav-btn rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                                <i class="bi bi-chevron-left"></i>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <div class="carousel-indicators testimonial-indicators position-relative m-0">
                                @foreach ($testimonials as $index => $testimonial)
                                    <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" @if ($index === 0) aria-current="true" @endif aria-label="Testimonial {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-brand testimonial-nav-btn rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                                <i class="bi bi-chevron-right"></i>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- Package benefits --}}
    <section class="section bg-light">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-7">
                    <h2 class="section-title">What you get as a member</h2>
                </div>
            </div>
            <div class="row g-4">
                @foreach ([
                    ['icon' => 'bi-speedometer2', 'title' => 'Personal Dashboard', 'text' => 'See your membership status, package value, and application progress in one place.'],
                    ['icon' => 'bi-shield-check', 'title' => 'Verified Officers', 'text' => 'Every referral officer is registered and tracked — no anonymous middlemen.'],
                    ['icon' => 'bi-bell', 'title' => 'Real-Time Updates', 'text' => 'Get notified the moment your application status changes.'],
                    ['icon' => 'bi-lock', 'title' => 'Secure by Design', 'text' => 'Role-based access control keeps every member\'s data private.'],
                ] as $benefit)
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-feature p-4 text-center">
                            <div class="icon-circle mx-auto mb-3"><i class="bi {{ $benefit['icon'] }}"></i></div>
                            <h5 class="fw-semibold">{{ $benefit['title'] }}</h5>
                            <p class="text-muted small mb-0">{{ $benefit['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="section">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-7">
                    <h2 class="section-title">How it works</h2>
                    <p class="text-muted">From application to active membership in four simple steps.</p>
                </div>
            </div>
            <div class="row g-4">
                @foreach ([
                    ['step' => '1', 'title' => 'Apply', 'text' => 'Submit your application, optionally through a Marketing Officer\'s referral link.'],
                    ['step' => '2', 'title' => 'Review', 'text' => 'Your assigned officer or an admin reviews the application details.'],
                    ['step' => '3', 'title' => 'Approve', 'text' => 'Once approved, your membership is activated and recorded.'],
                    ['step' => '4', 'title' => 'Track', 'text' => 'View your status, package, and officer anytime from your dashboard.'],
                ] as $item)
                    <div class="col-md-6 col-lg-3">
                        <div class="text-center">
                            <div class="icon-circle mx-auto mb-3 fw-bold fs-4">{{ $item['step'] }}</div>
                            <h5 class="fw-semibold">{{ $item['title'] }}</h5>
                            <p class="text-muted small">{{ $item['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Referral system --}}
    <section class="section bg-light">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <h2 class="section-title mb-3">Backed by a real referral network</h2>
                    <p class="text-muted">
                        Every Marketing Officer has a unique referral code and link. When you register through
                        one, that relationship is recorded permanently — your officer stays connected to your
                        account for the life of your membership, so you always know who to reach out to.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-brand me-2"></i>Unique, verified referral codes</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-brand me-2"></i>Permanent referral relationships</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-brand me-2"></i>Full transparency into who referred you</li>
                    </ul>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-diagram-3" style="font-size: 10rem; color: var(--brand); opacity:.15;"></i>
                </div>
            </div>
        </div>
    </section>

    {{-- Why choose us --}}
    <section class="section">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-7">
                    <h2 class="section-title">Why choose Monthly Bazar</h2>
                </div>
            </div>
            <div class="row g-4">
                @foreach ([
                    ['icon' => 'bi-cash-coin', 'title' => 'Fixed, Published Pricing', 'text' => 'The price you see is the price on your application — it never changes retroactively.'],
                    ['icon' => 'bi-clipboard-check', 'title' => 'Full Audit Trail', 'text' => 'Every status change on your application is logged and visible.'],
                    ['icon' => 'bi-people', 'title' => 'Human Support', 'text' => 'A real Marketing Officer is there to help you through the process.'],
                ] as $reason)
                    <div class="col-md-4">
                        <div class="card card-feature p-4 text-center">
                            <div class="icon-circle mx-auto mb-3"><i class="bi {{ $reason['icon'] }}"></i></div>
                            <h5 class="fw-semibold">{{ $reason['title'] }}</h5>
                            <p class="text-muted small mb-0">{{ $reason['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="section bg-light">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-7">
                    <h2 class="section-title">Frequently asked questions</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        @foreach ([
                            ['q' => 'Is there an online payment option?', 'a' => 'Not yet. Payment for your package is arranged separately with your officer or the office, and recorded on your account once confirmed.'],
                            ['q' => 'Can the package price change after I apply?', 'a' => 'No. The price shown on your application is locked in at the moment you apply, even if the published price changes later.'],
                            ['q' => 'What if I don\'t have a referral officer?', 'a' => 'You can still apply directly — you\'ll simply be assigned an officer during the review process.'],
                            ['q' => 'How do I check my application status?', 'a' => 'Once you have an account, your dashboard shows your live application and membership status at all times.'],
                        ] as $i => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">
                                        {{ $faq['q'] }}
                                    </button>
                                </h2>
                                <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted">{{ $faq['a'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="section bg-brand text-white text-center">
        <div class="container">
            <h2 class="fw-bold mb-3">Ready to join Monthly Bazar?</h2>
            <p class="mb-4" style="opacity:.9;">Reach out today and a Marketing Officer will guide you through the application.</p>
            <a href="{{ route('contact') }}" class="btn btn-light btn-lg text-brand fw-semibold" wire:navigate>Contact Us</a>
        </div>
    </section>
@endsection
