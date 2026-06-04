<x-layouts.app
    :title="$visaProduct->name . ' | Alliago.id'"
    :description="$visaProduct->short_description ?: 'Ajukan ' . $visaProduct->name . ' dengan mudah bersama Alliago.id. Proses cepat, terpercaya, dan didukung tim profesional.'"
    ogType="product"
>

@push('meta')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $visaProduct->name }}",
    "description": "{{ $visaProduct->short_description ?: 'Layanan pengajuan visa ' . $visaProduct->name . ' bersama Alliago.id.' }}",
    "brand": {
        "@type": "Brand",
        "name": "Alliago.id"
    },
    "offers": {
        "@type": "Offer",
        "priceCurrency": "IDR",
        "price": "{{ $visaProduct->discount_price ?? $visaProduct->base_price }}",
        "availability": "https://schema.org/InStock",
        "url": "{{ url()->current() }}"
    }
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://www.alliago.id"},
        {"@type": "ListItem", "position": 2, "name": "Visa Catalog", "item": "https://www.alliago.id/visa"},
        {"@type": "ListItem", "position": 3, "name": "{{ $visaProduct->name }}", "item": "{{ url()->current() }}"}
    ]
}
</script>
@endpush
    <x-home.header />
    
    <div class="min-h-screen bg-white text-slate-900 pt-24 pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <a href="{{ route('visa.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#0361fc] hover:text-blue-700 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to visa catalog
            </a>

            <div class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px] xl:gap-12">
                <div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <img src="{{ $visaProduct->icon_url }}" alt="{{ $visaProduct->name }}" class="h-16 w-16 rounded-2xl object-cover border border-slate-100 shadow-sm shrink-0 mt-1">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">{{ $visaProduct->country->name }}</p>
                                    <h1 class="mt-2 text-4xl font-extrabold tracking-tight sm:text-5xl">{{ $visaProduct->name }}</h1>
                                    <p class="mt-4 max-w-2xl text-lg text-slate-600">{{ $visaProduct->short_description ?: 'This visa product is managed in the admin catalog and ready for client orders.' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex shrink-0 items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-[#0361fc] ring-1 ring-inset ring-[#0361fc]/10">{{ $visaProduct->type }}</span>
                        </div>

                        <div class="mt-10 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Processing time</p>
                                </div>
                                <p class="mt-2 text-lg font-bold text-slate-900">{{ $visaProduct->processing_time ?: 'Contact support' }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Stay duration</p>
                                </div>
                                <p class="mt-2 text-lg font-bold text-slate-900">{{ $visaProduct->stay_duration ?: 'Varies by product' }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Validity</p>
                                </div>
                                <p class="mt-2 text-lg font-bold text-slate-900">{{ $visaProduct->validity ?: 'See product terms' }}</p>
                            </div>
                        </div>

                        @if ($visaProduct->description)
                            <div class="prose prose-slate mt-10 max-w-none">
                                {!! $visaProduct->description !!}
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 grid gap-8">
                        <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                            <h2 class="text-2xl font-bold">Requirements</h2>
                            <div class="mt-6 grid gap-4">
                                @forelse ($visaProduct->requirements as $requirement)
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                        <h3 class="text-lg font-bold text-slate-900">{{ $requirement->title }}</h3>
                                        @if ($requirement->description)
                                            <p class="mt-2 text-sm text-slate-600">{{ $requirement->description }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">Requirements will appear here once configured in admin.</p>
                                @endforelse
                            </div>
                        </section>

                        <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                            <h2 class="text-2xl font-bold">Required Documents</h2>
                            <div class="mt-6 grid gap-4">
                                @forelse ($visaProduct->documents as $document)
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                        <div class="flex items-start justify-between gap-4">
                                            <h3 class="text-lg font-bold text-slate-900">{{ $document->name }}</h3>
                                            @if ($document->is_required)
                                                <span class="inline-flex shrink-0 items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-bold text-red-600 ring-1 ring-inset ring-red-600/10">Required</span>
                                            @endif
                                        </div>
                                        @if ($document->description)
                                            <p class="mt-2 text-sm text-slate-600">{{ $document->description }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">Document requirements will appear here once configured in admin.</p>
                                @endforelse
                            </div>
                        </section>

                        <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                            <h2 class="text-2xl font-bold">Process Steps</h2>
                            <div class="mt-6 grid gap-4">
                                @forelse ($visaProduct->processSteps as $step)
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                        <h3 class="text-lg font-bold text-slate-900">{{ $step->title }}</h3>
                                        @if ($step->description)
                                            <p class="mt-2 text-sm text-slate-600">{{ $step->description }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">Process steps will appear here once configured in admin.</p>
                                @endforelse
                            </div>
                        </section>

                        <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                            <h2 class="text-2xl font-bold">Frequently Asked Questions</h2>
                            <div class="mt-6 grid gap-4">
                                @forelse ($visaProduct->faqs as $faq)
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                        <h3 class="text-lg font-bold text-slate-900">{{ $faq->question }}</h3>
                                        <p class="mt-2 text-sm text-slate-600">{{ $faq->answer }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">FAQs will appear here once configured in admin.</p>
                                @endforelse
                            </div>
                        </section>
                    </div>
                </div>

                <aside>
                    <div class="sticky top-24 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Starting price</p>
                        @if ($visaProduct->discount_price)
                            <p class="mt-2 text-sm font-medium text-slate-400 line-through">IDR {{ number_format((float) $visaProduct->base_price, 0, ',', '.') }}</p>
                        @endif
                        <p class="mt-1 text-4xl font-extrabold tracking-tight text-slate-900">IDR {{ number_format((float) $visaProduct->discount_price ?: (float) $visaProduct->base_price, 0, ',', '.') }}</p>

                        @if ($visaProduct->promo_label)
                            <div class="mt-4 inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-600 ring-1 ring-inset ring-emerald-600/10">
                                {{ $visaProduct->promo_label }}
                            </div>
                        @endif

                        <div class="mt-8 space-y-3">
                            @if(auth()->check())
                                <a href="{{ route('client.applications.create', $visaProduct->slug) }}" class="flex w-full items-center justify-center rounded-full bg-[#0361fc] px-4 py-3.5 text-sm font-bold text-white transition hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20">
                                    Order now
                                </a>
                            @else
                                <a href="{{ route('client.login') }}" class="flex w-full items-center justify-center rounded-full bg-[#0361fc] px-4 py-3.5 text-sm font-bold text-white transition hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20">
                                    Login to order
                                </a>
                                <a href="{{ route('client.register') }}" class="flex w-full items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                                    Create account
                                </a>
                            @endif
                        </div>

                        <div class="mt-8 border-t border-slate-100 pt-6">
                            <h2 class="text-lg font-bold text-slate-900">Available Add-ons</h2>
                            <div class="mt-4 space-y-3">
                                @forelse ($visaProduct->addons as $addon)
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <h3 class="font-bold text-slate-900">{{ $addon->name }}</h3>
                                                @if ($addon->description)
                                                    <p class="mt-1 text-xs text-slate-600">{{ $addon->description }}</p>
                                                @endif
                                            </div>
                                            <span class="text-sm font-bold text-slate-900 whitespace-nowrap">IDR {{ number_format((float) $addon->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No add-ons configured yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-layouts.app>
