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
            --sidebar-width: 260px;
            --sidebar-width-mini: 76px;
            --brand-dark: #0f3d3e;
            --brand: #146356;
            --brand-light: #1b8a6b;
        }
        body.layout-admin {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--brand-dark) 0%, var(--brand) 100%);
            z-index: 1040;
            transition: width .2s ease-in-out, transform .2s ease-in-out;
            overflow: hidden;
        }
        .sidebar-heading {
            color: rgba(255,255,255,.55);
            text-transform: uppercase;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .05em;
            white-space: nowrap;
        }
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,.85);
            border-radius: .5rem;
            padding: .5rem .75rem;
            font-size: .9rem;
            white-space: nowrap;
        }
        .admin-sidebar .nav-link:hover {
            background-color: rgba(255,255,255,.08);
            color: #fff;
        }
        .admin-sidebar .nav-link.active {
            background-color: rgba(255,255,255,.15);
            color: #fff;
            font-weight: 600;
        }
        .admin-sidebar .nav-link.disabled {
            color: rgba(255,255,255,.35);
            cursor: default;
        }
        .admin-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left .2s ease-in-out;
        }

        /* Desktop-only icon-rail collapse, toggled independently from the
           mobile off-canvas behavior below so the two never fight over what
           the same class means (see the historical bug note on the mobile
           media query — this keeps that fix intact). */
        body.sidebar-mini .admin-sidebar { width: var(--sidebar-width-mini); }
        body.sidebar-mini .admin-content { margin-left: var(--sidebar-width-mini); }
        body.sidebar-mini .sidebar-brand { justify-content: center; padding-left: 0; padding-right: 0; }
        body.sidebar-mini .sidebar-brand span,
        body.sidebar-mini .sidebar-heading,
        body.sidebar-mini .nav-link span { display: none; }
        body.sidebar-mini .admin-sidebar .nav-link { justify-content: center; padding: .6rem; }
        body.sidebar-mini .admin-sidebar .nav-link i { margin-right: 0 !important; font-size: 1.15rem; }
        body.sidebar-mini .sidebar-brand img,
        body.sidebar-mini .sidebar-brand i { margin-right: 0 !important; }
        .admin-navbar {
            height: 64px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--brand);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: .85rem;
        }
        .admin-main {
            flex: 1;
            padding: 1.5rem;
        }
        body.sidebar-collapsed .admin-sidebar { transform: translateX(-100%); }
        body.sidebar-collapsed .admin-content { margin-left: 0; }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .4);
            z-index: 1035;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-content { margin-left: 0; }
            body.sidebar-collapsed .admin-sidebar { transform: translateX(0); }
            body.sidebar-collapsed .sidebar-backdrop { display: block; }

            /* Mobile always uses the full-width off-canvas sidebar — the
               desktop-only mini/icon-rail mode never applies here, even if
               the mini preference was set on a wider screen earlier. */
            body.sidebar-mini .admin-sidebar { width: var(--sidebar-width); }
            body.sidebar-mini .admin-content { margin-left: 0; }
            body.sidebar-mini .sidebar-brand { justify-content: flex-start; padding-left: 1rem; padding-right: 1rem; }
            body.sidebar-mini .sidebar-brand span,
            body.sidebar-mini .sidebar-heading,
            body.sidebar-mini .nav-link span { display: inline; }
            body.sidebar-mini .admin-sidebar .nav-link { justify-content: flex-start; padding: .5rem .75rem; }
            body.sidebar-mini .admin-sidebar .nav-link i { margin-right: .5rem !important; font-size: 1rem; }
            body.sidebar-mini .sidebar-brand img,
            body.sidebar-mini .sidebar-brand i { margin-right: .5rem !important; }
        }

        /* Custom scrollbars — WebKit/Blink/Chromium and Firefox */
        .admin-sidebar nav {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.3) transparent;
        }
        .admin-sidebar nav::-webkit-scrollbar { width: 6px; }
        .admin-sidebar nav::-webkit-scrollbar-track { background: transparent; }
        .admin-sidebar nav::-webkit-scrollbar-thumb {
            background-color: rgba(255,255,255,.3);
            border-radius: 6px;
        }
        .admin-sidebar nav::-webkit-scrollbar-thumb:hover { background-color: rgba(255,255,255,.5); }

        html {
            scrollbar-width: thin;
            scrollbar-color: var(--brand) #e9ecef;
        }
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: #e9ecef; }
        ::-webkit-scrollbar-thumb {
            background-color: var(--brand);
            border-radius: 6px;
            border: 2px solid #e9ecef;
        }
        ::-webkit-scrollbar-thumb:hover { background-color: var(--brand-dark); }

        .card { border: none; border-radius: .75rem; box-shadow: 0 .125rem .5rem rgba(0,0,0,.06); }
        .btn-brand { background-color: var(--brand); border-color: var(--brand); color: #fff; }
        .btn-brand:hover { background-color: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .text-brand { color: var(--brand); }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 700; }
        @media (max-width: 575.98px) {
            .stat-card .stat-value { font-size: 1.35rem; }
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: .75rem;
            display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: #fff;
        }
    </style>

    @livewireStyles
</head>
<body class="layout-admin">

@include('partials.admin.sidebar')

<div id="sidebar-backdrop" class="sidebar-backdrop"></div>

<div class="admin-content">
    @include('partials.admin.navbar')

    <main class="admin-main">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{ $slot }}
    </main>
</div>

<x-toast />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    // Restore the desktop icon-rail preference. Only on a desktop-sized
    // viewport — mobile always starts with the sidebar fully closed
    // (its own separate `sidebar-collapsed` mechanism), regardless of what
    // was last chosen on a wider screen.
    if (window.innerWidth >= 992 && localStorage.getItem('sidebar-mini') === '1') {
        document.body.classList.add('sidebar-mini');
    }

    document.getElementById('sidebar-toggle')?.addEventListener('click', function () {
        if (window.innerWidth < 992) {
            document.body.classList.toggle('sidebar-collapsed');
        } else {
            var isMini = document.body.classList.toggle('sidebar-mini');
            localStorage.setItem('sidebar-mini', isMini ? '1' : '0');
        }
    });

    document.getElementById('sidebar-backdrop')?.addEventListener('click', function () {
        document.body.classList.remove('sidebar-collapsed');
    });
</script>

@livewireScripts
</body>
</html>
