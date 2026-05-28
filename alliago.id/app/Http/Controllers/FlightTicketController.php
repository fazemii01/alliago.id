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

                if ($results === []) {
                    $error = 'Jadwal belum tersedia untuk rute atau tanggal ini. Silakan coba tanggal lain.';
                }

                $paginatedResults = $this->paginateResults(collect($results), $request);
            } catch (Throwable $throwable) {
                $error = 'Pencarian tiket sedang sibuk. Silakan coba lagi dalam beberapa saat.';
            }
        }

        return view('landing.flights.index', [
            'tripTypes' => $tripTypes,
            'filters' => $filters,
            'results' => $results,
            'paginatedResults' => $paginatedResults,
            'error' => $error,
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
}
