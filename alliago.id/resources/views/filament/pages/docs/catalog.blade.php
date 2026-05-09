@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">🗺️ Visa Catalog</h2>
        <p class="{{ $p }} text-gray-500">Manage countries and the visa products clients can order.</p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Countries</h3>
        <p class="{{ $p }}">
            Countries form the foundation of the catalog. Every Visa Product must be linked to a Country.
            Each country has a <strong>name</strong>, <strong>slug</strong>, <strong>flag emoji</strong>, <strong>ISO code</strong>, and an <strong>active</strong> toggle.
        </p>
        <ul class="space-y-1.5 text-sm text-gray-600 list-disc list-inside">
            <li>Flag emojis are seeded automatically based on country name — you can override them in the edit form.</li>
            <li>Inactive countries are hidden from the public website but remain in the database.</li>
            <li>Deleting a country will also affect all its linked visa products — be careful.</li>
        </ul>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Visa Products</h3>
        <p class="{{ $p }}">
            Visa Products are the individual services clients can purchase. Each product belongs to one Country and includes:
        </p>
        <div class="grid grid-cols-2 gap-2 text-sm">
            @foreach([
                ['Name', 'The full service name shown to clients.'],
                ['Slug', 'URL-friendly identifier (auto-generated).'],
                ['Base Price', 'The standard price before any discount.'],
                ['Discount Price', 'Optional discounted price shown with a strikethrough of base price.'],
                ['Processing Time', 'e.g. "3-5 business days" — shown on product page.'],
                ['Short Description', 'Brief summary shown in catalog cards.'],
                ['Sort Order', 'Controls display order in the catalog.'],
                ['Active', 'Toggle visibility on the public website.'],
            ] as [$field, $desc])
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-3">
                <p class="font-semibold text-gray-800">{{ $field }}</p>
                <p class="text-gray-500 mt-0.5 text-xs">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Pricing Tips</h3>
        <ul class="space-y-2 text-sm text-gray-600 list-disc list-inside">
            <li>If a <strong>Discount Price</strong> is set and lower than the Base Price, it is shown as the active price with the original crossed out.</li>
            <li>When a client purchases, the invoice amount is locked at the time of payment — future price changes won't affect existing invoices.</li>
            <li>All prices are in <strong>IDR (Indonesian Rupiah)</strong>.</li>
        </ul>
    </div>

</div>
