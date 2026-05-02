<x-layouts.app title="Visa Catalog | Alliago.id">
    <x-home.header />
    
    <div class="min-h-screen bg-white text-slate-900 pt-24 pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl">
                <p class="text-sm uppercase tracking-[0.2em] font-bold text-[#0361fc]">Alliago.id Visa Catalog</p>
                <h1 class="mt-3 text-4xl font-extrabold tracking-tight sm:text-5xl">Choose a visa product.</h1>
                <p class="mt-4 text-lg text-slate-600">Explore our range of visa options designed to meet your travel needs.</p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($visaProducts as $visaProduct)
                    <a href="{{ route('visa.show', $visaProduct->slug) }}" class="group relative flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#0361fc]/30 hover:shadow-xl hover:shadow-[#0361fc]/5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $visaProduct->country->name }}</p>
                                <h2 class="mt-1 text-xl font-bold leading-tight text-slate-900 group-hover:text-[#0361fc] transition-colors">{{ $visaProduct->name }}</h2>
                            </div>
                            <span class="inline-flex shrink-0 items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#0361fc] ring-1 ring-inset ring-[#0361fc]/10">{{ $visaProduct->type }}</span>
                        </div>

                        <div class="mt-6 flex flex-col gap-3 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p>Processing time: <span class="font-medium text-slate-900">{{ $visaProduct->processing_time ?: 'Contact support' }}</span></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <p>Stay duration: <span class="font-medium text-slate-900">{{ $visaProduct->stay_duration ?: 'Varies by visa type' }}</span></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p>Validity: <span class="font-medium text-slate-900">{{ $visaProduct->validity ?: 'See full details' }}</span></p>
                            </div>
                        </div>

                        <div class="mt-auto pt-8 flex items-end justify-between gap-4">
                            <div>
                                @if ($visaProduct->discount_price)
                                    <p class="text-xs font-medium text-slate-400 line-through">IDR {{ number_format((float) $visaProduct->base_price, 0, ',', '.') }}</p>
                                @endif
                                <p class="text-xl font-bold text-slate-900">IDR {{ number_format((float) $visaProduct->discount_price ?: (float) $visaProduct->base_price, 0, ',', '.') }}</p>
                            </div>
                            <span class="inline-flex items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-xs font-bold text-white transition-colors group-hover:bg-[#0361fc]">
                                View details
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center sm:col-span-2 lg:col-span-3">
                        <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">No visas found</h3>
                        <p class="mt-2 text-sm text-slate-500">No active visa products are currently available. Please check back later or publish them from the admin dashboard.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <x-home.footer />
</x-layouts.app>
