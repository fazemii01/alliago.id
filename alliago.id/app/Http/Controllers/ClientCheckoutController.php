<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class ClientCheckoutController extends Controller
{
    public function show(Application $application)
    {
        // Only allow checkout if status is pending_payment
        if ($application->status !== 'pending_payment') {
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
        if ($application->status !== 'pending_payment') {
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

            $path = $request->file('payment_proof')->store("payments/{$application->id}", 'public');

            // Update metadata with proof path
            $metadata = $application->metadata;
            $metadata['payment_proof_path'] = $path;
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
            
            // Reuse existing invoice if it exists
            if (isset($metadata['xendit_invoice_url'])) {
                return redirect($metadata['xendit_invoice_url']);
            }

            $secretKey = $paymentMethod->configuration['secret_key'] ?? null;
            if (!$secretKey) {
                return back()->with('error', 'Konfigurasi Xendit tidak valid.');
            }

            $amount = $application->visaProduct->discount_price ?? $application->visaProduct->base_price;
            
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($secretKey, '')
                ->post('https://api.xendit.co/v2/invoices', [
                    'external_id' => $application->reference_number,
                    'amount' => $amount,
                    'payer_email' => $application->traveler_email,
                    'description' => 'Pembayaran Visa: ' . $application->visaProduct->name,
                    'success_redirect_url' => route('client.dashboard'),
                    'failure_redirect_url' => route('client.applications.checkout', $application),
                    'currency' => 'IDR',
                ]);

            if ($response->successful()) {
                $invoice = $response->json();
                
                $metadata['xendit_invoice_url'] = $invoice['invoice_url'];
                $metadata['xendit_invoice_id'] = $invoice['id'];
                
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
