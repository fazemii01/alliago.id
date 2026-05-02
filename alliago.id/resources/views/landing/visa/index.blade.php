<x-layouts.app title="Visa Catalog | Alliago.id">
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="mx-auto max-w-7xl px-6 py-10">
            <div class="max-w-3xl">
                <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Alliago.id Visa Catalog</p>
                <h1 class="mt-3 text-4xl font-semibold">Choose a visa product managed from the admin dashboard.</h1>
                <p class="mt-4 text-slate-300">The landing catalog now lives under the landing view structure, while product data remains fully managed from admin.</p>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($visaProducts as $visaProduct)
                    <a href="{{ route('visa.show', $visaProduct->slug) }}" class="rounded-3xl border border-white/10 bg-white/5 p-6 transition hover:border-amber-300/40 hover:bg-white/10">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-amber-300">{{ $visaProduct->country->name }}</p>
                                <h2 class="mt-2 text-2xl font-semibold">{{ $visaProduct->name }}</h2>
                            </div>
                            <span class="rounded-full border border-white/10 px-3 py-1 text-xs uppercase tracking-wide text-slate-300">{{ $visaProduct->type }}</span>
                        </div>

                        <div class="mt-5 grid gap-2 text-sm text-slate-300">
                            <p>Processing time: {{ $visaProduct->processing_time ?: 'Contact support' }}</p>
                            <p>Stay duration: {{ $visaProduct->stay_duration ?: 'Varies by visa type' }}</p>
                            <p>Validity: {{ $visaProduct->validity ?: 'See full details' }}</p>
                        </div>

                        <div class="mt-6 flex items-end justify-between gap-4">
                            <div>
                                @if ($visaProduct->discount_price)
                                    <p class="text-sm text-slate-500 line-through">IDR {{ number_format((float) $visaProduct->base_price, 0, ',', '.') }}</p>
                                @endif
                                <p class="text-2xl font-semibold text-white">IDR {{ number_format((float) $visaProduct->discount_price ?: (float) $visaProduct->base_price, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-sm font-medium text-amber-300">View details</span>
                        </div>
                    </a>
                @empty
                    <div class="rounded-3xl border border-dashed border-white/15 bg-white/5 p-8 text-sm text-slate-300 md:col-span-2 xl:col-span-3">
                        No active visa products yet. Publish them from the admin catalog first.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
