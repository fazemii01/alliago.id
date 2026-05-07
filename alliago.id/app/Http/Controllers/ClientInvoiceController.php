<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ClientInvoiceController extends Controller
{
    public function show(Application $application)
    {
        // Ensure user is authorized to view this invoice
        if ($application->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        // invoice_amount is locked at checkout time. If missing (legacy records),
        // use price_breakdown total. NEVER fall back to current product price,
        // as that price may have changed since the user paid.
        $metadata = $application->metadata ?? [];
        $invoiceAmount = $metadata['invoice_amount']
            ?? $metadata['price_breakdown']['total']
            ?? null; // null means we could not determine the paid amount

        return view('client.applications.invoice', [
            'application' => $application,
            'invoiceAmount' => $invoiceAmount,
        ]);
    }
}
