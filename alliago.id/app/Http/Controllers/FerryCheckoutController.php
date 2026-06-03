<?php

namespace App\Http\Controllers;

use App\Contracts\FileStorage;
use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FerryCheckoutController extends Controller
{
    public function __construct(protected FileStorage $fileStorage) {}

    public function show(Application $application)
    {
        abort_unless(($application->metadata['type'] ?? '') === 'ferry', 404);

        if (!in_array($application->status, ['pending_payment', 'payment_failed'])) {
            return redirect()->route('ferry.invoice', $application)
                ->with('error', 'Checkout tidak tersedia untuk pesanan ini.');
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $selectedMethod = null;

        $selectedMethodId = $application->metadata['payment_method_id'] ?? null;
        if ($selectedMethodId) {
            $selectedMethod = PaymentMethod::find($selectedMethodId);
        }

        return view('landing.ferry.checkout', compact('application', 'paymentMethods', 'selectedMethod'));
    }

    public function store(Request $request, Application $application)
    {
        abort_unless(($application->metadata['type'] ?? '') === 'ferry', 404);

        if (!in_array($application->status, ['pending_payment', 'payment_failed'])) {
            return back()->with('error', 'Checkout tidak tersedia untuk pesanan ini.');
        }

        // Handle manual payment proof upload
        if ($request->hasFile('payment_proof')) {
            $request->validate([
                'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            ]);

            $path = $this->fileStorage->store($request->file('payment_proof'), "payments/ferry/{$application->id}");

            $metadata = $application->metadata ?? [];
            $metadata['payment_proof_path'] = $path;
            $metadata['payment_status']     = 'pending_verification';
            $application->metadata          = $metadata;
            $application->status            = 'pending_verification';
            $application->save();

            ApplicationStatusLog::create([
                'application_id' => $application->id,
                'admin_id'       => null,
                'to_status'      => 'pending_verification',
                'message'        => 'Bukti pembayaran ferry diunggah. Menunggu verifikasi admin.',
            ]);

            return redirect()->route('ferry.invoice', $application)
                ->with('status', 'Bukti pembayaran berhasil diunggah. Tim kami akan memverifikasi dalam 1×24 jam.');
        }

        // Handle payment method selection
        $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
        ]);

        $paymentMethod = PaymentMethod::findOrFail($request->input('payment_method_id'));
        $metadata      = $application->metadata ?? [];
        $amount        = $metadata['invoice_amount'] ?? $metadata['price_breakdown']['total'] ?? 0;

        $metadata['payment_method_id'] = $paymentMethod->id;
        $application->metadata         = $metadata;
        $application->save();

        if ($paymentMethod->provider === 'manual') {
            return redirect()->route('ferry.checkout', $application);
        }

        if ($paymentMethod->provider === 'xendit') {
            if (isset($metadata['xendit_invoice_url'])) {
                return redirect($metadata['xendit_invoice_url']);
            }

            $secretKey = $paymentMethod->configuration['secret_key'] ?? null;
            if (!$secretKey) {
                return back()->with('error', 'Konfigurasi Xendit tidak valid.');
            }

            $ferry = $metadata['ferry_details'] ?? [];

            try {
                $response = Http::withBasicAuth($secretKey, '')
                    ->post('https://api.xendit.co/v2/invoices', [
                        'external_id'          => $application->reference_number,
                        'amount'               => $amount,
                        'payer_email'          => $application->traveler_email,
                        'description'          => 'Tiket Ferry: ' . ($ferry['origin'] ?? '') . ' → ' . ($ferry['destination'] ?? ''),
                        'success_redirect_url' => route('ferry.invoice', $application),
                        'failure_redirect_url' => route('ferry.checkout', $application),
                        'currency'             => 'IDR',
                    ]);

                if ($response->successful()) {
                    $invoice = $response->json();

                    $metadata['xendit_invoice_url'] = $invoice['invoice_url'];
                    $metadata['xendit_invoice_id']  = $invoice['id'];
                    $metadata['invoice_amount']     = $amount;
                    $application->metadata          = $metadata;
                    $application->save();

                    return redirect($invoice['invoice_url']);
                }

                Log::error('Xendit Ferry Invoice Failed: ' . $response->body());
                return back()->with('error', 'Gagal membuat pembayaran. Silakan coba lagi.');
            } catch (\Throwable $e) {
                Log::error('Xendit Ferry Exception: ' . $e->getMessage());
                return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            }
        }

        return back();
    }
}
