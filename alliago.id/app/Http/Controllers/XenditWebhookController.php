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
            if ($application->status === 'pending_payment') {
                $application->status = 'draft';
                $application->save();

                ApplicationStatusLog::create([
                    'application_id' => $application->id,
                    'to_status' => 'draft',
                    'message' => 'Pembayaran Xendit berhasil. Aplikasi sekarang dalam status draft untuk kelengkapan dokumen.',
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
