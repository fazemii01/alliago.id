<?php

namespace App\Http\Controllers;

use App\Services\DuffelFlightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Throwable;

class FlightTicketController extends Controller
{
    public function __construct(
        protected DuffelFlightService $duffelFlightService,
    ) {
    }

    public function index(Request $request)
    {
        $tripTypes = $this->duffelFlightService->tripTypes();
        $results = [];
        $paginatedResults = null;
        $error = null;

        $filters = [
            'origin' => (string) $request->input('origin', ''),
            'destination' => (string) $request->input('destination', ''),
            'origin_label' => (string) $request->input('origin_label', ''),
            'destination_label' => (string) $request->input('destination_label', ''),
            'depart_date' => (string) $request->input('depart_date', now()->addWeek()->toDateString()),
            'return_date' => (string) $request->input('return_date', ''),
            'trip_type' => (string) $request->input('trip_type', 'O'),
            'adult' => (int) $request->input('adult', 1),
            'child' => (int) $request->input('child', 0),
            'infant' => (int) $request->input('infant', 0),
        ];

        if ($request->isMethod('post') || $request->query('search') === '1') {
            $validated = $request->validate([
                'origin' => ['required', 'string'],
                'destination' => ['required', 'string', 'different:origin'],
                'depart_date' => ['required', 'date', 'after_or_equal:today'],
                'return_date' => ['nullable', 'date', 'after_or_equal:depart_date'],
                'trip_type' => ['required', Rule::in(array_keys($tripTypes))],
                'adult' => ['required', 'integer', 'min:1', 'max:9'],
                'child' => ['nullable', 'integer', 'min:0', 'max:9'],
                'infant' => ['nullable', 'integer', 'min:0', 'max:9'],
            ]);

            $filters = array_merge($filters, $validated);

            if (($filters['adult'] + $filters['child'] + $filters['infant']) > 9) {
                return back()
                    ->withInput()
                    ->withErrors(['adult' => 'Total passenger maksimal 9 orang.']);
            }

            if ($filters['trip_type'] !== 'R') {
                $filters['return_date'] = '';
            }

            try {
                $search = $this->duffelFlightService->search($filters);
                $results = Arr::get($search, 'results', []);

                $config = \App\Models\FlightPricingConfig::current();
                $currency = $config->currency ?? 'IDR';
                $rate = \App\Models\VisaSetting::getIdrToTargetRate($currency);
                $threshold = $currency === 'IDR' ? 2000000 : (2000000 / $rate);

                // Automatically hide any ticket priced under Rp 2.000.000 (ZZ airline bypasses this filter)
                $results = collect($results)
                    ->filter(fn ($flight) => ($flight['price_value'] ?? 0) >= $threshold || strtoupper($flight['airline']) === 'ZZ')
                    ->sortBy('price_value') // Sort other flights by lowest price
                    ->sortBy(fn ($flight) => strtoupper($flight['airline']) === 'ZZ' ? 0 : 1) // Prioritize ZZ to be at the absolute top (top 1 display) regardless of price
                    ->values()
                    ->all();

                if ($results === []) {
                    $error = 'Jadwal belum tersedia untuk rute atau tanggal ini. Silakan coba tanggal lain.';
                }

                $paginatedResults = $this->paginateResults(collect($results), $request);
            } catch (Throwable $throwable) {
                \Illuminate\Support\Facades\Log::error($throwable);
                $error = 'Pencarian tiket sedang sibuk. Silakan coba lagi dalam beberapa saat.';
            }
        }

        $airlines = collect($results)
            ->map(fn ($f) => ['code' => $f['airline'], 'name' => $f['airline_name'] ?? $f['airline']])
            ->unique('code')
            ->sortBy('name')
            ->values()
            ->all();

        $users = [];
        $paymentMethods = [];
        if (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))) {
            $users = \App\Models\User::orderBy('name')->get(['id', 'name', 'email', 'phone'])->all();
            $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get(['id', 'name', 'provider', 'code'])->all();
        }

        $recommendations = \App\Models\Recommendation::query()
            ->with(['visaProduct.country'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->pluck('visaProduct')
            ->filter();

        return view('landing.flights.index', [
            'tripTypes' => $tripTypes,
            'filters' => $filters,
            'results' => $results,
            'paginatedResults' => $paginatedResults,
            'error' => $error,
            'airlines' => $airlines,
            'users' => $users,
            'paymentMethods' => $paymentMethods,
            'recommendations' => $recommendations,
        ]);
    }

    public function searchAirports(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('query', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            return response()->json($this->duffelFlightService->searchAirports($query));
        } catch (Throwable) {
            return response()->json([]);
        }
    }

    public function airlineLogo(string $iata)
    {
        $config = \App\Models\FlightPricingConfig::current();
        $logoUrl = $config->zz_logo_url;

        if (strtoupper($iata) === 'ZZ' && $logoUrl) {
            try {
                // Fetch the image from the remote URL using Laravel's secure HTTP client
                $response = \Illuminate\Support\Facades\Http::timeout(10)->get($logoUrl);
                if ($response->successful()) {
                    $contentType = $response->header('Content-Type') ?: 'image/jpeg';
                    return response($response->body(), 200)->header('Content-Type', $contentType);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Failed to proxy ZZ airline logo from {$logoUrl}: " . $e->getMessage());
            }
        }

        return response('', 404);
    }

    protected function paginateResults(Collection $results, Request $request): LengthAwarePaginator
    {
        $perPage = 6;
        $currentPage = max((int) $request->query('page', 1), 1);
        $items = $results->forPage($currentPage, $perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $results->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => array_merge($request->query(), [
                    'search' => '1',
                    'origin' => $request->input('origin'),
                    'destination' => $request->input('destination'),
                    'origin_label' => $request->input('origin_label'),
                    'destination_label' => $request->input('destination_label'),
                    'depart_date' => $request->input('depart_date'),
                    'return_date' => $request->input('return_date'),
                    'trip_type' => $request->input('trip_type'),
                    'adult' => $request->input('adult'),
                    'child' => $request->input('child'),
                    'infant' => $request->input('infant'),
                ]),
            ],
        );
    }

    public function generateInvoice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:users,id'],
            'traveler_name' => ['required', 'string', 'max:255'],
            'traveler_email' => ['required', 'email', 'max:255'],
            'traveler_phone' => ['nullable', 'string', 'max:50'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'flight.airline' => ['required', 'string'],
            'flight.airline_name' => ['required', 'string'],
            'flight.flight_numbers' => ['nullable', 'string'],
            'flight.origin' => ['required', 'string'],
            'flight.destination' => ['required', 'string'],
            'flight.depart_date' => ['required', 'date'],
            'flight.depart_time' => ['nullable', 'string'],
            'flight.return_date' => ['nullable', 'date'],
            'flight.return_time' => ['nullable', 'string'],
            'flight.cabin_class' => ['required', 'string'],
            'flight.price_value' => ['required', 'numeric', 'min:0'],
            'flight.tax' => ['required', 'numeric', 'min:0'],
            'flight.total' => ['nullable', 'numeric', 'min:0'],
            'baggage_weight' => ['nullable', 'integer', 'min:0'],
            'baggage_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $subtotal = (float) $request->input('flight.price_value');
        $tax = (float) $request->input('flight.tax', 0);
        $baggagePrice = (float) $request->input('baggage_price', 0);
        $baggageWeight = (int) $request->input('baggage_weight', 0);
        $total = $request->input('flight.total') !== null ? (float) $request->input('flight.total') : ($subtotal + $tax + $baggagePrice);

        // Generate custom invoice metadata
        $metadata = [
            'type' => 'flight',
            'payment_method_id' => $request->input('payment_method_id'),
            'payment_status' => 'unpaid',
            'flight_details' => [
                'airline' => $request->input('flight.airline'),
                'airline_name' => $request->input('flight.airline_name'),
                'flight_numbers' => $request->input('flight.flight_numbers'),
                'origin' => $request->input('flight.origin'),
                'destination' => $request->input('flight.destination'),
                'depart_date' => $request->input('flight.depart_date'),
                'depart_time' => $request->input('flight.depart_time'),
                'return_date' => $request->input('flight.return_date'),
                'return_time' => $request->input('flight.return_time'),
                'cabin_class' => $request->input('flight.cabin_class'),
                'price_value' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'extra_baggage_weight' => $baggageWeight,
                'extra_baggage_price' => $baggagePrice,
            ],
            'price_breakdown' => [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'extra_baggage' => $baggagePrice,
                'total' => $total,
                'currency' => \App\Models\FlightPricingConfig::current()->currency ?? 'IDR',
            ],
            'invoice_amount' => $total,
        ];

        // Create application
        $application = \App\Models\Application::create([
            'user_id' => $request->input('client_id'),
            'visa_product_id' => null,
            'reference_number' => 'FLT-' . strtoupper(\Illuminate\Support\Str::random(10)),
            'status' => 'pending_payment',
            'traveler_name' => $request->input('traveler_name'),
            'traveler_email' => $request->input('traveler_email'),
            'traveler_phone' => $request->input('traveler_phone'),
            'metadata' => $metadata,
        ]);

        // Status log
        \App\Models\ApplicationStatusLog::create([
            'application_id' => $application->id,
            'admin_id' => auth()->id(),
            'to_status' => 'pending_payment',
            'message' => 'Invoice pemesanan tiket pesawat dibuat oleh admin.',
        ]);

        // Generate Xendit Payment URL if needed
        $paymentMethod = \App\Models\PaymentMethod::find($request->input('payment_method_id'));
        $xenditUrl = null;

        if ($paymentMethod && $paymentMethod->provider === 'xendit') {
            $secretKey = $paymentMethod->configuration['secret_key'] ?? null;
            if (!$secretKey) {
                return response()->json(['message' => 'Konfigurasi Xendit tidak valid.'], 422);
            }

            try {
                $response = \Illuminate\Support\Facades\Http::withBasicAuth($secretKey, '')
                    ->post('https://api.xendit.co/v2/invoices', [
                        'external_id' => $application->reference_number,
                        'amount' => $total,
                        'payer_email' => $application->traveler_email,
                        'description' => 'Pembayaran Tiket Pesawat: ' . ($metadata['flight_details']['airline_name'] ?? 'Penerbangan'),
                        'success_redirect_url' => route('client.dashboard'),
                        'failure_redirect_url' => route('client.applications.checkout', $application),
                        'currency' => $metadata['price_breakdown']['currency'] ?? 'IDR',
                    ]);

                if ($response->successful()) {
                    $invoice = $response->json();
                    
                    $updatedMetadata = $application->metadata ?? [];
                    $updatedMetadata['xendit_invoice_url'] = $invoice['invoice_url'];
                    $updatedMetadata['xendit_invoice_id'] = $invoice['id'];
                    $application->metadata = $updatedMetadata;
                    $application->save();

                    $xenditUrl = $invoice['invoice_url'];
                } else {
                    \Illuminate\Support\Facades\Log::error('Xendit Invoice Creation Failed: ' . $response->body());
                    return response()->json(['message' => 'Gagal membuat invoice Xendit.'], 500);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Xendit API Exception: ' . $e->getMessage());
                return response()->json(['message' => 'Terjadi kesalahan sistem saat menghubungi Xendit.'], 500);
            }
        }

        return response()->json([
            'status' => 'success',
            'reference_number' => $application->reference_number,
            'invoice_url' => route('client.applications.invoice', $application),
            'xendit_url' => $xenditUrl,
        ]);
    }
}
