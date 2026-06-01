<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $externalId = $request->input('external_id');
        $status = $request->input('status');

        if (!$externalId) {
            return response()->json(['error' => 'Missing external_id'], 400);
        }

        $application = Application::where('reference_number', $externalId)->first();

        if (!$application) {
            return response()->json(['error' => 'Application not found'], 404);
        }

        $paymentMethodId = $application->metadata['payment_method_id'] ?? null;
        $paymentMethod = $paymentMethodId ? PaymentMethod::find($paymentMethodId) : null;

        if (!$paymentMethod || $paymentMethod->provider !== 'xendit') {
            return response()->json(['error' => 'Invalid payment method for this application'], 400);
        }

        $webhookToken = $paymentMethod->configuration['webhook_token'] ?? null;

        if ($webhookToken && $request->header('x-callback-token') !== $webhookToken) {
            Log::warning('Xendit webhook invalid token for application: ' . $externalId);
            return response()->json(['error' => 'Invalid callback token'], 403);
        }

        if (in_array($status, ['PAID', 'SETTLED'])) {
            $paidAmount = $request->input('paid_amount') ?? $request->input('amount');

            if (in_array($application->status, ['pending_payment', 'payment_failed'])) {
                $metadata = $application->metadata ?? [];
                $expectedAmount = $metadata['price_breakdown']['total'] ?? ($application->visaProduct ? ($application->visaProduct->discount_price ?? $application->visaProduct->base_price) : 0);
                
                if ($paidAmount < $expectedAmount) {
                    $application->status = 'payment_failed';
                    $application->save();

                    ApplicationStatusLog::create([
                        'application_id' => $application->id,
                        'to_status' => 'payment_failed',
                        'message' => 'Pembayaran kurang dari jumlah tagihan (Dibayar: Rp ' . number_format($paidAmount, 0, ',', '.') . '). Silakan hubungi admin.',
                    ]);
                } else {
                    $application->status = 'draft';

                    if ($paidAmount) {
                        // Force the actual paid amount to ensure accuracy
                        $metadata['invoice_amount'] = $paidAmount;
                    }
                    $metadata['payment_status'] = 'paid';
                    $application->metadata = $metadata;

                    $application->save();

                    ApplicationStatusLog::create([
                        'application_id' => $application->id,
                        'to_status' => 'draft',
                        'message' => 'Pembayaran Xendit berhasil. Aplikasi sekarang dalam status draft untuk kelengkapan dokumen.',
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
