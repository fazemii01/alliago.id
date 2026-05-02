<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\SiteFaq;
use App\Models\Testimonial;
use App\Models\VisaProduct;

class LandingPageController extends Controller
{
    public function __invoke()
    {
        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $featuredProducts = VisaProduct::query()
            ->with('country')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $highlightProduct = VisaProduct::query()
            ->with(['country', 'requirements', 'documents', 'processSteps', 'faqs', 'addons'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        $testimonials = Testimonial::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $siteFaqs = SiteFaq::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return view('landing.home', compact(
            'countries',
            'featuredProducts',
            'highlightProduct',
            'testimonials',
            'siteFaqs',
        ));
    }
}
