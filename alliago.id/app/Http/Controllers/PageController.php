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

    public function about()
    {
        return view('pages.about');
    }

    public function currencyRates()
    {
        $apiKey = env('EXCHANGE_RATE_API_KEY') ?: config('services.abstract_currency.key');
        
        $ratesData = \Illuminate\Support\Facades\Cache::remember('currency_rates_data', 14400, function () use ($apiKey) {
            if (!$apiKey) {
                return null;
            }

            try {
                $response = \Illuminate\Support\Facades\Http::get('https://exchange-rates.abstractapi.com/v1/live/', [
                    'api_key' => $apiKey,
                    'base' => 'USD'
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                \Log::error('Abstract API Currency fetch failed: ' . $e->getMessage());
            }

            return null;
        });

        $exchangeRates = [];
        $baseCurrency = 'IDR';
        $lastUpdated = now()->timestamp;

        if ($ratesData && isset($ratesData['exchange_rates'])) {
            $rates = $ratesData['exchange_rates'];
            $lastUpdated = $ratesData['last_updated'] ?? now()->timestamp;
            $idrPerUsd = $rates['IDR'] ?? 16250;
            
            $targetCurrencies = [
                'USD' => 'US Dollar',
                'SGD' => 'Singapore Dollar',
                'MYR' => 'Malaysian Ringgit',
                'EUR' => 'Euro',
                'AUD' => 'Australian Dollar',
                'GBP' => 'British Pound',
                'JPY' => 'Japanese Yen',
                'CNY' => 'Chinese Yuan',
                'SAR' => 'Saudi Riyal',
                'KRW' => 'South Korean Won',
                'THB' => 'Thai Baht'
            ];

            foreach ($targetCurrencies as $code => $name) {
                if (isset($rates[$code])) {
                    $currencyPerUsd = $rates[$code];
                    $idrValue = $idrPerUsd / $currencyPerUsd;
                    $exchangeRates[$code] = [
                        'code' => $code,
                        'name' => $name,
                        'rate_to_idr' => $idrValue,
                        'idr_to_currency' => 1 / $idrValue,
                    ];
                }
            }
        } else {
            // High-fidelity fallback values
            $fallbackRates = [
                'USD' => ['name' => 'US Dollar', 'rate_to_idr' => 16280],
                'SGD' => ['name' => 'Singapore Dollar', 'rate_to_idr' => 12050],
                'MYR' => ['name' => 'Malaysian Ringgit', 'rate_to_idr' => 3450],
                'EUR' => ['name' => 'Euro', 'rate_to_idr' => 17620],
                'AUD' => ['name' => 'Australian Dollar', 'rate_to_idr' => 10830],
                'GBP' => ['name' => 'British Pound', 'rate_to_idr' => 20750],
                'JPY' => ['name' => 'Japanese Yen', 'rate_to_idr' => 104.2],
                'CNY' => ['name' => 'Chinese Yuan', 'rate_to_idr' => 2240],
                'SAR' => ['name' => 'Saudi Riyal', 'rate_to_idr' => 4340],
                'KRW' => ['name' => 'South Korean Won', 'rate_to_idr' => 11.8],
                'THB' => ['name' => 'Thai Baht', 'rate_to_idr' => 443]
            ];

            foreach ($fallbackRates as $code => $data) {
                $exchangeRates[$code] = [
                    'code' => $code,
                    'name' => $data['name'],
                    'rate_to_idr' => $data['rate_to_idr'],
                    'idr_to_currency' => 1 / $data['rate_to_idr'],
                ];
            }
        }

        return view('pages.currency-rates', compact('exchangeRates', 'baseCurrency', 'lastUpdated'));
    }

    public function faq()
    {
        $siteFaqs = SiteFaq::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return view('pages.faq', compact('siteFaqs'));
    }

    public function refundPolicy()
    {
        return view('pages.refund-policy');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }
}
