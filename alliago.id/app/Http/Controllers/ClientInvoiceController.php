<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ClientInvoiceController extends Controller
{
    public function show(Application $application)
    {
        $metadata = $application->metadata ?? [];
        
        // Recover invoice_amount cleanly, preferring locked invoice_amount, then breakdown, then flight_details
        $invoiceAmount = null;
        if (!empty($metadata['invoice_amount']) && (float) $metadata['invoice_amount'] > 0) {
            $invoiceAmount = (float) $metadata['invoice_amount'];
        } elseif (!empty($metadata['price_breakdown']['total']) && (float) $metadata['price_breakdown']['total'] > 0) {
            $invoiceAmount = (float) $metadata['price_breakdown']['total'];
        } elseif (!empty($metadata['flight_details']['total']) && (float) $metadata['flight_details']['total'] > 0) {
            $invoiceAmount = (float) $metadata['flight_details']['total'];
        } elseif (!empty($metadata['flight_details']['price_value'])) {
            $ticketPrice = (float) ($metadata['flight_details']['price_value'] ?? 0);
            $extraBag = (float) ($metadata['flight_details']['extra_baggage_price'] ?? 0);
            $tax = (float) ($metadata['flight_details']['tax'] ?? 0);
            $invoiceAmount = $ticketPrice + $extraBag + $tax;
        } elseif ($application->visaProduct) {
            $invoiceAmount = (float) ($application->visaProduct->discount_price ?? $application->visaProduct->base_price);
        }

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
