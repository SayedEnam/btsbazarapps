@php $user = auth()->user(); @endphp

<nav class="admin-navbar d-flex align-items-center justify-content-between px-3">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-light border" type="button" id="sidebar-toggle" aria-label="Toggle sidebar">
            <i class="bi bi-list fs-5"></i>
        </button>

        @if (isset($title))
            <h1 class="h5 mb-0 ms-2 text-truncate">{{ $title }}</h1>
        @endif
    </div>

    <div class="d-flex align-items-center gap-2">
        @livewire('admin.notification-bell')

        <div class="dropdown">
            <button class="btn btn-light border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                <span class="d-none d-md-inline">{{ $user->name }}</span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><span class="dropdown-item-text text-muted small">{{ $user->email }}</span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.profile') }}" wire:navigate>
                        <i class="bi bi-person-circle me-2"></i>My Profile
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
