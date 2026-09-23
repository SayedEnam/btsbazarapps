@extends('layouts.public')

@section('title', 'Contact Us')
@section('meta_description', 'Get in touch with Monthly Bazar.')

@section('content')
    @php
        use App\Models\Setting;
        $phone = Setting::get('phone', '');
        $email = Setting::get('email', '');
        $address = Setting::get('address', '');
        $whatsapp = Setting::get('whatsapp_number', '');
        $mapEmbed = Setting::get('google_map_embed', '');
    @endphp

    <section class="py-5 bg-light border-bottom">
        <div class="container text-center">
            <h1 class="display-6 fw-bold mb-2">Contact Us</h1>
            <p class="text-muted mb-0">We'd love to hear from you.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card card-feature p-4 mb-4">
                        <h5 class="fw-semibold mb-3">Get in Touch</h5>
                        <ul class="list-unstyled mb-0">
                            @if ($address)
                                <li class="d-flex gap-3 mb-3">
                                    <div class="icon-circle flex-shrink-0"><i class="bi bi-geo-alt"></i></div>
                                    <div class="text-muted small pt-2">{{ $address }}</div>
                                </li>
                            @endif
                            @if ($phone)
                                <li class="d-flex gap-3 mb-3">
                                    <div class="icon-circle flex-shrink-0"><i class="bi bi-telephone"></i></div>
                                    <div class="text-muted small pt-2">{{ $phone }}</div>
                                </li>
                            @endif
                            @if ($email)
                                <li class="d-flex gap-3 mb-3">
                                    <div class="icon-circle flex-shrink-0"><i class="bi bi-envelope"></i></div>
                                    <div class="text-muted small pt-2">{{ $email }}</div>
                                </li>
                            @endif
                            @if ($whatsapp)
                                <li class="d-flex gap-3">
                                    <div class="icon-circle flex-shrink-0"><i class="bi bi-whatsapp"></i></div>
                                    <div class="text-muted small pt-2">{{ $whatsapp }}</div>
                                </li>
                            @endif
                        </ul>
                    </div>

                    @if ($mapEmbed)
                        <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm">
                            <iframe src="{{ $mapEmbed }}" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    @endif
                </div>

                <div class="col-lg-8">
                    <div class="card card-feature p-4 p-md-5">
                        <h5 class="fw-semibold mb-3">Send Us a Message</h5>
                        @livewire('public.contact-form')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
