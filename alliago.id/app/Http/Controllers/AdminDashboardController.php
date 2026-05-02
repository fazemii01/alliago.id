<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use App\Models\VisaProduct;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'countries' => Country::query()->count(),
                'visaProducts' => VisaProduct::query()->count(),
                'activeVisaProducts' => VisaProduct::query()->where('is_active', true)->count(),
                'clients' => User::role('user')->count(),
            ],
            'latestVisaProducts' => VisaProduct::query()
                ->with('country')
                ->latest('updated_at')
                ->take(5)
                ->get(),
        ]);
    }
}
