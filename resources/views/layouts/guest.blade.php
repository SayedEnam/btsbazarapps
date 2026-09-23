<?php
    $themePrimary = \App\Models\Setting::theme('theme_primary_color');
    $themePrimaryDark = \App\Models\Setting::theme('theme_primary_dark_color');
    $themePrimaryLight = \App\Models\Setting::theme('theme_primary_light_color');
    $themeBodySize = \App\Models\Setting::theme('theme_body_font_size');
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Monthly Bazar' }} - {{ config('app.name') }}</title>
    @if ($faviconUrl = \App\Models\Setting::faviconUrl())
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body.layout-guest {
            background: linear-gradient(135deg, {{ $themePrimaryDark }} 0%, {{ $themePrimary }} 45%, {{ $themePrimaryLight }} 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: {{ $themeBodySize }};
        }
        .auth-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .25);
            overflow: hidden;
        }
        .auth-brand {
            font-weight: 700;
            letter-spacing: .03em;
        }
        .btn-brand {
            background-color: {{ $themePrimary }};
            border-color: {{ $themePrimary }};
            color: #fff;
        }
        .btn-brand:hover {
            background-color: {{ $themePrimaryDark }};
            border-color: {{ $themePrimaryDark }};
            color: #fff;
        }
        .text-brand { color: {{ $themePrimary }}; }
    </style>

    @livewireStyles
</head>
<body class="layout-guest">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-10 {{ ($wide ?? false) ? 'col-md-9 col-lg-7' : 'col-md-6 col-lg-5' }}">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="text-decoration-none" wire:navigate>
                    @if ($logoUrl = \App\Models\Setting::logoUrl())
                        <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}" style="height: 40px;" class="mb-1">
                    @endif
                    <span class="auth-brand fs-3 text-white d-block">{{ \App\Models\Setting::get('company_name', config('app.name')) }}</span>
                </a>
            </div>

            @if (session('status'))
                <div class="alert alert-warning">{{ session('status') }}</div>
            @endif

            <div class="card auth-card">
                <div class="card-body p-4 p-md-5">
                    {{ $slot }}
                </div>
            </div>

            <p class="text-center text-white-50 small mt-4 mb-0">
                &copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</div>

@livewireScripts
</body>
</html>
