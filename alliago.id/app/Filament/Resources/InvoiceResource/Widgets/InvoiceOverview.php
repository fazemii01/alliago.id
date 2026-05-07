<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use App\Models\Application;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class InvoiceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Calculate this month's paid invoices
        $thisMonthInvoices = Application::where('status', '!=', 'pending_payment')
            ->where('created_at', '>=', $currentMonth)
            ->get();

        $thisMonthTotal = $thisMonthInvoices->sum(function($app) {
            return $app->metadata['invoice_amount'] ?? ($app->visaProduct->discount_price ?? $app->visaProduct->base_price);
        });

        // Calculate last month's paid invoices
        $lastMonthInvoices = Application::where('status', '!=', 'pending_payment')
            ->whereBetween('created_at', [$lastMonth, $currentMonth->copy()->subSecond()])
            ->get();

        $lastMonthTotal = $lastMonthInvoices->sum(function($app) {
            return $app->metadata['invoice_amount'] ?? ($app->visaProduct->discount_price ?? $app->visaProduct->base_price);
        });

        // Calculate trend
        $trend = 0;
        if ($lastMonthTotal > 0) {
            $trend = (($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100;
        }

        $trendIcon = $trend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $trendColor = $trend >= 0 ? 'success' : 'danger';
        $trendText = number_format(abs($trend), 1) . '% ' . ($trend >= 0 ? 'increase' : 'decrease') . ' from last month';

        // Unpaid Invoices
        $unpaidInvoices = Application::where('status', 'pending_payment')->count();

        return [
            Stat::make('Total Revenue This Month', 'Rp ' . number_format($thisMonthTotal, 0, ',', '.'))
                ->description($trendText)
                ->descriptionIcon($trendIcon)
                ->color($trendColor),
            Stat::make('Invoices Paid This Month', $thisMonthInvoices->count()),
            Stat::make('Unpaid Invoices', $unpaidInvoices)
                ->description('Waiting for payment')
                ->color('warning'),
        ];
    }
}
