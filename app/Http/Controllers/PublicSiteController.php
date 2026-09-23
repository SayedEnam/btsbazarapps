<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\Package;
use App\Models\Page;
use App\Models\Testimonial;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'packages' => Package::active()->ordered()->get(),
            'heroSlides' => HeroSlide::active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
        ]);
    }

    public function about(): View
    {
        return view('public.about', [
            'page' => Page::where('slug', Page::ABOUT)->firstOrFail(),
        ]);
    }

    public function packages(): View
    {
        return view('public.packages', [
            'packages' => Package::active()->ordered()->get(),
        ]);
    }

    public function howItWorks(): View
    {
        return view('public.how-it-works');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function terms(): View
    {
        return view('public.terms', [
            'page' => Page::where('slug', Page::TERMS)->firstOrFail(),
        ]);
    }

    public function privacy(): View
    {
        return view('public.privacy', [
            'page' => Page::where('slug', Page::PRIVACY)->firstOrFail(),
        ]);
    }
}
