<?php

namespace App\Http\Controllers;

use App\Models\VisaProduct;

class VisaCatalogController extends Controller
{
    public function index()
    {
        return view('landing.visa.index', [
            'visaProducts' => VisaProduct::query()
                ->with('country')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function show(string $slug)
    {
        $visaProduct = VisaProduct::query()
            ->with(['country', 'requirements', 'documents', 'processSteps', 'faqs', 'addons'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('landing.visa.show', compact('visaProduct'));
    }
}
