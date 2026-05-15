<?php

namespace App\Http\Controllers;

use App\Services\H2hFlightService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Throwable;

class FlightTicketController extends Controller
{
    public function __construct(
        protected H2hFlightService $h2hFlightService,
    ) {
    }

    public function index(Request $request)
    {
        $tripTypes = $this->h2hFlightService->tripTypes();
        $cities = [];
        $results = [];
        $error = null;

        try {
            $cities = $this->h2hFlightService->getCities();
        } catch (Throwable $throwable) {
            $error = $throwable->getMessage();
        }

        $filters = [
            'origin' => (string) $request->input('origin', ''),
            'destination' => (string) $request->input('destination', ''),
            'depart_date' => (string) $request->input('depart_date', now()->addWeek()->toDateString()),
            'return_date' => (string) $request->input('return_date', ''),
            'trip_type' => (string) $request->input('trip_type', 'O'),
            'adult' => (int) $request->input('adult', 1),
            'child' => (int) $request->input('child', 0),
            'infant' => (int) $request->input('infant', 0),
        ];

        if ($request->isMethod('post')) {
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
                $search = $this->h2hFlightService->search($filters);
                $results = Arr::get($search, 'results', []);

                if ($results === []) {
                    $error = 'Jadwal belum tersedia untuk rute atau tanggal ini. Silakan coba tanggal lain.';
                }
            } catch (Throwable $throwable) {
                $error = 'Pencarian tiket sedang sibuk. Silakan coba lagi dalam beberapa saat.';
            }
        }

        return view('landing.flights.index', [
            'cities' => $cities,
            'tripTypes' => $tripTypes,
            'filters' => $filters,
            'results' => $results,
            'error' => $error,
        ]);
    }
}
