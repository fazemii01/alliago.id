<x-layouts.app
    title="Visa Catalog | Alliago.id — Temukan Visa yang Tepat untuk Anda"
    description="Temukan berbagai produk visa untuk Japan, Korea, Australia, Schengen, dan 50+ negara lainnya. Bandingkan harga, persyaratan, dan waktu proses. Ajukan sekarang bersama Alliago.id."
>
    <x-home.header />

    <div class="min-h-screen bg-white text-slate-900 pt-24 pb-16" x-data="visaFilter()">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">

            {{-- Header: title left, search+filter bar right --}}
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] font-bold text-[#0361fc]">Alliago.id Visa Catalog</p>
                    <h1 class="mt-3 text-4xl font-extrabold tracking-tight sm:text-5xl">Choose a visa product.</h1>
                    <p class="mt-4 text-lg text-slate-600">Explore our range of visa options designed to meet your travel needs.</p>
                </div>

                {{-- Search + Filter + Button bar --}}
                <div class="flex shrink-0 items-center gap-2">

                    {{-- Search input --}}
                    <div class="relative">
                        <input
                            x-model="query"
                            @keydown.enter.prevent="doSearch()"
                            type="text"
                            placeholder="Cari visa atau negara…"
                            class="w-56 sm:w-64 rounded-2xl border border-slate-200 bg-slate-50 py-2.5 pl-4 pr-4 text-sm font-medium text-slate-800 shadow-sm outline-none placeholder:font-normal placeholder:text-slate-400 focus:border-[#0361fc] focus:bg-white focus:ring-2 focus:ring-[#0361fc]/20"
                        >
                    </div>

                    {{-- Filter dropdown (no nested x-data — all state in parent visaFilter scope) --}}
                    @if (!empty($types))
                    <div class="relative">
                        <button type="button"
                                @click="filterOpen = !filterOpen"
                                class="relative flex items-center gap-1.5 rounded-2xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-[#0361fc] hover:text-[#0361fc]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                            Filter
                            <span x-show="pendingTypes.length > 0"
                                  x-text="pendingTypes.length"
                                  class="absolute -right-1.5 -top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-[#0361fc] text-[10px] font-bold text-white"></span>
                        </button>

                        <div x-show="filterOpen"
                             @click.outside="filterOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 top-full z-50 mt-2 w-48 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Tipe Visa</p>
                                <button type="button"
                                        x-show="pendingTypes.length > 0"
                                        @click="pendingTypes.splice(0)"
                                        class="text-[11px] font-semibold text-[#0361fc] hover:underline">Reset</button>
                            </div>
                            <div class="space-y-2.5">
                                @foreach ($types as $type)
                                <label class="flex cursor-pointer items-center gap-2.5">
                                    <input type="checkbox"
                                           value="{{ $type }}"
                                           @change="toggleType('{{ $type }}')"
                                           :checked="pendingTypes.includes('{{ $type }}')"
                                           class="h-4 w-4 rounded border-slate-300 text-[#0361fc] focus:ring-[#0361fc]">
                                    <span class="text-sm font-medium text-slate-700">{{ $type }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Search button --}}
                    <button type="button"
                            @click="doSearch()"
                            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#0361fc] text-white shadow-lg shadow-[#0361fc]/30 transition hover:bg-blue-700 active:scale-95">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z"/></svg>
                    </button>

                </div>
            </div>

            {{-- Active filter pills --}}
            <div x-show="activeQuery || activeTypes.length > 0"
                 class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold text-slate-400">Filter aktif:</span>
                <span x-show="activeQuery"
                      class="inline-flex items-center gap-1.5 rounded-full bg-[#EDF4FF] px-3 py-1 text-xs font-bold text-[#0361fc]">
                    "<span x-text="activeQuery"></span>"
                    <button @click="removeQuery()" class="hover:text-blue-800">×</button>
                </span>
                <template x-for="t in activeTypes" :key="t">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#EDF4FF] px-3 py-1 text-xs font-bold text-[#0361fc]">
                        <span x-text="t"></span>
                        <button @click="removeType(t)" class="hover:text-blue-800">×</button>
                    </span>
                </template>
                <button @click="clearAll()"
                        class="text-xs font-semibold text-slate-400 hover:text-slate-700 underline">
                    Hapus semua
                </button>
            </div>

            {{-- Cards grid (full width, no sidebar) --}}
            <div class="mt-8">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @forelse ($visaProducts as $visaProduct)
                            <a href="{{ route('visa.show', $visaProduct->slug) }}"
                               x-show="isVisible('{{ addslashes($visaProduct->name) }}', '{{ addslashes($visaProduct->country->name) }}', '{{ $visaProduct->type }}')"
                               x-transition:enter="transition ease-out duration-150"
                               x-transition:enter-start="opacity-0 scale-95"
                               x-transition:enter-end="opacity-100 scale-100"
                               class="group relative flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#0361fc]/30 hover:shadow-xl hover:shadow-[#0361fc]/5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        @if ($visaProduct->icon_image_path)
                                            <img src="{{ \Storage::url($visaProduct->icon_image_path) }}" alt="{{ $visaProduct->name }}" class="h-12 w-12 rounded-xl object-cover border border-slate-100 shadow-sm shrink-0">
                                        @endif
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $visaProduct->country->name }}</p>
                                            <h2 class="mt-1 text-xl font-bold leading-tight text-slate-900 group-hover:text-[#0361fc] transition-colors">{{ $visaProduct->name }}</h2>
                                        </div>
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
                            <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center sm:col-span-2 lg:col-span-3 xl:col-span-4">
                                <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                <h3 class="mt-4 text-lg font-bold text-slate-900">No visas found</h3>
                                <p class="mt-2 text-sm text-slate-500">No active visa products are currently available.</p>
                            </div>
                        @endforelse
                </div>

                @if ($visaProducts && $visaProducts->hasPages())
                    <div class="mt-8 flex items-center justify-center">
                        {{ $visaProducts->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <x-home.footer />

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('visaFilter', () => ({
                query: '{{ addslashes(request('query')) }}',
                pendingTypes: @json(request('types', [])),
                activeQuery: '{{ addslashes(request('query')) }}',
                activeTypes: @json(request('types', [])),
                filterOpen: false,
                doSearch() {
                    const params = new URLSearchParams();
                    if (this.query) {
                        params.set('query', this.query);
                    }
                    this.pendingTypes.forEach(t => {
                        params.append('types[]', t);
                    });
                    window.location.href = `${window.location.pathname}?${params.toString()}`;
                },
                removeQuery() {
                    this.query = '';
                    this.doSearch();
                },
                removeType(type) {
                    this.pendingTypes = this.pendingTypes.filter(x => x !== type);
                    this.doSearch();
                },
                clearAll() {
                    this.query = '';
                    this.pendingTypes = [];
                    window.location.href = window.location.pathname;
                },
                toggleType(type) {
                    const i = this.pendingTypes.indexOf(type);
                    i === -1 ? this.pendingTypes.push(type) : this.pendingTypes.splice(i, 1);
                },
                isVisible(name, country, type) {
                    // Since server-side filters the collection beforehand, we keep this for visual consistency
                    return true;
                },
            }));
        });
    </script>
    @endpush
</x-layouts.app>
