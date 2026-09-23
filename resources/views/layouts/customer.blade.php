<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name') }}</title>
    @if ($faviconUrl = \App\Models\Setting::faviconUrl())
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-dark: #0f3d3e;
            --brand: #146356;
            --brand-light: #1b8a6b;
        }
        body.layout-customer {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        .customer-navbar {
            background: linear-gradient(90deg, var(--brand-dark) 0%, var(--brand) 100%);
        }
        .customer-navbar .nav-link {
            color: rgba(255,255,255,.85);
            font-weight: 500;
        }
        .customer-navbar .nav-link.active,
        .customer-navbar .nav-link:hover { color: #fff; }
        .customer-main { padding: 2rem 0; }
        .card { border: none; border-radius: .75rem; box-shadow: 0 .125rem .5rem rgba(0,0,0,.06); }
        .btn-brand { background-color: var(--brand); border-color: var(--brand); color: #fff; }
        .btn-brand:hover { background-color: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .text-brand { color: var(--brand); }
        .avatar-circle {
            width: 32px; height: 32px; border-radius: 50%;
            background-color: rgba(255,255,255,.15);
            color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: .85rem;
        }
        .stat-card .stat-value { font-size: 1.5rem; font-weight: 700; }
        .stat-card .stat-icon {
            width: 44px; height: 44px; border-radius: .65rem;
            display: flex; align-items: center; justify-content: center; font-size: 1.15rem; color: #fff;
        }
    </style>

    @livewireStyles
</head>
<body class="layout-customer">

<nav class="navbar navbar-expand-lg customer-navbar">
    <div class="container">
        <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2" href="{{ route('customer.dashboard') }}" wire:navigate>
            @if ($logoUrl = \App\Models\Setting::logoUrl())
                <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}" style="height: 28px;">
            @else
                <i class="bi bi-shop"></i>
            @endif
            {{ \App\Models\Setting::get('company_name', config('app.name')) }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#customerNav" style="border-color: rgba(255,255,255,.4);">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="customerNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}" wire:navigate>Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}" href="{{ route('customer.profile') }}" wire:navigate>My Profile</a>
                </li>
                <li class="nav-item dropdown ms-lg-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                        <span class="avatar-circle">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="customer-main">
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{ $slot }}
    </div>
</main>

<x-toast />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@livewireScripts
</body>
</html>
