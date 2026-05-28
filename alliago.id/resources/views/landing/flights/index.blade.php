<x-layouts.app title="Tiket pesawat | Alliago.id">
    <x-home.header />

    @php
        $routeLabel       = $filters['origin_label']      ?: $filters['origin'];
        $destinationLabel = $filters['destination_label'] ?: $filters['destination'];
        $totalPassenger   = (int) $filters['adult'] + (int) $filters['child'] + (int) $filters['infant'];
        $hasSearch        = $routeLabel && $destinationLabel;
    @endphp

    {{-- ═══════════════════════════════════════════════════════════════════════
         HERO — full-screen background with text + search form overlaid
    ═══════════════════════════════════════════════════════════════════════════ --}}
    <section class="relative pt-24">
        <div class="page-wrapper">

            {{-- ─── Banner: Fuji image, contained + rounded, title overlaid ─── --}}
            <div class="relative h-[280px] overflow-hidden rounded-[32px] shadow-[0_20px_50px_rgba(15,23,42,0.18)] sm:h-[340px] lg:h-[380px]">
                <img
                    src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=85&w=2400&auto=format&fit=crop"
                    alt="Gunung Fuji"   
                    class="absolute inset-0 h-full w-full object-cover object-center"
                    loading="eager"
                >
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/55 via-slate-950/20 to-slate-950/45"></div>

                {{-- Title text over image --}}
                <div class="relative z-10 flex h-full items-start px-6 pt-8 sm:px-10 sm:pt-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-white/70">Tiket Pesawat</p>
                        <h1 class="mt-2 text-3xl font-extrabold leading-tight tracking-tight text-white drop-shadow sm:text-4xl lg:text-5xl">
                            Cari tiket pesawat
                        </h1>
                        <p class="mt-2 max-w-xl text-sm font-medium text-white/80 drop-shadow sm:text-base">
                            Jelajahi dunia — mulai dari Gunung Fuji hingga destinasi impianmu.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ─── Search form: floats over bottom edge of banner ──────────── --}}
            <div class="relative z-20 -mt-16 px-2 sm:-mt-20 sm:px-4">
                <form method="POST" action="{{ route('flights.index') }}"
                      x-data="flightSearchForm()">
                    @csrf
                    <input type="hidden" name="search" value="1">
                    <input type="hidden" name="trip_type" :value="tripType">

                    {{-- ── Canonical hidden inputs — always submitted, written by both mobile & desktop UI ── --}}
                    <input type="hidden" name="origin"          id="form-origin"          value="{{ $filters['origin'] }}">
                    <input type="hidden" name="origin_label"    id="form-origin-label"    value="{{ $filters['origin_label'] }}">
                    <input type="hidden" name="destination"      id="form-destination"      value="{{ $filters['destination'] }}">
                    <input type="hidden" name="destination_label" id="form-destination-label" value="{{ $filters['destination_label'] }}">
                    <input type="hidden" name="depart_date"     id="form-depart-date"     value="{{ $filters['depart_date'] }}">
                    <input type="hidden" name="return_date"     id="form-return-date"     value="{{ $filters['return_date'] }}">
                    <input type="hidden" name="adult"           id="form-adult"           :value="adult">
                    <input type="hidden" name="child"           id="form-child"           :value="child">
                    <input type="hidden" name="infant"          id="form-infant"          :value="infant">

                    {{-- Form card --}}
                    <div class="overflow-visible rounded-2xl bg-white shadow-[0_24px_60px_rgba(0,0,0,0.25)] ring-1 ring-slate-200/60">

                        {{-- Trip-type tabs (inside card) --}}
                        <!-- <div class="flex items-center gap-1 border-b border-slate-100 px-4 sm:px-5">
                            @foreach ($tripTypes as $val => $lbl)
                                <button type="button"
                                        @click="tripType = '{{ $val }}'"
                                        :class="tripType === '{{ $val }}'
                                            ? 'border-b-2 border-[#0361fc] text-[#0361fc] font-bold'
                                            : 'text-slate-500 hover:text-slate-700 font-semibold'"
                                        class="px-4 pb-3 pt-4 text-sm transition">
                                    {{ $lbl }}
                                </button>
                            @endforeach
                        </div> -->

                       
                        @php
                            $originDisplay      = $filters['origin_label']      ?: ($filters['origin']      ?: 'Pilih Bandara Asal');
                            $destinationDisplay = $filters['destination_label'] ?: ($filters['destination'] ?: 'Pilih Bandara Tujuan');
                            $departDay    = $filters['depart_date']  ? \Illuminate\Support\Carbon::parse($filters['depart_date'])->locale('id')->translatedFormat('l')  : null;
                            $departDate   = $filters['depart_date']  ? \Illuminate\Support\Carbon::parse($filters['depart_date'])->translatedFormat('d M Y') : null;
                            $returnDay    = $filters['return_date']  ? \Illuminate\Support\Carbon::parse($filters['return_date'])->locale('id')->translatedFormat('l')   : null;
                            $returnDate   = $filters['return_date']  ? \Illuminate\Support\Carbon::parse($filters['return_date'])->translatedFormat('d M Y')  : null;
                        @endphp

                        {{-- (canonical hidden inputs are declared above in the form, outside the card) --}}

                        <div class="block lg:hidden" x-data="{ mobilePassengerOpen: false }">

                            {{-- ─── Row 1: Trip-type tabs ─────────────────────────── --}}
                            <div class="flex border-b border-slate-100">
                                @foreach ($tripTypes as $val => $lbl)
                                    <button type="button"
                                            @click="tripType = '{{ $val }}'"
                                            :class="tripType === '{{ $val }}'
                                                ? 'border-b-2 border-[#0361fc] text-[#0361fc] font-bold'
                                                : 'text-slate-400 font-semibold'"
                                            class="flex-1 pb-3 pt-4 text-sm transition text-center">
                                        {{ $lbl }}
                                    </button>
                                @endforeach
                            </div>

                            {{-- ─── Row 2: Origin ──────────────────────────────────── --}}
                            <div class="relative"
                                 x-data="airportSearch('origin_mobile', '{{ $filters['origin'] }}', '{{ addslashes($filters['origin_label'] ?: $filters['origin']) }}')"
                                 x-init="$watch('value', v => { document.getElementById('form-origin').value = v; }); $watch('label', l => { document.getElementById('form-origin-label').value = l; })">
                                <label class="flex cursor-text items-center gap-3 px-4 py-3.5">
                                    {{-- Plane takeoff icon --}}
                                    <svg class="h-5 w-5 shrink-0 text-[#0361fc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                              d="M2.5 13.5L7 9l3 2 4-5 2 6"/>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <input
                                            type="text"
                                            x-model="query"
                                            @input.debounce.300ms="search()"
                                            @focus="suggestions.length && (open = true)"
                                            @blur="setTimeout(() => open = false, 200)"
                                            placeholder="Bandara asal"
                                            autocomplete="off"
                                            class="w-full bg-transparent text-[15px] font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400"
                                        >
                                        <p class="mt-0.5 text-[11px] text-slate-400" x-show="!query">Pilih kota atau bandara asal</p>
                                    </div>
                                    <span x-show="loading" class="shrink-0">
                                        <svg class="h-4 w-4 animate-spin text-[#0361fc]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                    </span>
                                </label>
                                <ul x-show="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="absolute left-0 top-full z-50 mt-1 max-h-56 w-full overflow-auto rounded-2xl border border-slate-200 bg-white py-1 shadow-2xl">
                                    <template x-for="airport in suggestions" :key="airport.id">
                                        <li @mousedown.prevent="select(airport)"
                                            class="flex cursor-pointer items-center gap-3 px-4 py-2.5 text-sm hover:bg-[#EDF4FF]">
                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#EDF4FF] text-[11px] font-bold text-[#0361fc]" x-text="airport.id"></span>
                                            <span class="font-medium text-slate-700" x-text="airport.label"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            {{-- Divider with floating Swap FAB --}}
                            <div class="relative mx-4">
                                <div class="h-px bg-slate-100"></div>
                                <button type="button"
                                        @click="swapAirports()"
                                        class="absolute right-0 top-1/2 z-10 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-[#EDF4FF] text-[#0361fc] shadow-sm transition hover:bg-blue-100 active:scale-95">
                                    {{-- Up/down swap arrows --}}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4 4 4M17 8v12m0 0 4-4m-4 4-4-4"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- ─── Row 3: Destination ─────────────────────────────── --}}
                            <div class="relative"
                                 x-data="airportSearch('destination_mobile', '{{ $filters['destination'] }}', '{{ addslashes($filters['destination_label'] ?: $filters['destination']) }}')"
                                 x-init="$watch('value', v => { document.getElementById('form-destination').value = v; }); $watch('label', l => { document.getElementById('form-destination-label').value = l; })">
                                <label class="flex cursor-text items-center gap-3 px-4 py-3.5">
                                    {{-- Plane landing icon --}}
                                    <svg class="h-5 w-5 shrink-0 text-[#0361fc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                              d="M5 17H3a2 2 0 0 1 0-4h1l4-7 1.5 5.5L19 8l1 3-15 6Z"/>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <input
                                            type="text"
                                            x-model="query"
                                            @input.debounce.300ms="search()"
                                            @focus="suggestions.length && (open = true)"
                                            @blur="setTimeout(() => open = false, 200)"
                                            placeholder="Bandara tujuan"
                                            autocomplete="off"
                                            class="w-full bg-transparent text-[15px] font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400"
                                        >
                                        <p class="mt-0.5 text-[11px] text-slate-400" x-show="!query">Pilih kota atau bandara tujuan</p>
                                    </div>
                                    <span x-show="loading" class="shrink-0">
                                        <svg class="h-4 w-4 animate-spin text-[#0361fc]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                    </span>
                                </label>
                                <ul x-show="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="absolute left-0 top-full z-50 mt-1 max-h-56 w-full overflow-auto rounded-2xl border border-slate-200 bg-white py-1 shadow-2xl">
                                    <template x-for="airport in suggestions" :key="airport.id">
                                        <li @mousedown.prevent="select(airport)"
                                            class="flex cursor-pointer items-center gap-3 px-4 py-2.5 text-sm hover:bg-[#EDF4FF]">
                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#EDF4FF] text-[11px] font-bold text-[#0361fc]" x-text="airport.id"></span>
                                            <span class="font-medium text-slate-700" x-text="airport.label"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            {{-- Full divider --}}
                            <div class="h-px bg-slate-100 mx-4"></div>

                            {{-- ─── Row 4: Dates ───────────────────────────────────── --}}
                            <div class="flex items-center gap-0 px-4 py-3.5">

                                {{-- Departure date --}}
                                <label class="flex flex-1 cursor-pointer items-center gap-2.5">
                                    {{-- Calendar + right arrow --}}
                                    <svg class="h-5 w-5 shrink-0 text-[#0361fc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 12l3 3-3 3"/>
                                    </svg>
                                    <div>
                                        <p class="text-[11px] text-slate-400">
                                            @if($departDay){{ $departDay }}@else Pergi @endif
                                        </p>
                                        <input x-ref="mobileDepartDate" type="text"
                                               value="{{ $filters['depart_date'] }}"
                                               placeholder="Pilih tanggal"
                                               readonly
                                               class="block w-full cursor-pointer bg-transparent text-[14px] font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400">
                                    </div>
                                </label>

                                {{-- Vertical separator --}}
                                <div class="mx-3 h-10 w-px bg-slate-100"></div>

                                {{-- Return date --}}
                                <div class="flex flex-1 items-center justify-between"
                                     :class="tripType !== 'R' ? 'opacity-40 pointer-events-none' : ''">
                                    <label class="flex cursor-pointer items-center gap-2.5">
                                        {{-- Calendar + left arrow --}}
                                        <svg class="h-5 w-5 shrink-0" :class="tripType === 'R' ? 'text-[#0361fc]' : 'text-slate-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 12l-3 3 3 3"/>
                                        </svg>
                                        <div>
                                            <p class="text-[11px] text-slate-400">
                                                @if($returnDay){{ $returnDay }}@else Pulang @endif
                                            </p>
                                            <input x-ref="mobileReturnDate" type="text"
                                                   value="{{ $filters['return_date'] }}"
                                                   placeholder="Pilih tanggal"
                                                   readonly
                                                   :disabled="tripType !== 'R'"
                                                   class="block w-full cursor-pointer bg-transparent text-[14px] font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400 disabled:cursor-not-allowed">
                                        </div>
                                    </label>
                                    {{-- Clear return date --}}
                                    <button type="button"
                                            x-show="tripType === 'R' && $refs.mobileReturnDate && $refs.mobileReturnDate.value"
                                            @click="$refs.mobileReturnDate.value = ''; mobileReturnPicker && mobileReturnPicker.clear(); document.getElementById('form-return-date').value = '';"
                                            class="ml-2 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#EDF4FF] text-[#0361fc] transition hover:bg-blue-100">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Full divider --}}
                            <div class="h-px bg-slate-100 mx-4"></div>

                            {{-- ─── Row 5: Passengers & Class ─────────────────────── --}}
                            <div class="flex items-center justify-between px-4 py-3.5">

                                {{-- Passengers: adult / child / infant icons + counts --}}
                                <button type="button"
                                        @click="mobilePassengerOpen = !mobilePassengerOpen"
                                        class="flex items-center gap-3">

                                    {{-- Adult --}}
                                    <span class="flex items-center gap-1">
                                        <svg class="h-4.5 w-4.5 h-[18px] w-[18px] text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-slate-700" x-text="adult"></span>
                                    </span>

                                    {{-- Child --}}
                                    <span class="flex items-center gap-1">
                                        <svg class="h-[18px] w-[18px] text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 2a3 3 0 100 6 3 3 0 000-6zM6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
                                            <circle cx="12" cy="5" r="3" stroke="currentColor" stroke-width="0" fill="none"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-slate-700" x-text="child"></span>
                                    </span>

                                    {{-- Infant --}}
                                    <span class="flex items-center gap-1">
                                        <svg class="h-[18px] w-[18px] text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 14s1.5 2 4 2 4-2 4-2"/>
                                            <line x1="9" y1="10" x2="9.01" y2="10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <line x1="15" y1="10" x2="15.01" y2="10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-slate-700" x-text="infant"></span>
                                    </span>
                                </button>

                                {{-- Class: seat icon + Economy --}}
                                <div class="flex items-center gap-2">
                                    <svg class="h-[18px] w-[18px] text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 17h10"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6V3"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-700">Economy</span>
                                </div>
                            </div>

                            {{-- Passenger popover for mobile --}}
                            <div x-show="mobilePassengerOpen"
                                 @click.outside="mobilePassengerOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="border-t border-slate-100 bg-slate-50/60 p-4">
                                <p class="mb-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Jumlah Penumpang</p>
                                @foreach ([['adult','Dewasa','12+ tahun',1],['child','Anak','2–11 tahun',0],['infant','Bayi','< 2 tahun',0]] as [$field,$name,$hint,$min])
                                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $name }}</p>
                                        <p class="text-xs text-slate-400">{{ $hint }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="decrement('{{ $field }}', {{ $min }})"
                                                class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-[#0361fc] hover:text-[#0361fc]">−</button>
                                        <span class="w-6 text-center text-sm font-bold text-slate-900" x-text="{{ $field }}"></span>
                                        <button type="button" @click="increment('{{ $field }}')"
                                                class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-[#0361fc] hover:text-[#0361fc]">+</button>
                                    </div>
                                </div>
                                @endforeach
                                <button type="button" @click="mobilePassengerOpen = false"
                                        class="mt-3 w-full rounded-xl bg-[#0361fc] py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">
                                    Selesai
                                </button>
                            </div>

                            {{-- ─── CTA Button (mobile) ────────────────────────────── --}}
                            <div class="px-4 pb-4 pt-3">
                                <button type="submit"
                                        class="flex w-full items-center justify-center gap-2.5 rounded-xl bg-[#0361fc] px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#0361fc]/30 transition hover:bg-blue-700 active:scale-[.98]">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z"/></svg>
                                    Cari Tiket
                                </button>
                            </div>

                        </div>{{-- end mobile card --}}

                        {{-- ══════════════════════════════════════════════
                             DESKTOP ROW  (lg+)  — original layout preserved
                        ══════════════════════════════════════════════ --}}
                        <div class="hidden lg:flex lg:flex-row lg:divide-x lg:divide-slate-100">

                            {{-- ① Origin --}}
                            <div x-data="airportSearch('origin', '{{ $filters['origin'] }}', '{{ addslashes($filters['origin_label'] ?: $filters['origin']) }}')"
                                 x-init="$watch('value', v => { document.getElementById('form-origin').value = v; }); $watch('label', l => { document.getElementById('form-origin-label').value = l; })"
                                 class="relative min-w-0 flex-1">
                                <label class="block cursor-text px-5 py-4 transition hover:bg-slate-50/60 lg:rounded-l-2xl">
                                    <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400">Dari</span>
                                    <div class="relative mt-1.5">
                                        <input
                                            type="text" x-model="query"
                                            @input.debounce.300ms="search()"
                                            @focus="suggestions.length && (open = true)"
                                            @blur="setTimeout(() => open = false, 200)"
                                            placeholder="Kota atau bandara asal"
                                            autocomplete="off"
                                            class="w-full bg-transparent text-sm font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400"
                                        >
                                        <span x-show="loading" class="pointer-events-none absolute right-0 top-0.5">
                                            <svg class="h-4 w-4 animate-spin text-[#0361fc]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                        </span>
                                    </div>
                                </label>
                                <ul x-show="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="absolute left-0 top-full z-50 mt-1 max-h-56 w-full min-w-[260px] overflow-auto rounded-2xl border border-slate-200 bg-white py-1 shadow-2xl">
                                    <template x-for="airport in suggestions" :key="airport.id">
                                        <li @mousedown.prevent="select(airport)"
                                            class="flex cursor-pointer items-center gap-3 px-4 py-2.5 text-sm hover:bg-[#EDF4FF]">
                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#EDF4FF] text-[11px] font-bold text-[#0361fc]" x-text="airport.id"></span>
                                            <span class="font-medium text-slate-700" x-text="airport.label"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            {{-- Swap button (desktop: between Origin & Destination) --}}
                            <div class="relative flex items-center">
                                <button type="button"
                                        class="absolute left-1/2 top-1/2 z-10 flex h-8 w-8 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:border-[#0361fc] hover:text-[#0361fc]">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0-4-4m4 4-4 4m-4 6H4m0 0 4 4m-4-4 4-4"/></svg>
                                </button>
                            </div>

                            {{-- ② Destination --}}
                            <div x-data="airportSearch('destination', '{{ $filters['destination'] }}', '{{ addslashes($filters['destination_label'] ?: $filters['destination']) }}')"
                                 x-init="$watch('value', v => { document.getElementById('form-destination').value = v; }); $watch('label', l => { document.getElementById('form-destination-label').value = l; })"
                                 class="relative min-w-0 flex-1">
                                <label class="block cursor-text px-5 py-4 transition hover:bg-slate-50/60">
                                    <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400">Ke</span>
                                    <div class="relative mt-1.5">
                                        <input
                                            type="text" x-model="query"
                                            @input.debounce.300ms="search()"
                                            @focus="suggestions.length && (open = true)"
                                            @blur="setTimeout(() => open = false, 200)"
                                            placeholder="Kota atau bandara tujuan"
                                            autocomplete="off"
                                            class="w-full bg-transparent text-sm font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400"
                                        >
                                        <span x-show="loading" class="pointer-events-none absolute right-0 top-0.5">
                                            <svg class="h-4 w-4 animate-spin text-[#0361fc]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                        </span>
                                    </div>
                                </label>
                                <ul x-show="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="absolute left-0 top-full z-50 mt-1 max-h-56 w-full min-w-[260px] overflow-auto rounded-2xl border border-slate-200 bg-white py-1 shadow-2xl">
                                    <template x-for="airport in suggestions" :key="airport.id">
                                        <li @mousedown.prevent="select(airport)"
                                            class="flex cursor-pointer items-center gap-3 px-4 py-2.5 text-sm hover:bg-[#EDF4FF]">
                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#EDF4FF] text-[11px] font-bold text-[#0361fc]" x-text="airport.id"></span>
                                            <span class="font-medium text-slate-700" x-text="airport.label"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            {{-- ③ Tanggal Pergi --}}
                            <label class="cursor-pointer px-5 py-4 transition hover:bg-slate-50/60 lg:min-w-[160px]">
                                <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400">Tanggal Pergi</span>
                                <div class="mt-1.5 flex items-center gap-2">
                                    <svg class="h-4 w-4 shrink-0 text-[#0361fc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <input x-ref="departDate" type="text"
                                           value="{{ $filters['depart_date'] }}"
                                           placeholder="Pilih tanggal"
                                           class="w-full bg-transparent text-sm font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400">
                                </div>
                            </label>

                            {{-- ④ Tanggal Pulang --}}
                            <label class="cursor-pointer px-5 py-4 transition hover:bg-slate-50/60 lg:min-w-[160px]"
                                   :class="tripType !== 'R' ? 'opacity-45 pointer-events-none' : ''">
                                <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400">Tanggal Pulang</span>
                                <div class="mt-1.5 flex items-center gap-2">
                                    <svg class="h-4 w-4 shrink-0" :class="tripType === 'R' ? 'text-[#0361fc]' : 'text-slate-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <input x-ref="returnDate" type="text"
                                           value="{{ $filters['return_date'] }}"
                                           placeholder="Pilih tanggal"
                                           :disabled="tripType !== 'R'"
                                           class="w-full bg-transparent text-sm font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400 disabled:cursor-not-allowed">
                                </div>
                            </label>

                            {{-- ⑤ Penumpang --}}
                            <div class="relative min-w-0 lg:min-w-[150px]" x-data="{ passengerOpen: false }">
                                <button type="button"
                                        @click="passengerOpen = !passengerOpen"
                                        class="w-full px-5 py-4 text-left transition hover:bg-slate-50/60">
                                    <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400">Penumpang</span>
                                    <div class="mt-1.5 flex items-center gap-2">
                                        <svg class="h-4 w-4 shrink-0 text-[#0361fc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="text-sm font-semibold text-slate-800" x-text="`${totalPassenger} Penumpang`"></span>
                                    </div>
                                </button>
                                <div x-show="passengerOpen"
                                     @click.outside="passengerOpen = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="absolute right-0 top-full z-50 mt-2 w-72 rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl ring-1 ring-slate-200/60">
                                    <p class="mb-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Jumlah Penumpang</p>
                                    @foreach ([['adult','Dewasa','12+ tahun',1],['child','Anak','2–11 tahun',0],['infant','Bayi','< 2 tahun',0]] as [$field,$name,$hint,$min])
                                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $name }}</p>
                                            <p class="text-xs text-slate-400">{{ $hint }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="decrement('{{ $field }}', {{ $min }})"
                                                    class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-[#0361fc] hover:text-[#0361fc]">−</button>
                                            <input x-model="{{ $field }}" type="number"
                                                   min="{{ $min }}" max="9"
                                                   class="w-8 border-none bg-transparent p-0 text-center text-sm font-bold text-slate-900 outline-none">
                                            <button type="button" @click="increment('{{ $field }}')"
                                                    class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-[#0361fc] hover:text-[#0361fc]">+</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- CTA button --}}
                            <div class="flex items-stretch">
                                <button type="submit"
                                        class="flex w-full items-center justify-center gap-2.5 rounded-r-2xl bg-[#0361fc] px-7 py-4 text-sm font-bold text-white shadow-lg shadow-[#0361fc]/30 transition hover:bg-blue-700 active:scale-[.98]">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z"/></svg>
                                    Cari Tiket
                                </button>
                            </div>

                        </div>{{-- end desktop row --}}

                        {{-- Errors (inside card, below panels) --}}
                        @if ($errors->any())
                            <div class="border-t border-slate-100 px-5 py-3 text-sm font-medium text-red-600">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        @if ($error)
                            <div class="flex items-center gap-2 border-t border-slate-100 px-5 py-3 text-sm font-medium text-amber-700">
                                <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                {{ $error }}
                            </div>
                        @endif
                    </div>{{-- end form card --}}
                </form>

            </div>{{-- end page-wrapper --}}
        </div>{{-- end content --}}
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════════
         RESULTS
    ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-[#F8F9FB] pb-16 pt-12 text-slate-900">
        <div class="page-wrapper grid gap-6 lg:grid-cols-4">

            {{-- Sidebar --}}
            <aside class="space-y-5 lg:col-span-1">
                <div class="rounded-[28px] bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
                    <h3 class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">Ringkasan</h3>
                    <dl class="mt-4 space-y-3.5 text-sm">
                        <div class="flex items-start justify-between gap-3">
                            <dt class="font-medium text-slate-500">Rute</dt>
                            <dd class="text-right font-bold leading-snug text-slate-900">
                                @if ($hasSearch)
                                    {{ $routeLabel }} → {{ $destinationLabel }}
                                @else
                                    <span class="font-normal text-slate-400">Belum dipilih</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="font-medium text-slate-500">Tanggal</dt>
                            <dd class="font-bold text-slate-900">
                                {{ $filters['depart_date'] ? \Illuminate\Support\Carbon::parse($filters['depart_date'])->translatedFormat('d M Y') : '-' }}
                            </dd>
                        </div>
                        @if ($filters['trip_type'] === 'R' && $filters['return_date'])
                        <div class="flex items-start justify-between gap-3">
                            <dt class="font-medium text-slate-500">Pulang</dt>
                            <dd class="font-bold text-slate-900">{{ \Illuminate\Support\Carbon::parse($filters['return_date'])->translatedFormat('d M Y') }}</dd>
                        </div>
                        @endif
                        <div class="flex items-start justify-between gap-3">
                            <dt class="font-medium text-slate-500">Penumpang</dt>
                            <dd class="font-bold text-slate-900">{{ $totalPassenger }} orang</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="font-medium text-slate-500">Kelas</dt>
                            <dd class="font-bold text-slate-900">Economy</dd>
                        </div>
                    </dl>
                </div>
                <div class="rounded-[28px] bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
                    <h3 class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">Catatan</h3>
                    <p class="mt-3 flex items-start gap-2 text-sm leading-relaxed text-slate-500">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#0361fc]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/></svg>
                        Harga estimasi dalam Rupiah. Kurs diperbarui tiap jam.
                    </p>
                </div>
            </aside>

            {{-- Results --}}
            <section class="lg:col-span-3">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.28em] text-slate-400">Hasil Pencarian</p>
                        <h2 class="mt-1.5 text-xl font-bold text-slate-900 sm:text-2xl">
                            @if ($hasSearch)
                                {{ $routeLabel }} → {{ $destinationLabel }}
                            @else
                                Pilih rute dan tanggal
                            @endif
                        </h2>
                    </div>
                    @if ($results)
                        <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-[#EDF4FF] px-3 py-1 text-xs font-bold text-[#0361fc]">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            {{ count($results) }} penerbangan
                        </span>
                    @endif
                </div>

                <div class="space-y-4">
                    @php $displayResults = $paginatedResults ?? collect($results); @endphp

                    @forelse ($displayResults as $flight)
                        <article class="overflow-hidden rounded-[28px] bg-white shadow-sm ring-1 ring-slate-200/80 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:gap-0">

                                {{-- Airline --}}
                                <div class="flex shrink-0 items-center gap-3 sm:w-32">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EDF4FF] text-xs font-bold text-[#0361fc]">
                                        {{ strtoupper(substr($flight['airline'], 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900">{{ $flight['airline'] }}</p>
                                        <p class="truncate text-[11px] font-medium text-slate-400">{{ $flight['flight_numbers'] ?: '-' }}</p>
                                    </div>
                                </div>

                                {{-- Timeline --}}
                                <div class="flex flex-1 items-center gap-2 sm:px-4">
                                    <div class="text-center">
                                        <p class="text-xl font-extrabold leading-none text-slate-900">{{ $flight['start_time'] }}</p>
                                        <p class="mt-0.5 text-xs font-bold text-slate-500">{{ $flight['start_location'] }}</p>
                                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">{{ $flight['start_date'] }}</p>
                                    </div>
                                    <div class="flex flex-1 flex-col items-center gap-1 px-2 sm:px-4">
                                        <p class="text-[11px] font-semibold text-slate-500">{{ $flight['duration'] }}</p>
                                        <div class="relative flex w-full items-center">
                                            <div class="h-px flex-1 bg-slate-200"></div>
                                            <svg class="mx-1.5 h-5 w-5 shrink-0 text-[#0361fc]" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5L21 16Z"/></svg>
                                            <div class="h-px flex-1 bg-slate-200"></div>
                                        </div>
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $flight['stops'] === 'Direct' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                            {{ $flight['stops'] }}
                                        </span>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xl font-extrabold leading-none text-slate-900">{{ $flight['end_time'] }}</p>
                                        <p class="mt-0.5 text-xs font-bold text-slate-500">{{ $flight['end_location'] }}</p>
                                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">{{ $flight['end_date'] }}</p>
                                    </div>
                                </div>

                                <div class="hidden h-14 w-px shrink-0 bg-slate-100 sm:block"></div>

                                {{-- Price & CTA --}}
                                <div class="flex items-center justify-between gap-4 sm:w-44 sm:flex-col sm:items-end sm:pl-4">
                                    <div class="sm:text-right">
                                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Mulai dari</p>
                                        <p class="text-lg font-extrabold text-slate-900 sm:text-xl">{{ $flight['price'] }}</p>
                                        <p class="text-[10px] text-slate-400">/ penumpang</p>
                                    </div>
                                    <!-- <button type="button"
                                            class="shrink-0 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-[#0361fc] active:scale-95 sm:w-full sm:text-center">
                                        Pilih
                                    </button> -->
                                </div>
                            </div>

                            {{-- Detail bar --}}
                            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-slate-100 bg-slate-50/60 px-5 py-3">
                                @if ($flight['class'])
                                    <span class="flex items-center gap-1.5 text-[11px] font-medium text-slate-500">
                                        <svg class="h-3.5 w-3.5 text-[#0361fc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        {{ $flight['class'] }}
                                    </span>
                                @endif
                                @if ($flight['info'])
                                    <span class="flex items-center gap-1.5 text-[11px] font-medium text-slate-500">
                                        <svg class="h-3.5 w-3.5 text-[#0361fc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        {{ $flight['info'] }}
                                    </span>
                                @endif
                                @if ($flight['passport_required'])
                                    <span class="flex items-center gap-1.5 text-[11px] font-bold text-amber-600">
                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                        Paspor diperlukan
                                    </span>
                                @endif
                                @foreach ($flight['fare_breakdown'] as $fare)
                                    <span class="text-[11px] text-slate-500">
                                        <span class="font-semibold capitalize text-slate-700">{{ $fare['pax_type'] }}:</span> {{ $fare['total_fare'] }}
                                    </span>
                                @endforeach
                            </div>
                        </article>
                    @empty
                        <div class="rounded-[32px] border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EDF4FF]">
                                <svg class="h-7 w-7 text-[#0361fc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900">Belum ada hasil tiket.</h3>
                            <p class="mt-3 text-sm text-slate-500">Isi rute, tanggal, dan jumlah penumpang lalu tekan <strong>Cari Tiket</strong>.</p>
                        </div>
                    @endforelse
                </div>

                @if ($paginatedResults && $paginatedResults->hasPages())
                    <div class="mt-8 flex items-center justify-center">
                        {{ $paginatedResults->onEachSide(1)->links() }}
                    </div>
                @endif
            </section>

        </div>
    </div>

    <x-home.footer />

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <style>
            .flatpickr-calendar {
                width: 300px; border-radius: 1.25rem;
                border: 1px solid #e2e8f0;
                box-shadow: 0 20px 60px rgba(15,23,42,.16);
                padding: 12px; font-family: inherit;
            }
            .flatpickr-months .flatpickr-month,
            .flatpickr-current-month .flatpickr-monthDropdown-months,
            .flatpickr-current-month input.cur-year { font-weight: 700; color: #0f172a; }
            .flatpickr-weekdays { margin-top: 6px; }
            .flatpickr-weekday { color: #94a3b8; font-weight: 700; font-size: 11px; }
            .flatpickr-days, .dayContainer { width: 100%; min-width: 100%; max-width: 100%; }
            .flatpickr-day, .flatpickr-weekday { display: inline-flex; align-items: center; justify-content: center; }
            .flatpickr-day {
                width: 36px; max-width: 36px; height: 36px; line-height: 36px;
                border-radius: .625rem; font-weight: 600; color: #334155; margin: 0;
            }
            .flatpickr-day.today { border-color: #0361fc; color: #0361fc; }
            .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange,
            .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover
            { background: #0361fc; border-color: #0361fc; color: #fff; }
            .flatpickr-day.inRange { background: #edf4ff; border-color: #edf4ff; color: #0361fc; box-shadow: none; }
            .flatpickr-day.prevMonthDay, .flatpickr-day.nextMonthDay,
            .flatpickr-day.flatpickr-disabled, .flatpickr-day.flatpickr-disabled:hover { color: #cbd5e1; }
        </style>

        <script>
            document.addEventListener('alpine:init', () => {

                Alpine.data('airportSearch', (fieldName, initialValue, initialLabel) => ({
                    query: initialLabel || '', value: initialValue || '',
                    label: initialLabel || '', suggestions: [], open: false, loading: false,
                    async search() {
                        const q = this.query.trim();
                        if (q.length < 2) { this.suggestions = []; this.open = false; return; }
                        this.loading = true;
                        try {
                            const res = await fetch(`/api/flights/airports?query=${encodeURIComponent(q)}`);
                            this.suggestions = await res.json();
                            this.open = this.suggestions.length > 0;
                        } catch (e) {
                            this.suggestions = []; this.open = false;
                        } finally { this.loading = false; }
                    },
                    select(airport) {
                        this.value = airport.id; this.label = airport.label;
                        this.query = airport.label; this.open = false; this.suggestions = [];
                    },
                }));

                Alpine.data('flightSearchForm', () => ({
                    tripType:     '{{ $filters['trip_type'] }}',
                    adult:        {{ (int) $filters['adult'] }},
                    child:        {{ (int) $filters['child'] }},
                    infant:       {{ (int) $filters['infant'] }},
                    departPicker: null,
                    returnPicker: null,
                    mobileDepartPicker: null,
                    mobileReturnPicker: null,
                    get totalPassenger() {
                        return Number(this.adult) + Number(this.child) + Number(this.infant);
                    },
                    init() {
                        const jakartaToday = '{{ now('Asia/Jakarta')->toDateString() }}';

                        // ── Desktop flatpickr (x-ref="departDate" / "returnDate") ──
                        this.departPicker = flatpickr(this.$refs.departDate, {
                            dateFormat: 'Y-m-d', minDate: jakartaToday,
                            defaultDate: this.$refs.departDate.value || null,
                            disableMobile: true,
                            onChange: (_, dateStr) => {
                                document.getElementById('form-depart-date').value = dateStr;
                                if (this.returnPicker) this.returnPicker.set('minDate', dateStr || jakartaToday);
                                if (this.mobileReturnPicker) this.mobileReturnPicker.set('minDate', dateStr || jakartaToday);
                            },
                        });
                        this.returnPicker = flatpickr(this.$refs.returnDate, {
                            dateFormat: 'Y-m-d', minDate: this.$refs.departDate.value || jakartaToday,
                            defaultDate: this.$refs.returnDate.value || null, disableMobile: true,
                            onChange: (_, dateStr) => {
                                document.getElementById('form-return-date').value = dateStr;
                            },
                        });

                        // ── Mobile flatpickr (x-ref="mobileDepartDate" / "mobileReturnDate") ──
                        this.mobileDepartPicker = flatpickr(this.$refs.mobileDepartDate, {
                            dateFormat: 'Y-m-d', minDate: jakartaToday,
                            defaultDate: this.$refs.mobileDepartDate.value || null,
                            disableMobile: true,
                            onChange: (_, dateStr) => {
                                document.getElementById('form-depart-date').value = dateStr;
                                if (this.returnPicker) this.returnPicker.set('minDate', dateStr || jakartaToday);
                                if (this.mobileReturnPicker) this.mobileReturnPicker.set('minDate', dateStr || jakartaToday);
                            },
                        });
                        this.mobileReturnPicker = flatpickr(this.$refs.mobileReturnDate, {
                            dateFormat: 'Y-m-d', minDate: this.$refs.mobileDepartDate.value || jakartaToday,
                            defaultDate: this.$refs.mobileReturnDate.value || null, disableMobile: true,
                            onChange: (_, dateStr) => {
                                document.getElementById('form-return-date').value = dateStr;
                            },
                        });

                        this.$watch('tripType', value => {
                            if (value !== 'R') {
                                // Clear desktop
                                this.$refs.returnDate.value = '';
                                this.returnPicker.clear();
                                // Clear mobile
                                this.$refs.mobileReturnDate.value = '';
                                this.mobileReturnPicker.clear();
                                // Clear canonical
                                document.getElementById('form-return-date').value = '';
                            }
                        });
                    },
                    increment(field) {
                        if (this.totalPassenger >= 9) return;
                        this[field] = Math.min(9, Number(this[field]) + 1);
                    },
                    decrement(field, min = 0) {
                        this[field] = Math.max(min, Number(this[field]) - 1);
                    },
                    swapAirports() {
                        // Grab the canonical form hidden inputs
                        const originInput      = document.getElementById('form-origin');
                        const originLabelInput = document.getElementById('form-origin-label');
                        const destInput        = document.getElementById('form-destination');
                        const destLabelInput   = document.getElementById('form-destination-label');
                        if (!originInput || !destInput) return;

                        // Swap the canonical values
                        const tmpVal   = originInput.value;
                        const tmpLabel = originLabelInput.value;
                        originInput.value      = destInput.value;
                        originLabelInput.value = destLabelInput.value;
                        destInput.value        = tmpVal;
                        destLabelInput.value   = tmpLabel;

                        // Also update visible text inputs in mobile Alpine components
                        const originComp = document.querySelector('[x-data*="origin_mobile"]')?._x_dataStack?.[0];
                        const destComp   = document.querySelector('[x-data*="destination_mobile"]')?._x_dataStack?.[0];
                        if (originComp && destComp) {
                            const tmpQuery = originComp.query;
                            const tmpValue = originComp.value;
                            const tmpLbl   = originComp.label;
                            originComp.query = destComp.query;
                            originComp.value = destComp.value;
                            originComp.label = destComp.label;
                            destComp.query = tmpQuery;
                            destComp.value = tmpValue;
                            destComp.label = tmpLbl;
                        }
                    },
                }));
            });
        </script>
    @endpush
</x-layouts.app>
