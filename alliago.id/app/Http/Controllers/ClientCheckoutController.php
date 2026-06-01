<?php

namespace App\Http\Controllers;

use App\Contracts\FileStorage;
use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class ClientCheckoutController extends Controller
{
    protected FileStorage $fileStorage;

    public function __construct(FileStorage $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }
    public function show(Application $application)
    {
        abort_unless($application->user_id === auth()->id() || auth()->user()->hasRole('admin'), 403);

        // Only allow checkout if status is pending_payment or payment_failed
        if (!in_array($application->status, ['pending_payment', 'payment_failed'])) {
            return redirect()->route('client.applications.show', $application)
                ->with('error', 'Checkout tidak tersedia untuk aplikasi ini.');
        }

        $paymentMethodId = $application->metadata['payment_method_id'] ?? null;
        $paymentMethod = $paymentMethodId ? PaymentMethod::find($paymentMethodId) : null;

        if (!$paymentMethod) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Metode pembayaran tidak valid.');
        }

        return view('client.applications.checkout', [
            'application' => $application,
            'paymentMethod' => $paymentMethod,
        ]);
    }

    public function store(Request $request, Application $application)
    {
        abort_unless($application->user_id === auth()->id() || auth()->user()->hasRole('admin'), 403);

        if (!in_array($application->status, ['pending_payment', 'payment_failed'])) {
            return back()->with('error', 'Checkout tidak tersedia untuk aplikasi ini.');
        }

        $paymentMethodId = $application->metadata['payment_method_id'] ?? null;
        $paymentMethod = $paymentMethodId ? PaymentMethod::find($paymentMethodId) : null;

        if (!$paymentMethod) {
            return back()->with('error', 'Metode pembayaran tidak valid.');
        }

        if ($paymentMethod->provider === 'manual') {
            $request->validate([
                'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            $path = $this->fileStorage->store($request->file('payment_proof'), "payments/{$application->id}");

            // Update metadata with proof path and lock amount
            $metadata = $application->metadata ?? [];
            $amount = $metadata['price_breakdown']['total'] ?? ($application->visaProduct ? ($application->visaProduct->discount_price ?? $application->visaProduct->base_price) : 0);
            $metadata['payment_proof_path'] = $path;
            $metadata['invoice_amount'] = $amount;
            $metadata['payment_status'] = 'pending_verification';
            $application->metadata = $metadata;
            
            // Advance status to draft (or we could have a 'pending_verification' status for manual payments)
            // Let's set it to pending_verification so admin can check it before it becomes draft.
            $application->status = 'pending_verification';
            $application->save();

            ApplicationStatusLog::create([
                'application_id' => $application->id,
                'to_status' => 'pending_verification',
                'message' => 'Bukti pembayaran manual berhasil diunggah. Menunggu verifikasi admin.',
            ]);

            return redirect()->route('client.applications.show', $application)
                ->with('status', 'Bukti pembayaran berhasil diunggah. Silakan tunggu verifikasi dari tim kami.');
        }

        if ($paymentMethod->provider === 'xendit') {
            $metadata = $application->metadata ?? [];
            // Use the calculated total from price_breakdown to include all fees, addons, and taxes
            $amount = $metadata['price_breakdown']['total'] ?? ($application->visaProduct ? ($application->visaProduct->discount_price ?? $application->visaProduct->base_price) : 0);
            
            // Reuse existing invoice if it exists
            if (isset($metadata['xendit_invoice_url'])) {
                // Ensure amount is locked
                if (!isset($metadata['invoice_amount'])) {
                    $metadata['invoice_amount'] = $amount;
                    $application->metadata = $metadata;
                    $application->save();
                }
                return redirect($metadata['xendit_invoice_url']);
            }

            $secretKey = $paymentMethod->configuration['secret_key'] ?? null;
            if (!$secretKey) {
                return back()->with('error', 'Konfigurasi Xendit tidak valid.');
            }

            $response = \Illuminate\Support\Facades\Http::withBasicAuth($secretKey, '')
                ->post('https://api.xendit.co/v2/invoices', [
                    'external_id' => $application->reference_number,
                    'amount' => $amount,
                    'payer_email' => $application->traveler_email,
                    'description' => 'Pembayaran ' . ($application->visaProduct ? 'Visa: ' . $application->visaProduct->name : 'Tiket Pesawat: ' . ($metadata['flight_details']['airline_name'] ?? 'Penerbangan')),
                    'success_redirect_url' => route('client.dashboard'),
                    'failure_redirect_url' => route('client.applications.checkout', $application),
                    'currency' => 'IDR',
                ]);

            if ($response->successful()) {
                $invoice = $response->json();
                
                $metadata['xendit_invoice_url'] = $invoice['invoice_url'];
                $metadata['xendit_invoice_id'] = $invoice['id'];
                $metadata['invoice_amount'] = $amount;
                
                $application->metadata = $metadata;
                $application->save();

                return redirect($invoice['invoice_url']);
            }

            \Illuminate\Support\Facades\Log::error('Xendit Invoice Creation Failed: ' . $response->body());
            return back()->with('error', 'Gagal membuat invoice Xendit. Silakan coba lagi nanti.');
        }

        return back();
    }
}
