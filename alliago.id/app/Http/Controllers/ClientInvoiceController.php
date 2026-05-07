<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ClientInvoiceController extends Controller
{
    public function show(Application $application)
    {
        // Ensure user is authorized to view this invoice
        if ($application->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        // Only applications that have passed pending_payment or have an invoice can be viewed
        // Wait, even pending_payment is an unpaid invoice. We can view it.

        $invoiceAmount = $application->metadata['invoice_amount'] ?? ($application->visaProduct->discount_price ?? $application->visaProduct->base_price);

        return view('client.applications.invoice', [
            'application' => $application,
            'invoiceAmount' => $invoiceAmount,
        ]);
    }
}
