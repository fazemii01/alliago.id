<?php

namespace App\Http\Controllers;

use App\Models\VisaProduct;

class VisaCatalogController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = VisaProduct::query()
            ->with('country')
            ->where('is_active', true);

        // Server-side search filter
        if ($search = $request->input('query')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('country', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Server-side purpose filter (from home page search)
        if ($purpose = $request->input('purpose')) {
            $query->where(function ($q) use ($purpose) {
                if ($purpose === 'tourism') {
                    $q->where('name', 'like', '%tourist%')
                      ->orWhere('name', 'like', '%tourism%')
                      ->orWhere('name', 'like', '%holiday%')
                      ->orWhere('name', 'like', '%wisata%')
                      ->orWhere('name', 'like', '%liburan%')
                      ->orWhere('name', 'like', '%visit%');
                } elseif ($purpose === 'business') {
                    $q->where('name', 'like', '%business%')
                      ->orWhere('name', 'like', '%bisnis%');
                } elseif ($purpose === 'student') {
                    $q->where('name', 'like', '%student%')
                      ->orWhere('name', 'like', '%pelajar%')
                      ->orWhere('name', 'like', '%study%')
                      ->orWhere('name', 'like', '%belajar%');
                } elseif ($purpose === 'family') {
                    $q->where('name', 'like', '%family%')
                      ->orWhere('name', 'like', '%keluarga%')
                      ->orWhere('name', 'like', '%kunjungan%');
                }
            });
        }

        // Server-side type filter
        if ($types = $request->input('types')) {
            if (is_array($types)) {
                $query->whereIn('type', $types);
            }
        }

        // Paginate results to 16 products per page
        $visaProducts = $query->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(16)
            ->withQueryString();

        // Get all unique types across all active products for the dropdown filters
        $allTypes = VisaProduct::query()
            ->where('is_active', true)
            ->distinct()
            ->pluck('type')
            ->filter()
            ->sort()
            ->values()
            ->all();

        return view('landing.visa.index', [
            'visaProducts' => $visaProducts,
            'types' => $allTypes,
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
