<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // 1. Total Applications
        $totalApps = Application::count();
        $newAppsThisMonth = Application::where('created_at', '>=', $currentMonth)->count();
        $newAppsLastMonth = Application::whereBetween('created_at', [$lastMonth, $currentMonth])->count();
        
        $appsTrend = 0;
        if ($newAppsLastMonth > 0) {
            $appsTrend = (($newAppsThisMonth - $newAppsLastMonth) / $newAppsLastMonth) * 100;
        }

        // 2. Applications needing attention
        $pendingApps = Application::whereIn('status', ['pending_payment', 'draft', 'in_review'])->count();

        // 3. Total Clients
        $totalClients = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();

        return [
            Stat::make('Total Applications', $totalApps)
                ->description($newAppsThisMonth . ' new this month')
                ->descriptionIcon($appsTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($appsTrend >= 0 ? 'success' : 'danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Needs Attention', $pendingApps)
                ->description('Applications waiting for action')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning'),

            Stat::make('Total Clients', $totalClients)
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
