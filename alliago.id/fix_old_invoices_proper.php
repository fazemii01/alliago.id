<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Application;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Http;

echo "=== Fixing Historical Invoice Amounts ===\n\n";

// Get all paid/completed applications that don't have a locked invoice_amount
$applications = Application::whereNotIn('status', ['pending_payment', 'draft_unsubmitted'])
    ->get();

$fixed = 0;
$skipped = 0;
$errors = 0;

foreach ($applications as $application) {
    $metadata = $application->metadata ?? [];

    // Skip if already locked correctly
    if (isset($metadata['invoice_amount']) && $metadata['invoice_amount'] > 0) {
        echo "[SKIP] {$application->reference_number} already has invoice_amount = {$metadata['invoice_amount']}\n";
        $skipped++;
        continue;
    }

    // Try to fetch the real paid amount from Xendit API
    $xenditInvoiceId = $metadata['xendit_invoice_id'] ?? null;
    $paymentMethodId = $metadata['payment_method_id'] ?? null;
    $paymentMethod = $paymentMethodId ? PaymentMethod::find($paymentMethodId) : null;
    $secretKey = $paymentMethod ? ($paymentMethod->configuration['secret_key'] ?? null) : null;

    if ($xenditInvoiceId && $secretKey) {
        // Fetch invoice from Xendit to get the actual amount paid
        $response = Http::withBasicAuth($secretKey, '')
            ->get("https://api.xendit.co/v2/invoices/{$xenditInvoiceId}");

        if ($response->successful()) {
            $invoice = $response->json();
            // Xendit uses 'paid_amount' or 'amount' field
            $paidAmount = $invoice['paid_amount'] ?? $invoice['amount'] ?? null;

            if ($paidAmount) {
                $metadata['invoice_amount'] = $paidAmount;
                $application->metadata = $metadata;
                $application->save();
                echo "[FIXED via Xendit API] {$application->reference_number} => Rp " . number_format($paidAmount, 0, ',', '.') . "\n";
                $fixed++;
                continue;
            }
        } else {
            echo "[WARN] Could not fetch from Xendit for {$application->reference_number}: " . $response->body() . "\n";
        }
    }

    // Fallback: use price_breakdown.total if available (the amount the user agreed to at checkout)
    if (isset($metadata['price_breakdown']['total'])) {
        $amount = $metadata['price_breakdown']['total'];
        $metadata['invoice_amount'] = $amount;
        $application->metadata = $metadata;
        $application->save();
        echo "[FIXED via price_breakdown] {$application->reference_number} => Rp " . number_format($amount, 0, ',', '.') . "\n";
        $fixed++;
        continue;
    }

    // Last resort: use the current product price (least accurate for historical)
    $amount = $application->visaProduct->discount_price ?? $application->visaProduct->base_price;
    $metadata['invoice_amount'] = $amount;
    $application->metadata = $metadata;
    $application->save();
    echo "[FIXED via current price (fallback)] {$application->reference_number} => Rp " . number_format($amount, 0, ',', '.') . "\n";
    $fixed++;
}

echo "\n=== Done ===\n";
echo "Fixed: {$fixed} | Skipped (already correct): {$skipped} | Errors: {$errors}\n";
