<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ClientInvoiceController extends Controller
{
    public function show(Application $application)
    {
        // invoice_amount is locked at checkout time. If missing (legacy records),
        // use price_breakdown total. NEVER fall back to current product price,
        // as that price may have changed since the user paid.
        $metadata = $application->metadata ?? [];
        $invoiceAmount = $metadata['invoice_amount']
            ?? $metadata['price_breakdown']['total']
            ?? null; // null means we could not determine the paid amount

        $currency = $metadata['price_breakdown']['currency'] ?? $metadata['currency'] ?? 'IDR';
        $isMyr = in_array(strtoupper($currency), ['MYR', 'RM']);

        // Safeguard for legacy or unconverted invoices: if currency is MYR but amount is in IDR magnitude (>= 50,000)
        if ($isMyr && $invoiceAmount && $invoiceAmount >= 50000) {
            $rate = \App\Models\VisaSetting::getIdrToTargetRate('MYR');
            $rate = $rate > 0 ? $rate : 3450;
            $invoiceAmount = round($invoiceAmount / $rate);
        }

        return view('client.applications.invoice', [
            'application' => $application,
            'invoiceAmount' => $invoiceAmount,
        ]);
    }
}
