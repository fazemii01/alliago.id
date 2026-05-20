<?php

namespace App\Http\Controllers;

use App\Models\VisaProduct;

class VisaCatalogController extends Controller
{
    public function index()
    {
        $query = VisaProduct::query()
            ->with('country')
            ->where('is_active', true);

        if (request('country')) {
            $query->whereHas('country', function ($q) {
                $q->where('name', 'like', '%' . request('country') . '%');
            });
        }

        if (request('type')) {
            $query->where('type', 'like', '%' . request('type') . '%');
        }

        return view('landing.visa.index', [
            'visaProducts' => $query->orderBy('sort_order')
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
