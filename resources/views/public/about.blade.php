@extends('layouts.public')

@section('title', $page->title)
@section('meta_description', $page->meta_description ?? '')

@section('content')
    <section class="hero py-5">
        <div class="container text-center py-4">
            <h1 class="display-6 fw-bold mb-0">{{ $page->title }}</h1>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="content-body">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
