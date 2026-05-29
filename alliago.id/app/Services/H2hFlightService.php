<?php

namespace App\Services;

use App\Models\FlightPricingConfig;
use App\Support\AirlineNames;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class H2hFlightService
{
    public function getCities(): array
    {
        $response = $this->post('Airline/City', []);

        return collect(Arr::get($response, 'cities', []))
            ->filter(fn ($city) => filled(Arr::get($city, 'cityID')) && filled(Arr::get($city, 'cityName')))
            ->map(fn ($city) => [
                'id' => (string) Arr::get($city, 'cityID'),
                'name' => (string) Arr::get($city, 'cityName'),
                'country_id' => (string) Arr::get($city, 'countryID', ''),
                'label' => trim(sprintf('%s (%s)', Arr::get($city, 'cityName'), Arr::get($city, 'cityID'))),
            ])
            ->sortBy('name')
            ->values()
            ->all();
    }

    public function search(array $input): array
    {
        $payload = [
            'tripType' => $this->tripTypeValue((string) Arr::get($input, 'trip_type', 'O')),
            'origin' => Arr::get($input, 'origin'),
            'destination' => Arr::get($input, 'destination'),
            'departDate' => $this->travelDate((string) Arr::get($input, 'depart_date')),
            'returnDate' => Arr::get($input, 'trip_type') === 'R' && filled(Arr::get($input, 'return_date'))
                ? $this->travelDate((string) Arr::get($input, 'return_date'))
                : null,
            'paxAdult' => (int) Arr::get($input, 'adult', 1),
            'paxChild' => (int) Arr::get($input, 'child', 0),
            'paxInfant' => (int) Arr::get($input, 'infant', 0),
            'cacheType' => 2,
            'isShowEachAirline' => true,
            'airlineAccessCode' => '',
            'promoCode' => '',
        ];

        $scheduleResponses = [];
        $journeys = collect();
        $seenIndexes = [];
        $maxAttempts = 6;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $schedule = $this->post('Airline/ScheduleAllAirline', array_filter($payload, fn ($value) => $value !== null));
            $scheduleResponses[] = $schedule;
            $journeys = $journeys->merge(Arr::get($schedule, 'journeyDepart', Arr::get($schedule, 'departures', Arr::get($schedule, 'journeys', Arr::get($schedule, 'data', [])))));

            $currentIndex = (int) Arr::get($schedule, 'airlineIndex', 0);
            $totalAirline = (int) Arr::get($schedule, 'totalAirline', 0);
            $payload['airlineAccessCode'] = (string) Arr::get($schedule, 'airlineAccessCode', '');

            if ($totalAirline === 0 || $currentIndex >= $totalAirline || isset($seenIndexes[$currentIndex])) {
                break;
            }

            $seenIndexes[$currentIndex] = true;
        }

        $journeys = $journeys->values();

        if ($journeys->isEmpty()) {
            return [
                'results' => [],
                'raw_schedule' => $scheduleResponses,
            ];
        }

        $lastSchedule = $scheduleResponses[array_key_last($scheduleResponses)] ?? [];

        $results = $journeys
            ->map(function (array $journey) use ($payload, $lastSchedule) {
                $pricePayload = [
                    'airlineID' => Arr::get($journey, 'airlineID', ''),
                    'tripType' => $payload['tripType'],
                    'origin' => $payload['origin'],
                    'destination' => $payload['destination'],
                    'departDate' => $payload['departDate'],
                    'returnDate' => $payload['returnDate'],
                    'paxAdult' => $payload['paxAdult'],
                    'paxChild' => $payload['paxChild'],
                    'paxInfant' => $payload['paxInfant'],
                    'airlineAccessCode' => Arr::get($lastSchedule, 'airlineAccessCode', ''),
                    'journeyDepartReference' => Arr::get($journey, 'journeyReference', Arr::get($journey, 'departureReference', '')),
                    'journeyReturnReference' => '',
                ];

                $price = $this->post('Airline/PriceAllAirline', array_filter($pricePayload, fn ($value) => $value !== null));

                return $this->mapJourney($journey, $price);
            })
            ->filter()
            ->sortBy('price_value')
            ->values()
            ->all();

        return [
            'results' => $results,
            'raw_schedule' => $scheduleResponses,
        ];
    }

    public function tripTypes(): array
    {
        return [
            'O' => 'One Way',
            'R' => 'Round Trip',
        ];
    }

    protected function tripTypeValue(string $tripType): string
    {
        return $tripType === 'R' ? 'RoundTrip' : 'OneWay';
    }

    protected function travelDate(string $date): string
    {
        return Carbon::createFromFormat('Y-m-d', $date, 'Asia/Jakarta')
            ->startOfDay()
            ->toIso8601String();
    }

    protected function mapJourney(array $journey, array $price): ?array
    {
        $segments = collect(Arr::get($journey, 'segment', Arr::get($journey, 'segments', [])))->values();
        $flightDetails = $segments
            ->flatMap(fn ($segment) => Arr::wrap(Arr::get($segment, 'flightDetail', [])))
            ->values();
        $availableDetails = $segments
            ->flatMap(fn ($segment) => Arr::wrap(Arr::get($segment, 'availableDetail', [])))
            ->values();
        $firstFlight = $flightDetails->first() ?? [];
        $lastFlight = $flightDetails->last() ?? [];
        $priceSegments = collect(Arr::get($price, 'priceDepart', []))->values();
        $firstPriceSegment = $priceSegments->first() ?? [];
        $priceDetails = $priceSegments
            ->flatMap(fn ($segment) => Arr::wrap(Arr::get($segment, 'priceDetail', [])))
            ->values();
        $sumFare = Arr::get($price, 'sumFare', Arr::get($journey, 'sumPrice'));
        $numericFare = (float) preg_replace('/[^0-9.]/', '', (string) $sumFare);

        if (! $numericFare && empty($sumFare)) {
            return null;
        }

        $markup = FlightPricingConfig::current()->totalMarkup();
        $totalPrice = $numericFare + $markup;

        $flightNumbers = $flightDetails
            ->map(fn ($detail) => trim(collect([
                Arr::get($detail, 'airlineCode'),
                Arr::get($detail, 'flightNumber'),
            ])->filter()->implode(' ')))
            ->filter()
            ->implode(', ');

        $priceInfo = $priceDetails
            ->flatMap(fn ($detail) => Arr::wrap(Arr::get($detail, 'priceInfo', [])))
            ->filter()
            ->implode(' | ');
        $baggageInfo = $priceDetails
            ->flatMap(fn ($detail) => Arr::wrap(Arr::get($detail, 'bagInfo', [])))
            ->filter()
            ->implode(' | ');
        $fareBreakdown = $priceDetails
            ->map(fn ($detail) => [
                'pax_type' => Arr::get($detail, 'paxType'),
                'base_fare' => $this->formatCurrency(Arr::get($detail, 'baseFare')),
                'tax' => $this->formatCurrency(Arr::get($detail, 'tax')),
                'total_fare' => $this->formatCurrency(Arr::get($detail, 'totalFare')),
            ])
            ->all();

        $airlineIata = (string) Arr::get($journey, 'airlineID', Arr::get($firstFlight, 'airlineCode', ''));

        return [
            'airline' => $airlineIata ?: 'Flight Option',
            'airline_name' => $airlineIata ? AirlineNames::get($airlineIata) : 'Flight Option',
            'logo_url' => $airlineIata ? "https://airlabs.co/img/airline/s/{$airlineIata}.png" : null,
            'flight_numbers' => $flightNumbers,
            'duration' => $this->formatDuration(Arr::get($journey, 'jiDepartTime'), Arr::get($journey, 'jiArrivalTime')),
            'stops' => $flightDetails->count() > 1 ? ($flightDetails->count() - 1) . ' stop' . ($flightDetails->count() > 2 ? 's' : '') : 'Direct',
            'net_price' => $numericFare,
            'price' => $this->formatCurrency($totalPrice),
            'price_value' => $totalPrice,
            'start_time' => $this->formatTime(Arr::get($journey, 'jiDepartTime', Arr::get($firstFlight, 'fdDepartTime'))),
            'start_location' => Arr::get($journey, 'jiOrigin', Arr::get($firstFlight, 'fdOrigin')),
            'end_time' => $this->formatTime(Arr::get($journey, 'jiArrivalTime', Arr::get($lastFlight, 'fdArrivalTime'))),
            'end_location' => Arr::get($journey, 'jiDestination', Arr::get($lastFlight, 'fdDestination')),
            'class' => Arr::get($firstPriceSegment, 'classId', Arr::get($availableDetails->first() ?? [], 'flightClass')),
            'info' => collect([$priceInfo, $baggageInfo])->filter()->implode(' | '),
            'segments' => $segments->all(),
            'fare_breakdown' => $fareBreakdown,
            'journey_reference' => Arr::get($journey, 'journeyReference', ''),
            'passport_required' => (bool) (Arr::get($firstPriceSegment, 'passportRequired') ?? Arr::get($firstFlight, 'passportRequired', false)),
        ];
    }

    protected function post(string $endpoint, array $payload, bool $retry = true): array
    {
        $response = $this->client()
            ->post($endpoint, $this->withAuth($payload))
            ->throw()
            ->json();

        if (Str::upper((string) Arr::get($response, 'status')) === 'FAILED') {
            $message = (string) Arr::get($response, 'respMessage', 'H2H request failed.');

            if ($retry && $this->shouldRefreshToken($message)) {
                Cache::forget((string) config('services.h2h.access_token_cache_key'));

                return $this->post($endpoint, $payload, false);
            }

            throw new RuntimeException($message);
        }

        return is_array($response) ? $response : [];
    }

    protected function withAuth(array $payload): array
    {
        return array_merge($payload, [
            'userID' => $this->loginPayload()['userID'] ?? '',
            'accessToken' => $this->accessToken(),
        ]);
    }

    protected function accessToken(): string
    {
        $cacheKey = config('services.h2h.access_token_cache_key');
        $ttlMinutes = max((int) config('services.h2h.access_token_ttl_minutes', 10), 1);

        return Cache::remember($cacheKey, Carbon::now()->addMinutes($ttlMinutes), function () {
            $payload = $this->loginPayload();
            $payload['accessToken'] = '';

            $response = $this->client()
                ->post('Session/Login', $payload)
                ->throw()
                ->json();

            $token = (string) Arr::get($response, 'accessToken', '');

            if ($token === '') {
                throw new RuntimeException((string) Arr::get($response, 'respMessage', 'Unable to get H2H access token.'));
            }

            return $token;
        });
    }

    protected function loginPayload(): array
    {
        $path = config('services.h2h.login_payload_path');
        $fullPath = str_starts_with($path, DIRECTORY_SEPARATOR) || preg_match('/^[A-Za-z]:\\\\/', $path)
            ? $path
            : base_path($path);

        if (! is_file($fullPath)) {
            throw new RuntimeException('H2H login payload file not found.');
        }

        $payload = json_decode((string) file_get_contents($fullPath), true);

        if (! is_array($payload) || blank(Arr::get($payload, 'userID'))) {
            throw new RuntimeException('H2H login payload is invalid.');
        }

        return $payload;
    }

    protected function shouldRefreshToken(string $message): bool
    {
        return Str::contains(Str::lower($message), ['token', 'expired', 'unauthorized', 'login']);
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl(rtrim((string) config('services.h2h.base_url'), '/') . '/')
            ->acceptJson()
            ->asJson()
            ->timeout(20)
            ->connectTimeout(10);
    }

    protected function formatCurrency(mixed $amount): string
    {
        if (is_numeric($amount)) {
            return 'IDR ' . number_format((float) $amount, 0, ',', '.');
        }

        $numeric = preg_replace('/[^0-9.]/', '', (string) $amount);

        if ($numeric !== '') {
            return 'IDR ' . number_format((float) $numeric, 0, ',', '.');
        }

        return (string) $amount;
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
