<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\VisaProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClientApplicationController extends Controller
{
    public function show(Application $application): View
    {
        abort_unless($application->user_id === auth()->id(), 403);

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

        return view('client.applications.create', [
            'visaProduct' => $visaProduct->load(['country', 'documents']),
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
        ]);

        $application = Application::create([
            'user_id' => $request->user()->id,
            'visa_product_id' => $visaProduct->id,
            'reference_number' => 'GP-'.strtoupper(Str::random(8)),
            'status' => 'draft',
            'traveler_name' => $data['traveler_name'],
            'traveler_email' => $data['traveler_email'],
            'traveler_phone' => $data['traveler_phone'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        ApplicationStatusLog::create([
            'application_id' => $application->id,
            'to_status' => 'draft',
            'message' => 'Order created by user and ready for document submission.',
        ]);

        foreach ($visaProduct->documents as $document) {
            $application->documents()->create([
                'visa_document_id' => $document->id,
                'label' => $document->name,
                'file_path' => '',
                'status' => $document->is_required ? 'pending_upload' : 'optional',
            ]);
        }

        return redirect()
            ->route('client.dashboard')
            ->with('status', 'Order created. You can now continue with document follow-up from your client area.');
    }
}
