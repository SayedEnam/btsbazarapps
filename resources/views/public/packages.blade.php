@extends('layouts.public')

@section('title', 'Packages')
@section('meta_description', 'View Monthly Bazar membership packages and pricing.')

@section('content')
    @php
        use App\Models\Setting;
        $currency = Setting::get('currency_symbol', '৳');
    @endphp

    <section class="py-5 bg-light border-bottom">
        <div class="container text-center">
            <h1 class="display-6 fw-bold mb-2">Membership Packages</h1>
            <p class="text-muted mb-0">Fixed, published pricing — no hidden fees.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if ($packages->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-box-seam display-4 d-block mb-3"></i>
                    No packages are available right now. Please check back soon.
                </div>
            @else
                <div class="row g-4 justify-content-center">
                    @foreach ($packages as $package)
                        <div class="col-md-6 col-lg-4">
                            <div class="card card-feature h-100 d-flex flex-column">
                                @if ($package->imageUrl())
                                    <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}" class="w-100" style="height: 180px; object-fit: cover;">
                                @endif
                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <h4 class="fw-bold">{{ $package->name }}</h4>
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
            @endif
        </div>
    </section>
@endsection
