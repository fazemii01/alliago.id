@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">💰 Finance</h2>
        <p class="{{ $p }} text-gray-500">Invoices, payment tracking, and payment method configuration.</p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Invoices</h3>
        <p class="{{ $p }}">
            Invoices are auto-generated when a client completes a payment. The <strong>Finance → Invoices</strong> page
            is a read-only view — you cannot edit or delete invoices to preserve financial integrity.
        </p>
        <p class="{{ $p }}">
            Each invoice shows: <strong>Invoice ID</strong> (= application reference), <strong>Customer</strong>,
            <strong>Description</strong> (visa product), <strong>Amount</strong>, <strong>Date</strong>, and <strong>Status</strong> (Paid / Unpaid).
        </p>
        <div class="rounded-lg bg-amber-50 border border-amber-100 p-3 text-sm text-amber-800">
            ⚠️ Invoice amounts are <strong>locked at the time of payment</strong>. Changing visa product prices later will not affect historical invoices.
        </div>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Payment Statuses</h3>
        <div class="space-y-2">
            @foreach([
                ['Unpaid (Pending Payment)', 'yellow', 'Client has placed the order but payment has not been received yet.'],
                ['Paid', 'emerald', 'Payment confirmed via the payment gateway webhook. Application processing begins.'],
            ] as [$status, $color, $desc])
            <div class="flex items-start gap-3">
                <span class="inline-block rounded-md px-2 py-0.5 text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-700 shrink-0 mt-0.5">{{ $status }}</span>
                <p class="text-sm text-gray-500">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Filtering Invoices</h3>
        <p class="{{ $p }}">
            Use the <strong>Date Range</strong> filter (From / Until) on the Invoices table to narrow down records to a specific time period —
            useful for monthly reconciliation or generating reports.
        </p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Payment Methods</h3>
        <p class="{{ $p }}">
            Go to <strong>Finance → Payment Methods</strong> to configure available payment options.
            Alliago.id uses <strong>Xendit</strong> as the payment gateway. Webhooks are handled automatically —
            when Xendit confirms a payment, the application status is updated and the invoice amount is locked.
        </p>
        <ul class="space-y-1.5 text-sm text-gray-600 list-disc list-inside">
            <li>Each payment method has a name, logo, and toggle to enable/disable it.</li>
            <li>Disabled payment methods are hidden from the client checkout page.</li>
            <li>Gateway credentials (API keys) are configured via the <code class="bg-gray-100 px-1 rounded text-xs">.env</code> file — contact your developer to change them.</li>
        </ul>
    </div>

</div>
