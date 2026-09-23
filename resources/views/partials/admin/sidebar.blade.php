@php
    $user = auth()->user();
    $sidebarLogoUrl = \App\Models\Setting::logoUrl();
    $sidebarCompanyName = \App\Models\Setting::get('company_name', config('app.name'));

    // Each item: label, route name (null = not built yet), icon.
    $sections = [
        [
            'title' => null,
            'items' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'bi-speedometer2'],
            ],
        ],
        [
            'title' => 'Website',
            'items' => [
                ['label' => 'Pages', 'route' => 'admin.pages.index', 'icon' => 'bi-file-earmark-richtext'],
                ['label' => 'Hero Slides', 'route' => 'admin.hero-slides.index', 'icon' => 'bi-images'],
                ['label' => 'Testimonials', 'route' => 'admin.testimonials.index', 'icon' => 'bi-chat-quote'],
                ['label' => 'Settings', 'route' => 'admin.settings', 'icon' => 'bi-gear'],
            ],
        ],
        [
            'title' => 'Users & Access',
            'items' => [
                ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'bi-people'],
                ['label' => 'Roles', 'route' => 'admin.roles.index', 'icon' => 'bi-shield-lock'],
                ['label' => 'Permissions', 'route' => 'admin.permissions.index', 'icon' => 'bi-key'],
            ],
        ],
        [
            'title' => 'Members',
            'items' => [
                ['label' => 'Customers', 'route' => 'admin.customers.index', 'icon' => 'bi-person-badge'],
                ['label' => 'Applications', 'route' => 'admin.applications.index', 'icon' => 'bi-file-earmark-text'],
                ['label' => 'Packages', 'route' => 'admin.packages.index', 'icon' => 'bi-box-seam'],
                ['label' => 'Referral Management', 'route' => 'admin.referrals.index', 'icon' => 'bi-diagram-3'],
            ],
        ],
        [
            'title' => 'Officers',
            'items' => [
                ['label' => 'Officers', 'route' => 'admin.officers.index', 'icon' => 'bi-person-workspace'],
                ['label' => 'Departments', 'route' => 'admin.departments.index', 'icon' => 'bi-building'],
                ['label' => 'Designations', 'route' => 'admin.designations.index', 'icon' => 'bi-award'],
                ['label' => 'Salary Profiles', 'route' => 'admin.salary-profiles.index', 'icon' => 'bi-cash-stack'],
            ],
        ],
        [
            'title' => 'Accounts',
            'items' => [
                ['label' => 'Accounts Dashboard', 'route' => 'admin.accounts.index', 'icon' => 'bi-graph-up'],
                ['label' => 'Payroll', 'route' => 'admin.payroll.index', 'icon' => 'bi-wallet2'],
                ['label' => 'Salary Payments', 'route' => 'admin.salary-payments.index', 'icon' => 'bi-credit-card'],
                ['label' => 'Expenses', 'route' => 'admin.expenses.index', 'icon' => 'bi-receipt'],
                ['label' => 'Expense Categories', 'route' => 'admin.expense-categories.index', 'icon' => 'bi-tags'],
                ['label' => 'Financial Transactions', 'route' => 'admin.financial-transactions.index', 'icon' => 'bi-graph-up-arrow'],
            ],
        ],
        [
            'title' => 'Reports',
            'items' => [
                ['label' => 'Reports', 'route' => 'admin.reports.index', 'icon' => 'bi-bar-chart-line'],
            ],
        ],
        [
            'title' => 'System',
            'items' => [
                ['label' => 'Notifications', 'route' => 'admin.notifications.index', 'icon' => 'bi-bell'],
                ['label' => 'Activity Logs', 'route' => 'admin.activity-logs.index', 'icon' => 'bi-clock-history'],
                ['label' => 'QA Checklist', 'route' => 'admin.qa-checklist.index', 'icon' => 'bi-camera'],
            ],
        ],
    ];
@endphp

<aside id="admin-sidebar" class="admin-sidebar d-flex flex-column">
    <div class="sidebar-brand d-flex align-items-center px-3 py-3">
        @if ($sidebarLogoUrl)
            <img src="{{ $sidebarLogoUrl }}" alt="{{ $sidebarCompanyName }}" style="height: 28px;" class="me-2">
        @else
            <i class="bi bi-shop fs-4 text-white me-2"></i>
        @endif
        <span class="fw-bold text-white fs-5">{{ $sidebarCompanyName }}</span>
    </div>

    <nav class="flex-grow-1 overflow-auto px-2 pb-3">
        @foreach ($sections as $section)
            @if ($section['title'])
                <div class="sidebar-heading px-2 mt-3 mb-1">{{ $section['title'] }}</div>
            @endif

            <ul class="nav flex-column">
                @foreach ($section['items'] as $item)
                    <li class="nav-item">
                        @if ($item['route'] && Route::has($item['route']))
                            <a href="{{ route($item['route']) }}" wire:navigate title="{{ $item['label'] }}"
                               class="nav-link d-flex align-items-center {{ request()->routeIs($item['route'].'*') ? 'active' : '' }}">
                                <i class="bi {{ $item['icon'] }} me-2"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @else
                            <span class="nav-link d-flex align-items-center justify-content-between disabled" title="{{ $item['label'] }}">
                                <span><i class="bi {{ $item['icon'] }} me-2"></i>{{ $item['label'] }}</span>
                                <span class="badge text-bg-secondary small">Soon</span>
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endforeach
    </nav>
</aside>
