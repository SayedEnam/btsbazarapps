<?php
    use App\Models\Setting;

    $companyName = Setting::get('company_name', config('app.name'));
    $tagline = Setting::get('tagline', '');
    $phone = Setting::get('phone', '');
    $email = Setting::get('email', '');
    $address = Setting::get('address', '');
    $facebookUrl = Setting::get('facebook_url', '');
    $youtubeUrl = Setting::get('youtube_url', '');
    $whatsappNumber = Setting::get('whatsapp_number', '');
    $footerText = Setting::get('footer_text', '');
    $logoUrl = Setting::logoUrl();
    $faviconUrl = Setting::faviconUrl();

    $themePrimary = Setting::theme('theme_primary_color');
    $themePrimaryDark = Setting::theme('theme_primary_dark_color');
    $themePrimaryLight = Setting::theme('theme_primary_light_color');
    $themeFooterBg = Setting::theme('theme_footer_bg_color');
    $themeFooterText = Setting::theme('theme_footer_text_color');
    $themeH1 = Setting::theme('theme_h1_size');
    $themeH2 = Setting::theme('theme_h2_size');
    $themeH3 = Setting::theme('theme_h3_size');
    $themeH4 = Setting::theme('theme_h4_size');
    $themeH5 = Setting::theme('theme_h5_size');
    $themeH6 = Setting::theme('theme_h6_size');
    $themeBodySize = Setting::theme('theme_body_font_size');
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', $tagline)">
    <title>@yield('title', 'Home') - {{ $companyName }}</title>
    @if ($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-dark: {{ $themePrimaryDark }};
            --brand: {{ $themePrimary }};
            --brand-light: {{ $themePrimaryLight }};
        }
        body.layout-public {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #212529;
            font-size: {{ $themeBodySize }};
        }
        h1 { font-size: {{ $themeH1 }} !important; }
        h2 { font-size: {{ $themeH2 }} !important; }
        h3 { font-size: {{ $themeH3 }} !important; }
        h4 { font-size: {{ $themeH4 }} !important; }
        h5 { font-size: {{ $themeH5 }} !important; }
        h6 { font-size: {{ $themeH6 }} !important; }
        p { font-size: {{ $themeBodySize }}; }
        .navbar-brand { font-weight: 700; color: var(--brand) !important; }
        .site-navbar .nav-link {
            font-weight: 500;
            color: #33403c;
        }
        .site-navbar .nav-link.active,
        .site-navbar .nav-link:hover { color: var(--brand); }
        .btn-brand { background-color: var(--brand); border-color: var(--brand); color: #fff; }
        .btn-brand:hover { background-color: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .btn-outline-brand { border-color: var(--brand); color: var(--brand); }
        .btn-outline-brand:hover { background-color: var(--brand); color: #fff; }
        .text-brand { color: var(--brand); }
        .bg-brand { background-color: var(--brand); }
        .bg-brand-dark { background-color: var(--brand-dark); }

        .hero {
            background: linear-gradient(135deg, var(--brand-dark) 0%, var(--brand) 55%, var(--brand-light) 100%);
            color: #fff;
        }
        .hero .carousel-item { min-height: 445px; }
        .hero .carousel-item.active { display: flex; align-items: center; }
        .hero .carousel-item > .container { width: 100%; }
        .hero-nav-btn { width: 42px; height: 42px; }
        .hero-indicators [data-bs-target] {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #7700ff;
            border: none;
        }
        @media (max-width: 575.98px) {
            .hero .carousel-item { 
                min-height: 200px;
              }
        }
        .section { padding: 4rem 0; }
        .section-title { font-weight: 700; }
        .card-feature {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 .25rem 1rem rgba(0,0,0,.06);
            height: 100%;
            overflow: hidden;
        }
        .icon-circle {
            width: 56px; height: 56px; border-radius: 50%;
            background-color: rgba(20,99,86,.1);
            color: var(--brand);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
        }
        .testimonial-nav-btn { width: 42px; height: 42px; }
        .testimonial-indicators [data-bs-target] {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--brand);
            border: none;
        }
        footer { background-color: {{ $themeFooterBg }}; color: {{ $themeFooterText }}; }
        footer a { color: {{ $themeFooterText }}; text-decoration: none; }
        footer a:hover { color: #fff; }
        .social-icon {
            width: 36px; height: 36px; border-radius: 50%;
            background-color: rgba(255,255,255,.1);
            display: inline-flex; align-items: center; justify-content: center;
            color: #fff;
        }
        .social-icon:hover { background-color: var(--brand-light); }
    </style>

    @livewireStyles
</head>
<body class="layout-public">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top site-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}" wire:navigate>
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $companyName }}" style="height: 32px;">
            @else
                <i class="bi bi-shop fs-4"></i>
            @endif
            <span>{{ $companyName }}</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav" aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="siteNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" wire:navigate>Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}" wire:navigate>About</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('packages') ? 'active' : '' }}" href="{{ route('packages') }}" wire:navigate>Packages</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('how-it-works') ? 'active' : '' }}" href="{{ route('how-it-works') }}" wire:navigate>How It Works</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}" wire:navigate>Contact</a></li>
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0 d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-brand btn-sm px-3" wire:navigate>Login</a>
                    <a href="{{ route('register') }}" class="btn btn-brand btn-sm px-3" wire:navigate>Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<footer class="pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <h5 class="text-white d-flex align-items-center gap-2">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $companyName }}" style="height: 24px;">
                    @else
                        <i class="bi bi-shop"></i>
                    @endif
                    {{ $companyName }}
                </h5>
                <p class="small mb-3">{{ $tagline }}</p>
                <div class="d-flex gap-2">
                    @if ($facebookUrl)
                        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="social-icon"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if ($youtubeUrl)
                        <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener" class="social-icon"><i class="bi bi-youtube"></i></a>
                    @endif
                    @if ($whatsappNumber)
                        <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener" class="social-icon"><i class="bi bi-whatsapp"></i></a>
                    @endif
                </div>
            </div>

            <div class="col-6 col-md-2">
                <h6 class="text-white mb-3">Quick Links</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('about') }}" wire:navigate>About Us</a></li>
                    <li class="mb-2"><a href="{{ route('packages') }}" wire:navigate>Packages</a></li>
                    <li class="mb-2"><a href="{{ route('how-it-works') }}" wire:navigate>How It Works</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}" wire:navigate>Contact</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-2">
                <h6 class="text-white mb-3">Legal</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('terms') }}" wire:navigate>Terms &amp; Conditions</a></li>
                    <li class="mb-2"><a href="{{ route('privacy') }}" wire:navigate>Privacy Policy</a></li>
                </ul>
            </div>

            <div class="col-12 col-md-4">
                <h6 class="text-white mb-3">Contact</h6>
                <ul class="list-unstyled small">
                    @if ($address)
                        <li class="mb-2 d-flex gap-2"><i class="bi bi-geo-alt mt-1"></i><span>{{ $address }}</span></li>
                    @endif
                    @if ($phone)
                        <li class="mb-2 d-flex gap-2"><i class="bi bi-telephone mt-1"></i><span>{{ $phone }}</span></li>
                    @endif
                    @if ($email)
                        <li class="mb-2 d-flex gap-2"><i class="bi bi-envelope mt-1"></i><span>{{ $email }}</span></li>
                    @endif
                </ul>
            </div>
        </div>

        <hr class="border-white-50 mt-4 mb-3" style="opacity:.15;">

        <p class="small text-center mb-0" style="opacity:.75;">
            {{ $footerText ?: '© '.now()->year.' '.$companyName.'. All rights reserved.' }}
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@livewireScripts
</body>
</html>
