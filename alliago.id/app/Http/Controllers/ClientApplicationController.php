<?php

namespace App\Http\Controllers;

use App\Contracts\FileStorage;
use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\VisaProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClientApplicationController extends Controller
{
    protected FileStorage $fileStorage;

    public function __construct(FileStorage $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }
    public function show(Application $application): View
    {
        abort_unless($application->user_id === auth()->id() || auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'), 403);

        if ($application->status === 'pending_payment') {
            return redirect()->route('client.applications.checkout', $application);
        }

        $application->messages()
            ->where('is_admin', true)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('client.applications.show', [
            'application' => $application->load([
                'visaProduct.country',
                'documents',
                'messages.sender',
                'statusLogs',
            ]),
            'client' => auth()->user(),
        ]);
    }

    public function create(VisaProduct $visaProduct): View
    {
        abort_unless($visaProduct->is_active, 404);

        $visaProduct->load(['country', 'documents', 'addons']);

        $availableAddons = $visaProduct->addons->map(fn($a) => [
            'id' => $a->id,
            'name' => $a->name,
            'description' => $a->description,
            'price' => (float) $a->display_price,
            'is_active' => $a->is_active,
            'sort_order' => $a->sort_order,
        ]);

        return view('client.applications.create', [
            'visaProduct' => $visaProduct,
            'availableAddons' => $availableAddons,
            'paymentMethods' => \App\Models\PaymentMethod::where('is_active', true)->get(),
            'client' => auth()->user(),
        ]);
    }

    public function store(Request $request, VisaProduct $visaProduct): RedirectResponse
    {
        abort_unless($visaProduct->is_active, 404);

        $data = $request->validate([
            'traveler_name' => ['required', 'string', 'max:255'],
            'traveler_email' => ['required', 'email', 'max:255'],
            'traveler_phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'departure_date' => ['nullable', 'date'],
            'delivery_method' => ['required', 'in:soft_file,hard_file'],
            'hard_file_pickup' => ['nullable', 'required_if:delivery_method,hard_file', 'in:kurir,sendiri'],
            'hard_file_delivery' => ['nullable', 'required_if:delivery_method,hard_file', 'in:kurir,sendiri'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
            'addons' => ['nullable', 'array'],
            'addons.*' => ['exists:visa_addons,id'],
            'processing_time_type' => ['required', 'string'],
            'subtotal' => ['required', 'numeric'],
            'tax' => ['required', 'numeric'],
            'total' => ['required', 'numeric'],
        ]);

        $application = Application::create([
            'user_id' => $request->user()->id,
            'visa_product_id' => $visaProduct->id,
            'reference_number' => 'GP-'.strtoupper(Str::random(8)),
            'status' => 'pending_payment',
            'traveler_name' => $data['traveler_name'],
            'traveler_email' => $data['traveler_email'],
            'traveler_phone' => $data['traveler_phone'] ?? null,
            'notes' => $data['notes'] ?? null,
            'metadata' => [
                'departure_date' => $data['departure_date'] ?? null,
                'delivery_method' => $data['delivery_method'],
                'hard_file_pickup' => $data['hard_file_pickup'] ?? null,
                'hard_file_delivery' => $data['hard_file_delivery'] ?? null,
                'payment_method_id' => $data['payment_method_id'],
                'addons' => $data['addons'] ?? [],
                'processing_time_type' => $data['processing_time_type'],
                'price_breakdown' => [
                    'subtotal' => $data['subtotal'],
                    'tax' => $data['tax'],
                    'total' => $data['total'],
                    'currency' => \App\Models\VisaSetting::current()->currency,
                ],
                'payment_status' => 'unpaid',
            ]
        ]);

        ApplicationStatusLog::create([
            'application_id' => $application->id,
            'to_status' => 'pending_payment',
            'message' => 'Order created. Menunggu proses pembayaran.',
        ]);

        foreach ($visaProduct->documents as $document) {
            $filePath = '';
            $status = $document->is_required ? 'pending_upload' : 'optional';

            if ($request->hasFile("documents.{$document->id}")) {
                $filePath = $this->fileStorage->store($request->file("documents.{$document->id}"), "applications/{$application->id}");
                $status = 'uploaded';
            }

            $application->documents()->create([
                'visa_document_id' => $document->id,
                'label' => $document->name,
                'file_path' => $filePath,
                'status' => $status,
            ]);
        }

        return redirect()
            ->route('client.applications.checkout', $application)
            ->with('status', 'Order created. Silakan selesaikan pembayaran Anda.');
    }
}
