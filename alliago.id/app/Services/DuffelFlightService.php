<?php

namespace App\Services;

use App\Models\FlightPricingConfig;
use App\Support\AirlineNames;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DuffelFlightService
{
    protected const BASE_URL = 'https://api.duffel.com';
    protected const VERSION = 'v2';
    protected const AIRPORTS_CACHE_TTL_HOURS = 24;
    protected const EXCHANGE_RATE_CACHE_TTL_MINUTES = 60;

    public function getCities(): array
    {
        return Cache::remember('duffel_airports', Carbon::now()->addHours(self::AIRPORTS_CACHE_TTL_HOURS), function () {
            $airports = [];
            $after = null;

            do {
                $params = ['limit' => 200];
                if ($after !== null) {
                    $params['after'] = $after;
                }

                $response = $this->get('/air/airports', $params);
                $data = Arr::get($response, 'data', []);
                $airports = array_merge($airports, $data);
                $after = Arr::get($response, 'meta.after');
            } while ($after !== null && count($airports) < 2000);

            return collect($airports)
                ->filter(fn ($airport) => filled(Arr::get($airport, 'iata_code')))
                ->map(fn ($airport) => [
                    'id' => (string) Arr::get($airport, 'iata_code'),
                    'name' => (string) (Arr::get($airport, 'city_name') ?? Arr::get($airport, 'name', '')),
                    'country_id' => (string) Arr::get($airport, 'iata_country_code', ''),
                    'label' => trim(sprintf(
                        '%s (%s)',
                        Arr::get($airport, 'city_name') ?? Arr::get($airport, 'name'),
                        Arr::get($airport, 'iata_code'),
                    )),
                ])
                ->sortBy('name')
                ->values()
                ->all();
        });
    }

    public function searchAirports(string $query): array
    {
        if (blank($query)) {
            return [];
        }

        $response = $this->get('/places/suggestions', ['query' => $query]);

        return collect(Arr::get($response, 'data', []))
            ->filter(fn ($place) => filled(Arr::get($place, 'iata_code')))
            ->map(fn ($place) => [
                'id' => (string) Arr::get($place, 'iata_code'),
                'name' => (string) (Arr::get($place, 'city_name') ?? Arr::get($place, 'name', '')),
                'country_id' => (string) Arr::get($place, 'iata_country_code', ''),
                'label' => trim(sprintf(
                    '%s (%s)',
                    Arr::get($place, 'city_name') ?? Arr::get($place, 'name'),
                    Arr::get($place, 'iata_code'),
                )),
            ])
            ->values()
            ->all();
    }

    public function tripTypes(): array
    {
        return [
            'O' => 'One Way',
            'R' => 'Round Trip',
        ];
    }

    public function search(array $input): array
    {
        $slices = $this->buildSlices($input);
        $passengers = $this->buildPassengers($input);

        $offerRequest = $this->post('/air/offer_requests?return_offers=true', [
            'data' => [
                'slices' => $slices,
                'passengers' => $passengers,
                'cabin_class' => (string) Arr::get($input, 'cabin_class', 'economy'),
            ],
        ]);

        $offerRequestId = Arr::get($offerRequest, 'data.id', '');

        $offersResponse = $this->get('/air/offers', [
            'offer_request_id' => $offerRequestId,
            'sort' => 'total_amount',
            'limit' => 50,
        ]);

        $results = collect(Arr::get($offersResponse, 'data', []))
            ->map(fn ($offer) => $this->mapOffer($offer))
            ->filter()
            ->sortBy('price_value')
            ->values()
            ->all();

        return ['results' => $results];
    }

    protected function buildSlices(array $input): array
    {
        $slices = [
            [
                'origin' => (string) Arr::get($input, 'origin'),
                'destination' => (string) Arr::get($input, 'destination'),
                'departure_date' => (string) Arr::get($input, 'depart_date'),
            ],
        ];

        if (Arr::get($input, 'trip_type') === 'R' && filled(Arr::get($input, 'return_date'))) {
            $slices[] = [
                'origin' => (string) Arr::get($input, 'destination'),
                'destination' => (string) Arr::get($input, 'origin'),
                'departure_date' => (string) Arr::get($input, 'return_date'),
            ];
        }

        return $slices;
    }

    protected function buildPassengers(array $input): array
    {
        $passengers = [];

        for ($i = 0; $i < (int) Arr::get($input, 'adult', 1); $i++) {
            $passengers[] = ['type' => 'adult'];
        }

        for ($i = 0; $i < (int) Arr::get($input, 'child', 0); $i++) {
            $passengers[] = ['type' => 'child'];
        }

        for ($i = 0; $i < (int) Arr::get($input, 'infant', 0); $i++) {
            $passengers[] = ['type' => 'infant_without_seat'];
        }

        return $passengers;
    }

    protected function getExchangeRate(string $from, string $to = 'IDR'): float
    {
        if ($from === $to) {
            return 1.0;
        }

        return Cache::remember(
            "exchange_rate_{$from}_{$to}",
            Carbon::now()->addMinutes(self::EXCHANGE_RATE_CACHE_TTL_MINUTES),
            function () use ($from, $to) {
                try {
                    $response = Http::timeout(10)->connectTimeout(5)
                        ->get('https://api.frankfurter.app/latest', ['from' => $from, 'to' => $to]);

                    $rate = (float) Arr::get($response->json(), "rates.{$to}", 0);

                    return $rate > 0 ? $rate : 1.0;
                } catch (\Throwable) {
                    return 1.0;
                }
            }
        );
    }

    protected function mapOffer(array $offer): ?array
    {
        $totalAmount = (float) Arr::get($offer, 'total_amount', 0);
        if ($totalAmount <= 0) {
            return null;
        }

        $currency = (string) Arr::get($offer, 'total_currency', 'IDR');
        $idrRate = $this->getExchangeRate($currency);
        $slices = Arr::get($offer, 'slices', []);
        $firstSlice = $slices[0] ?? [];
        $segments = Arr::get($firstSlice, 'segments', []);
        $firstSegment = $segments[0] ?? [];
        $lastSegment = ! empty($segments) ? $segments[array_key_last($segments)] : [];

        $departingAt = Arr::get($firstSegment, 'departing_at');
        $arrivingAt = Arr::get($lastSegment, 'arriving_at');
        $stopCount = max(count($segments) - 1, 0);

        $flightNumbers = collect($segments)
            ->map(fn ($seg) => trim(collect([
                Arr::get($seg, 'marketing_carrier.iata_code'),
                Arr::get($seg, 'marketing_carrier_flight_number'),
            ])->filter()->implode(' ')))
            ->filter()
            ->implode(', ');

        $firstSegPax = Arr::get($firstSegment, 'passengers.0', []);
        $cabinClass = (string) Arr::get($firstSegPax, 'cabin_class_marketing_name',
            Arr::get($firstSegPax, 'cabin_class', ''));

        $baggageInfo = collect($segments)
            ->flatMap(fn ($seg) => Arr::wrap(Arr::get($seg, 'passengers.0.baggages', [])))
            ->map(fn ($bag) => Arr::get($bag, 'quantity') . 'x ' . Arr::get($bag, 'type'))
            ->filter()
            ->unique()
            ->implode(' | ');

        $fareBrandName = (string) Arr::get($firstSlice, 'fare_brand_name', '');
        $info = collect([$fareBrandName, $baggageInfo])->filter()->implode(' | ');

        $totalAmountIdr = round($totalAmount * $idrRate);
        $airlineIata = (string) Arr::get($firstSegment, 'marketing_carrier.iata_code', Arr::get($firstSegment, 'operating_carrier.iata_code', ''));
        $config = FlightPricingConfig::current();
        $totalWithMarkup = $totalAmountIdr + $config->markupFor($airlineIata);

        return [
            'offer_id' => (string) Arr::get($offer, 'id'),
            'airline' => $airlineIata ?: 'Unknown',
            'airline_name' => $config->nameFor($airlineIata) ?? ($airlineIata ? AirlineNames::get($airlineIata) : 'Unknown'),
            'logo_url' => $config->logoFor($airlineIata) ?? ($airlineIata ? "https://airlabs.co/img/airline/s/{$airlineIata}.png" : null),
            'flight_numbers' => $flightNumbers,
            'duration' => $this->formatDuration($departingAt, $arrivingAt),
            'stops' => $stopCount > 0 ? $stopCount . ' stop' . ($stopCount > 1 ? 's' : '') : 'Direct',
            'net_price' => $totalAmountIdr,
            'price' => $this->formatIdr($totalWithMarkup),
            'price_value' => $totalWithMarkup,
            'start_date' => $this->formatDate($departingAt),
            'start_time' => $this->formatTime($departingAt),
            'start_location' => (string) Arr::get($firstSegment, 'origin.iata_code', ''),
            'end_date' => $this->formatDate($arrivingAt),
            'end_time' => $this->formatTime($arrivingAt),
            'end_location' => (string) Arr::get($lastSegment, 'destination.iata_code', ''),
            'class' => $cabinClass,
            'info' => $info,
            'segments' => $segments,
            'fare_breakdown' => $this->buildFareBreakdown($offer, $idrRate),
            'journey_reference' => (string) Arr::get($offer, 'id'),
            'passport_required' => (bool) Arr::get($offer, 'passenger_identity_documents_required', false),
        ];
    }

    protected function buildFareBreakdown(array $offer, float $idrRate = 1.0): array
    {
        $passengers = Arr::get($offer, 'passengers', []);
        $totalAmount = (float) Arr::get($offer, 'total_amount', 0) * $idrRate;
        $baseAmount = (float) Arr::get($offer, 'base_amount', 0) * $idrRate;
        $taxAmount = (float) Arr::get($offer, 'tax_amount', 0) * $idrRate;
        $count = max(count($passengers), 1);

        $perPassenger = $totalAmount / $count;
        $perBase = $baseAmount / $count;
        $perTax = $taxAmount / $count;

        return collect($passengers)
            ->groupBy(fn ($p) => Arr::get($p, 'type', 'adult'))
            ->map(fn ($group, $type) => [
                'pax_type' => $type,
                'base_fare' => $this->formatIdr(round($perBase * count($group))),
                'tax' => $this->formatIdr(round($perTax * count($group))),
                'total_fare' => $this->formatIdr(round($perPassenger * count($group))),
            ])
            ->values()
            ->all();
    }

    protected function get(string $path, array $query = []): array
    {
        $response = $this->client()
            ->get($path, $query)
            ->throw()
            ->json();

        return is_array($response) ? $response : [];
    }

    protected function post(string $path, array $body): array
    {
        $response = $this->client()
            ->post($path, $body)
            ->throw()
            ->json();

        return is_array($response) ? $response : [];
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL)
            ->withToken((string) config('services.duffel.secret'))
            ->withHeaders([
                'Duffel-Version' => self::VERSION,
                'Accept' => 'application/json',
            ])
            ->acceptJson()
            ->asJson()
            ->timeout(30)
            ->connectTimeout(10);
    }

    protected function formatIdr(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    protected function formatDate(?string $dateTime): string
    {
        if (blank($dateTime)) {
            return '-';
        }

        try {
            return Carbon::parse($dateTime)->translatedFormat('d M Y');
        } catch (\Throwable) {
            return '-';
        }
    }

    protected function formatTime(?string $dateTime): string
    {
        if (blank($dateTime)) {
            return '-';
        }

        try {
            return Carbon::parse($dateTime)->format('H:i');
        } catch (\Throwable) {
            return (string) $dateTime;
        }
    }

    protected function formatDuration(?string $departureTime, ?string $arrivalTime): string
    {
        if (blank($departureTime) || blank($arrivalTime)) {
            return '-';
        }

        try {
            $minutes = Carbon::parse($departureTime)->diffInMinutes(Carbon::parse($arrivalTime));

            return sprintf('%dh %02dm', intdiv($minutes, 60), $minutes % 60);
        } catch (\Throwable) {
            return '-';
        }
    }
}
