<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\FerryRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FerryTicketController extends Controller
{
    public function index()
    {
        $canOrder = auth()->check()
            && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'));

        $users = [];
        if ($canOrder) {
            $users = \App\Models\User::orderBy('name')->get(['id', 'name', 'email', 'phone'])->all();
        }

        return view('landing.ferry.index', [
            'routes'   => FerryRoute::where('is_active', true)->get(),
            'canOrder' => $canOrder,
            'users'    => $users,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $rules = [
            'route_id'        => ['required', 'integer', 'exists:ferry_routes,id'],
            'passenger_name'  => ['required', 'string', 'max:255'],
            'passenger_email' => ['required', 'email', 'max:255'],
            'passenger_phone' => ['required', 'string', 'max:50'],
            'travel_date'     => ['required', 'date', 'after_or_equal:today'],
            'passenger_count' => ['required', 'integer', 'min:1', 'max:10'],
        ];

        if (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))) {
            $rules['client_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);

        $route       = FerryRoute::findOrFail($validated['route_id']);
        $totalAmount = $route->price * $validated['passenger_count'];

        $metadata = [
            'type'          => 'ferry',
            'ferry_details' => [
                'route_id'         => $route->id,
                'origin'           => $route->origin,
                'destination'      => $route->destination,
                'travel_date'      => $validated['travel_date'],
                'passenger_count'  => $validated['passenger_count'],
                'price_per_person' => $route->price,
            ],
            'price_breakdown' => [
                'subtotal' => $totalAmount,
                'total'    => $totalAmount,
            ],
            'invoice_amount' => $totalAmount,
            'payment_status' => 'unpaid',
        ];

        $clientId = $validated['client_id'] ?? auth()->id();

        $application = Application::create([
            'user_id'          => $clientId,
            'visa_product_id'  => null,
            'reference_number' => 'FRT-' . strtoupper(Str::random(10)),
            'status'           => 'pending_payment',
            'traveler_name'    => $validated['passenger_name'],
            'traveler_email'   => $validated['passenger_email'],
            'traveler_phone'   => $validated['passenger_phone'],
            'metadata'         => $metadata,
        ]);

        ApplicationStatusLog::create([
            'application_id' => $application->id,
            'admin_id'       => null,
            'to_status'      => 'pending_payment',
            'message'        => 'Pesanan tiket ferry dibuat.',
        ]);

        return response()->json([
            'status'           => 'success',
            'reference_number' => $application->reference_number,
            'invoice_url'      => route('ferry.invoice', $application),
        ]);
    }

    public function invoice(Application $application)
    {
        abort_unless(($application->metadata['type'] ?? '') === 'ferry', 404);

        $invoiceAmount = $application->metadata['invoice_amount'] ?? null;

        return view('landing.ferry.invoice', compact('application', 'invoiceAmount'));
    }
}
