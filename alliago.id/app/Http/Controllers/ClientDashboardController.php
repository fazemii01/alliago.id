<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Country;
use App\Models\VisaProduct;
use Illuminate\View\View;

class ClientDashboardController extends Controller
{
    public function __invoke(): View
    {
        $client = auth()->user();

        $applications = Application::query()
            ->with(['visaProduct.country', 'documents', 'messages', 'statusLogs'])
            ->where('user_id', $client->id)
            ->latest()
            ->get();

        $documentsNeedAttention = $applications->sum(function (Application $application): int {
            return $application->documents->whereIn('status', ['pending_upload', 'needs_revision'])->count();
        });

        $unreadAdminMessages = $applications->sum(function (Application $application): int {
            return $application->messages->where('is_admin', true)->whereNull('read_at')->count();
        });

        return view('client.dashboard', [
            'client' => $client,
            'orderStats' => [
                'activeOrders' => $applications->count(),
                'documentsNeedAttention' => $documentsNeedAttention,
                'unreadAdminMessages' => $unreadAdminMessages,
            ],
            'profileCompletionItems' => [
                'Name on account' => (bool) $client?->name,
                'Email address' => (bool) $client?->email,
                'Phone number' => (bool) $client?->phone,
            ],
            'orderGuidance' => [
                'Choose the visa product that matches your trip.',
                'Complete your personal details before document review starts.',
                'Use the communication panel whenever admin asks for revisions or clarification.',
            ],
            'recommendedVisaProducts' => VisaProduct::query()
                ->with('country')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->take(6)
                ->get(),
            'availableCountries' => Country::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->take(8)
                ->get(),
            'applications' => $applications,
        ]);
    }
}
