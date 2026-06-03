<?php

namespace App\Http\Controllers;

use App\Models\VisaProduct;

class SitemapController extends Controller
{
    public function index()
    {
        $visaProducts = VisaProduct::where('is_active', true)
            ->select('slug', 'updated_at')
            ->orderBy('sort_order')
            ->get();

        return response()
            ->view('sitemap', compact('visaProducts'))
            ->header('Content-Type', 'text/xml');
    }
}
