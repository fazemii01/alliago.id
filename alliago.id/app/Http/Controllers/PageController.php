<?php

namespace App\Http\Controllers;

use App\Models\SiteFaq;
use App\Models\VisaProduct;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function detail()
    {
        $highlightProduct = VisaProduct::query()
            ->with(['country', 'requirements', 'documents', 'processSteps', 'faqs', 'addons'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        return view('pages.detail', compact('highlightProduct'));
    }

    public function process()
    {
        return view('pages.process');
    }

    public function faq()
    {
        $siteFaqs = SiteFaq::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return view('pages.faq', compact('siteFaqs'));
    }
}
