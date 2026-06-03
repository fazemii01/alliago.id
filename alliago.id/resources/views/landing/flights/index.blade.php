<x-layouts.app
    title="Tiket Pesawat Murah | Alliago.id — Cari & Pesan Penerbangan"
    description="Cari dan pesan tiket pesawat murah dengan Alliago.id. Bandingkan harga dari berbagai maskapai untuk penerbangan domestik dan internasional. Booking mudah, cepat, dan terpercaya."
>
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

                        <div class="block lg:hidden">

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

                        {{-- Trip-type switcher — desktop only --}}
                        <div class="hidden lg:flex items-center gap-1 border-b border-slate-100 px-5">
                            @foreach ($tripTypes as $val => $lbl)
                                <button type="button"
                                        @click="tripType = '{{ $val }}'"
                                        :class="tripType === '{{ $val }}'
                                            ? 'border-b-2 border-[#0361fc] text-[#0361fc] font-bold'
                                            : 'text-slate-400 hover:text-slate-600 font-semibold'"
                                        class="pb-3 pt-4 px-4 text-sm transition">
                                    {{ $lbl }}
                                </button>
                            @endforeach
                        </div>

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
                            <div class="relative min-w-0 lg:min-w-[150px]" x-data="{ passengerOpen: false }" @click.outside="passengerOpen = false">
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
    <div class="bg-[#F8F9FB] pb-16 pt-12 text-slate-900"
         x-data="airlineFilter({{ json_encode(array_column($airlines ?? [], 'code')) }})">
        <div class="page-wrapper grid gap-6 lg:grid-cols-4">

            {{-- Sidebar --}}
            <aside class="space-y-5 lg:col-span-1 min-w-0">
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

                @php
                    $sidebarBanner = \App\Models\FlightPricingConfig::where('is_sidebar_banner_active', true)->orderBy('updated_at', 'desc')->first();
                @endphp

                @if ($sidebarBanner)
                    @php
                        $sidebarBannerSrc = $sidebarBanner->sidebar_banner_path 
                            ? Storage::disk('s3')->url($sidebarBanner->sidebar_banner_path) 
                            : $sidebarBanner->sidebar_banner_image_url;
                    @endphp

                    @if ($sidebarBannerSrc)
                        <div class="w-full max-w-md mx-auto lg:max-w-none rounded-[28px] overflow-hidden shadow-sm ring-1 ring-slate-200/80 transition hover:shadow-md hover:-translate-y-0.5 bg-slate-100">
                            @if ($sidebarBanner->sidebar_banner_link)
                                <a href="{{ $sidebarBanner->sidebar_banner_link }}" target="_blank" rel="noopener noreferrer" class="block w-full">
                                    <img src="{{ $sidebarBannerSrc }}" alt="Sidebar Banner" class="w-full max-w-full h-auto block">
                                </a>
                            @else
                                <img src="{{ $sidebarBannerSrc }}" alt="Sidebar Banner" class="w-full max-w-full h-auto block">
                            @endif
                        </div>
                    @endif
                @endif

                @if (!empty($airlines))
                <div class="rounded-[28px] bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">Maskapai</h3>
                        <button type="button" @click="selectedAirlines = []"
                                x-show="selectedAirlines.length > 0"
                                class="text-[11px] font-semibold text-[#0361fc] hover:underline">
                            Reset
                        </button>
                    </div>
                    <div class="mt-3 space-y-2.5">
                        @foreach ($airlines as $airline)
                        <label class="flex cursor-pointer items-center gap-3">
                            <input type="checkbox"
                                   value="{{ $airline['code'] }}"
                                   @change="toggleAirline('{{ $airline['code'] }}')"
                                   :checked="selectedAirlines.includes('{{ $airline['code'] }}')"
                                   class="h-4 w-4 rounded border-slate-300 text-[#0361fc] focus:ring-[#0361fc]">
                            <span class="text-sm font-medium text-slate-700">{{ $airline['name'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>

            {{-- Results --}}
            <section class="lg:col-span-3 min-w-0">
                @php
                    $flightConfig = \App\Models\FlightPricingConfig::current();
                    $activeConfigs = \App\Models\FlightPricingConfig::where('is_banner_active', true)->orderBy('updated_at', 'desc')->get();
                    $firstBanner = $activeConfigs->first();
                    $secondBanner = $activeConfigs->skip(1)->first();
                @endphp

                @if (isset($recommendations) && count($recommendations) > 0)
                    <div class="mb-8 rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#0361fc]">Rekomendasi Layanan Kami yang lain</p>
                                <h3 class="mt-1 text-base font-extrabold text-slate-900 sm:text-lg">
                                    Rekomendasi Layanan Visa Terpopuler
                                </h3>
                            </div>
                        </div>
                        
                        <div x-data="{
                            canScrollLeft: false,
                            canScrollRight: false,
                            checkScroll() {
                                const el = this.$refs.slider;
                                if (!el) return;
                                this.canScrollLeft = el.scrollLeft > 10;
                                this.canScrollRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 10);
                            },
                            scroll(dir) {
                                const el = this.$refs.slider;
                                if (!el) return;
                                const scrollAmount = dir === 'next' ? 260 : -260;
                                el.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                            }
                        }" x-init="setTimeout(() => checkScroll(), 300); window.addEventListener('resize', () => checkScroll())" 
                        class="relative">
                            
                            {{-- Left arrow --}}
                            <button type="button"
                                    x-show="canScrollLeft"
                                    @click="scroll('prev')"
                                    class="absolute -left-4 top-1/2 z-10 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 shadow-md transition hover:bg-slate-50 active:scale-95"
                                    style="display: none;">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>

                            {{-- Right arrow --}}
                            <button type="button"
                                    x-show="canScrollRight"
                                    @click="scroll('next')"
                                    class="absolute -right-4 top-1/2 z-10 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 shadow-md transition hover:bg-slate-50 active:scale-95"
                                    style="display: none;">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>

                            {{-- Slider container --}}
                            <div x-ref="slider"
                                 @scroll.debounce.50ms="checkScroll()"
                                 class="flex gap-4 overflow-x-auto pb-2 pt-1 scroll-smooth"
                                 style="scrollbar-width: none; -ms-overflow-style: none;">
                                
                                @foreach($recommendations as $product)
                                    @php
                                        $hasDiscount = $product->discount_price && $product->discount_price < $product->base_price;
                                        $discountPercent = $hasDiscount ? round((1 - $product->discount_price / $product->base_price) * 100) : 0;
                                        $countryName = $product->country->name ?? '';
                                        $countryCode = $product->country->code ?? '';
                                        $flagEmoji = $product->country->flag_emoji ?? '🏳️';
                                        
                                        $cardImages = [
                                            'Jepang' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=400&q=80',
                                            'Korea Selatan' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=400&q=80',
                                            'Australia' => 'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?auto=format&fit=crop&w=400&q=80',
                                            'China' => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?auto=format&fit=crop&w=400&q=80',
                                            'Taiwan' => 'https://images.unsplash.com/photo-1470004914212-05527e49370b?auto=format&fit=crop&w=400&q=80',
                                            'United States' => 'https://images.unsplash.com/photo-1485738422979-f5c462d49f04?auto=format&fit=crop&w=400&q=80',
                                            'United Kingdom' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=400&q=80',
                                            'Netherlands' => 'https://images.unsplash.com/photo-1534351590666-13e3e96b5017?auto=format&fit=crop&w=400&q=80',
                                        ];
                                        $defaultImage = 'https://images.unsplash.com/photo-1488085061387-422e29b40080?auto=format&fit=crop&w=400&q=80';
                                        $cardImage = $cardImages[$countryName] ?? $defaultImage;
                                    @endphp
                                    
                                    <a href="{{ route('visa.show', $product->slug) }}" 
                                       class="group relative flex w-[230px] shrink-0 flex-col rounded-2xl border border-slate-200/80 bg-white p-2.5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#0361fc]/35 hover:shadow-md">
                                        
                                        {{-- Image card --}}
                                        <div class="relative h-28 w-full overflow-hidden rounded-xl bg-slate-100">
                                            <img src="{{ $cardImage }}" alt="{{ $product->name }}" 
                                                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                                 loading="lazy">
                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/30 via-transparent to-transparent"></div>
                                            
                                            {{-- Country / Flag badge --}}
                                            <div class="absolute left-2 top-2 flex items-center gap-1 rounded-md bg-[#0361fc] px-1.5 py-0.5 text-[9px] font-extrabold text-white shadow-sm">
                                                <span>{{ $flagEmoji }}</span>
                                                <span class="truncate max-w-[80px]">{{ $countryName }}</span>
                                            </div>

                                            @if ($product->promo_label)
                                                <div class="absolute right-2 top-2 rounded-md bg-red-500 px-1.5 py-0.5 text-[8px] font-black uppercase text-white shadow-sm">
                                                    {{ $product->promo_label }}
                                                </div>
                                            @elseif ($hasDiscount)
                                                <div class="absolute right-2 top-2 rounded-md bg-orange-500 px-1.5 py-0.5 text-[8px] font-black uppercase text-white shadow-sm">
                                                    Hemat {{ $discountPercent }}%
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Details --}}
                                        <div class="mt-2.5 flex flex-1 flex-col">
                                            <h4 class="text-xs font-bold text-slate-800 leading-snug line-clamp-1 group-hover:text-[#0361fc] transition-colors">
                                                {{ $product->name }}
                                            </h4>
                                            <p class="mt-0.5 text-[9px] font-semibold text-slate-400 uppercase tracking-wider">
                                                {{ $product->type }}
                                            </p>

                                            {{-- processing/stay durations --}}
                                            <div class="mt-2 flex items-center gap-1.5 text-[9px] font-medium text-slate-500">
                                                @if ($product->processing_time)
                                                    <span class="flex items-center gap-0.5 rounded bg-slate-50 px-1 py-0.5 ring-1 ring-slate-150">
                                                        ⏱️ {{ $product->processing_time }}
                                                    </span>
                                                @endif
                                                @if ($product->stay_duration)
                                                    <span class="flex items-center gap-0.5 rounded bg-slate-50 px-1 py-0.5 ring-1 ring-slate-150">
                                                        📅 {{ $product->stay_duration }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Price tag --}}
                                            <div class="mt-auto pt-2.5 flex items-center justify-between border-t border-slate-100/80">
                                                <div class="flex flex-col">
                                                    @if ($hasDiscount)
                                                        <span class="text-[8px] font-medium text-slate-400 line-through leading-none">
                                                            IDR {{ number_format($product->base_price, 0, ',', '.') }}
                                                        </span>
                                                        <span class="text-xs font-extrabold text-red-600 leading-none mt-0.5">
                                                            IDR {{ number_format($product->discount_price, 0, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span class="text-[8px] font-medium text-slate-400 leading-none">
                                                            Mulai dari
                                                        </span>
                                                        <span class="text-xs font-extrabold text-slate-800 leading-none mt-0.5">
                                                            IDR {{ number_format($product->base_price, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#EDF4FF] text-[#0361fc] transition-all duration-300 group-hover:bg-[#0361fc] group-hover:text-white">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            
                            <style>
                                div[x-ref="slider"]::-webkit-scrollbar {
                                    display: none;
                                }
                            </style>
                        </div>
                    </div>
                @endif

                @if ($firstBanner && !$hasSearch)
                    @php
                        $bannerSrc = $firstBanner->banner_path 
                            ? Storage::disk('s3')->url($firstBanner->banner_path) 
                            : $firstBanner->banner_image_url;
                    @endphp

                    @if ($bannerSrc)
                        <div class="mb-6 rounded-[28px] overflow-hidden shadow-sm ring-1 ring-slate-200/80 max-w-[810px] w-full bg-slate-100 transition hover:shadow-md hover:-translate-y-0.5">
                            @if ($firstBanner->banner_link)
                                <a href="{{ $firstBanner->banner_link }}" target="_blank" rel="noopener noreferrer" class="w-full block">
                                    <img src="{{ $bannerSrc }}" alt="Promo Banner" class="w-full max-w-full h-auto block">
                                </a>
                            @else
                                <img src="{{ $bannerSrc }}" alt="Promo Banner" class="w-full max-w-full h-auto block">
                            @endif
                        </div>
                    @elseif ($firstBanner->banner_link)
                        <a href="{{ $firstBanner->banner_link }}" target="_blank" rel="noopener noreferrer" 
                           class="mb-6 rounded-[28px] overflow-hidden shadow-sm ring-1 ring-slate-200/80 max-w-[810px] w-full bg-gradient-to-r from-blue-600 via-[#0361fc] to-[#00d2ff] flex flex-col justify-center px-6 py-8 md:px-12 md:py-6 text-white h-auto md:h-[195.5px] transition hover:shadow-lg hover:-translate-y-0.5 group relative aspect-auto md:aspect-[810/195.5]">
                            <div class="absolute right-0 top-0 h-full w-1/3 bg-white/5 skew-x-12 translate-x-10 transition group-hover:translate-x-4"></div>
                            <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10 blur-xl"></div>
                            
                            <div class="relative z-10 space-y-1 md:space-y-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-[10px] md:text-xs font-bold uppercase tracking-wider text-white backdrop-blur-sm">
                                    Promo Spesial ✨
                                </span>
                                <h3 class="text-lg md:text-2xl font-black tracking-tight leading-tight drop-shadow-sm">
                                    Temukan Penawaran Terbaik Hari Ini
                                </h3>
                                <p class="text-xs md:text-sm font-medium text-white/95 max-w-lg leading-relaxed">
                                    Nikmati promo eksklusif penerbangan domestik dan internasional. Klik di sini untuk melihat info selengkapnya.
                                </p>
                            </div>
                        </a>
                    @endif
                @endif

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

                @if ($firstBanner && $hasSearch)
                    @php
                        $bannerSrc = $firstBanner->banner_path 
                            ? Storage::disk('s3')->url($firstBanner->banner_path) 
                            : $firstBanner->banner_image_url;
                    @endphp

                    @if ($bannerSrc)
                        <div class="mb-6 rounded-[28px] overflow-hidden shadow-sm ring-1 ring-slate-200/80 max-w-[810px] w-full bg-slate-100 transition hover:shadow-md hover:-translate-y-0.5">
                            @if ($firstBanner->banner_link)
                                <a href="{{ $firstBanner->banner_link }}" target="_blank" rel="noopener noreferrer" class="w-full block">
                                    <img src="{{ $bannerSrc }}" alt="Promo Banner" class="w-full max-w-full h-auto block">
                                </a>
                            @else
                                <img src="{{ $bannerSrc }}" alt="Promo Banner" class="w-full max-w-full h-auto block">
                            @endif
                        </div>
                    @elseif ($firstBanner->banner_link)
                        <a href="{{ $firstBanner->banner_link }}" target="_blank" rel="noopener noreferrer" 
                           class="mb-6 rounded-[28px] overflow-hidden shadow-sm ring-1 ring-slate-200/80 max-w-[810px] w-full bg-gradient-to-r from-blue-600 via-[#0361fc] to-[#00d2ff] flex flex-col justify-center px-6 py-8 md:px-12 md:py-6 text-white h-auto md:h-[195.5px] transition hover:shadow-lg hover:-translate-y-0.5 group relative aspect-auto md:aspect-[810/195.5]">
                            <div class="absolute right-0 top-0 h-full w-1/3 bg-white/5 skew-x-12 translate-x-10 transition group-hover:translate-x-4"></div>
                            <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10 blur-xl"></div>
                            
                            <div class="relative z-10 space-y-1 md:space-y-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-[10px] md:text-xs font-bold uppercase tracking-wider text-white backdrop-blur-sm">
                                    Promo Spesial ✨
                                </span>
                                <h3 class="text-lg md:text-2xl font-black tracking-tight leading-tight drop-shadow-sm">
                                    Temukan Penawaran Terbaik Hari Ini
                                </h3>
                                <p class="text-xs md:text-sm font-medium text-white/95 max-w-lg leading-relaxed">
                                    Nikmati promo eksklusif penerbangan domestik dan internasional. Klik di sini untuk melihat info selengkapnya.
                                </p>
                            </div>
                        </a>
                    @endif
                @endif

                <div class="space-y-4">
                    @php $displayResults = $paginatedResults ?? collect($results); @endphp

                    @forelse ($displayResults as $flight)
                        <article x-show="isVisible('{{ $flight['airline'] }}')"
                                 class="overflow-hidden rounded-[28px] bg-white shadow-sm ring-1 ring-slate-200/80 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:gap-0">

                                {{-- Airline --}}
                                <div class="flex shrink-0 items-center gap-3 sm:w-32">
                                    @if (!empty($flight['logo_url']))
                                        <img src="{{ $flight['logo_url'] }}"
                                             alt="{{ $flight['airline'] }}"
                                             class="h-10 w-10 rounded-xl object-contain p-1 bg-[#EDF4FF]"
                                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                        <div class="hidden h-10 w-10 items-center justify-center rounded-xl bg-[#EDF4FF] text-xs font-bold text-[#0361fc]">
                                            {{ strtoupper(substr($flight['airline'], 0, 2)) }}
                                        </div>
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EDF4FF] text-xs font-bold text-[#0361fc]">
                                            {{ strtoupper(substr($flight['airline'], 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-900">{{ $flight['airline_name'] ?? $flight['airline'] }}</p>
                                        <p class="truncate text-[11px] font-medium text-slate-400">{{ $flight['airline'] }} · {{ $flight['flight_numbers'] ?: '-' }}</p>
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
                                        <p class="text-[10px] text-slate-400">
                                            @if ($totalPassenger > 1)
                                                Total ({{ $totalPassenger }} penumpang)
                                            @else
                                                / penumpang
                                            @endif
                                        </p>
                                    </div>
                                    @if (trim(strtoupper($flight['airline'] ?? '')) === 'ZZ')
                                        @if (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff')))
                                            <button type="button"
                                                    @click="$dispatch('open-invoice-wizard', { flight: {{ json_encode($flight) }}, filters: {{ json_encode($filters) }} })"
                                                    class="shrink-0 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-700 active:scale-95 sm:w-full sm:text-center">
                                                Buat Invoice
                                            </button>
                                        @else
                                            <a href="https://wa.me/6281334455616"
                                               target="_blank" rel="noopener noreferrer"
                                               class="shrink-0 rounded-xl bg-[#0361fc] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700 active:scale-95 sm:w-full sm:text-center">
                                                Pilih
                                            </a>
                                        @endif
                                    @endif
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
                                        <span class="font-semibold capitalize text-slate-700">{{ $fare['pax_type'] }}:</span>
                                        {{ $fare['total_fare'] }}
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

                @if ($secondBanner)
                    @php
                        $bannerSrc2 = $secondBanner->banner_path 
                            ? Storage::disk('s3')->url($secondBanner->banner_path) 
                            : $secondBanner->banner_image_url;
                    @endphp

                    @if ($bannerSrc2)
                        <div class="mt-6 rounded-[28px] overflow-hidden shadow-sm ring-1 ring-slate-200/80 max-w-[810px] w-full bg-slate-100 transition hover:shadow-md hover:-translate-y-0.5">
                            @if ($secondBanner->banner_link)
                                <a href="{{ $secondBanner->banner_link }}" target="_blank" rel="noopener noreferrer" class="w-full block">
                                    <img src="{{ $bannerSrc2 }}" alt="Promo Banner" class="w-full max-w-full h-auto block">
                                </a>
                            @else
                                <img src="{{ $bannerSrc2 }}" alt="Promo Banner" class="w-full max-w-full h-auto block">
                            @endif
                        </div>
                    @elseif ($secondBanner->banner_link)
                        <a href="{{ $secondBanner->banner_link }}" target="_blank" rel="noopener noreferrer" 
                           class="mt-6 rounded-[28px] overflow-hidden shadow-sm ring-1 ring-slate-200/80 max-w-[810px] w-full bg-gradient-to-r from-blue-600 via-[#0361fc] to-[#00d2ff] flex flex-col justify-center px-6 py-8 md:px-12 md:py-6 text-white h-auto md:h-[195.5px] transition hover:shadow-lg hover:-translate-y-0.5 group relative aspect-auto md:aspect-[810/195.5]">
                            <div class="absolute right-0 top-0 h-full w-1/3 bg-white/5 skew-x-12 translate-x-10 transition group-hover:translate-x-4"></div>
                            <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10 blur-xl"></div>
                            
                            <div class="relative z-10 space-y-1 md:space-y-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-[10px] md:text-xs font-bold uppercase tracking-wider text-white backdrop-blur-sm">
                                    Promo Spesial ✨
                                </span>
                                <h3 class="text-lg md:text-2xl font-black tracking-tight leading-tight drop-shadow-sm">
                                    Temukan Penawaran Terbaik Hari Ini
                                </h3>
                                <p class="text-xs md:text-sm font-medium text-white/95 max-w-lg leading-relaxed">
                                    Nikmati promo eksklusif penerbangan domestik dan internasional. Klik di sini untuk melihat info selengkapnya.
                                </p>
                            </div>
                        </a>
                    @endif
                @endif
            </section>



        </div>
    </div>

    @if (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff')))
        <!-- Invoice Generation Wizard Modal -->
        <div x-data="invoiceWizard()"
             @open-invoice-wizard.window="initWizard($event.detail.flight, $event.detail.filters)"
             x-show="open"
             style="display: none;"
             class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
             @keydown.escape.window="open = false">
             
            <div class="relative bg-white rounded-[32px] border border-slate-200 shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto flex flex-col"
                 @click.away="open = false">
                 
                <!-- Header -->
                <div class="px-8 pt-8 pb-4 flex justify-between items-center border-b border-slate-100">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Buat Invoice Penerbangan</h3>
                        <p class="text-xs text-slate-500 font-medium">Buat tagihan manual untuk airline virtual ZZ</p>
                    </div>
                    <button @click="open = false" type="button" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-full p-2 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Stepper Progress Bar -->
                <div class="px-8 py-4 bg-slate-50/50 flex justify-between items-center gap-2 border-b border-slate-100 text-xs font-bold text-slate-400">
                    <div class="flex items-center gap-1.5" :class="step >= 1 ? 'text-[#0361fc]' : ''">
                        <span class="h-5 w-5 rounded-full flex items-center justify-center text-[10px] border" :class="step >= 1 ? 'border-[#0361fc] bg-blue-50' : 'border-slate-300'">1</span>
                        <span>Penerbangan</span>
                    </div>
                    <div class="h-px bg-slate-200 grow"></div>
                    <div class="flex items-center gap-1.5" :class="step >= 2 ? 'text-[#0361fc]' : ''">
                        <span class="h-5 w-5 rounded-full flex items-center justify-center text-[10px] border" :class="step >= 2 ? 'border-[#0361fc] bg-blue-50' : 'border-slate-300'">2</span>
                        <span>Client</span>
                    </div>
                    <div class="h-px bg-slate-200 grow"></div>
                    <div class="flex items-center gap-1.5" :class="step >= 3 ? 'text-[#0361fc]' : ''">
                        <span class="h-5 w-5 rounded-full flex items-center justify-center text-[10px] border" :class="step >= 3 ? 'border-[#0361fc] bg-blue-50' : 'border-slate-300'">3</span>
                        <span>Ringkasan</span>
                    </div>
                    <div class="h-px bg-slate-200 grow"></div>
                    <div class="flex items-center gap-1.5" :class="step >= 4 ? 'text-emerald-600' : ''">
                        <span class="h-5 w-5 rounded-full flex items-center justify-center text-[10px] border" :class="step >= 4 ? 'border-emerald-600 bg-emerald-50' : 'border-slate-300'">4</span>
                        <span>Selesai</span>
                    </div>
                </div>

                <!-- Step Contents -->
                <div class="p-8 grow overflow-y-auto">
                    
                    <!-- Error Notification -->
                    <template x-if="errorMessage">
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl text-sm font-semibold text-rose-700">
                            <span x-text="errorMessage"></span>
                        </div>
                    </template>

                    <!-- STEP 1: Flight details -->
                    <div x-show="step === 1" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Airline Name</label>
                                <input type="text" x-model="flight.airline_name" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Flight Number</label>
                                <input type="text" x-model="flight.flight_numbers" placeholder="Contoh: ZZ 123" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Rute Asal (IATA)</label>
                                <input type="text" x-model="flight.origin" maxlength="3" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none uppercase" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Rute Tujuan (IATA)</label>
                                <input type="text" x-model="flight.destination" maxlength="3" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none uppercase" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Tanggal Pergi</label>
                                <input type="date" x-model="flight.depart_date" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Waktu Pergi</label>
                                <input type="time" x-model="flight.depart_time" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4" x-show="flight.trip_type === 'R'">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Tanggal Pulang</label>
                                <input type="date" x-model="flight.return_date" :required="flight.trip_type === 'R'" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Waktu Pulang</label>
                                <input type="time" x-model="flight.return_time" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Kelas Kabin</label>
                                <select x-model="flight.cabin_class" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none">
                                    <option value="economy">Economy</option>
                                    <option value="premium_economy">Premium Economy</option>
                                    <option value="business">Business</option>
                                    <option value="first">First</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Harga Pokok (IDR)</label>
                                <input type="number" x-model="flight.price_value" @input="calculateTotal()" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase">Pajak/Biaya (IDR)</label>
                                <input type="number" x-model="flight.tax" @input="calculateTotal()" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none">
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-slate-50 rounded-2xl flex flex-col gap-2">
                            <div class="flex justify-between items-center">
                                <label class="text-xs font-bold text-slate-500 uppercase">Total Harga (IDR)</label>
                                <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-full">Dapat diedit manual</span>
                            </div>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-sm font-extrabold text-slate-400">Rp</span>
                                <input type="number" x-model="flight.total" class="block w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm font-black text-[#0361fc] focus:border-[#0361fc] focus:outline-none" required>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Client & Passenger Details -->
                    <div x-show="step === 2" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase">Pilih Akun Client (User ID)</label>
                            <select x-model="clientId" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none" required>
                                <option value="">-- Pilih Akun --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user['id'] }}">{{ $user['name'] }} ({{ $user['email'] }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-[10px] text-slate-400 font-medium">Akun client ini yang akan menerima invoice di dashboard mereka.</p>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <h4 class="text-xs font-bold text-[#0361fc] uppercase tracking-wider mb-3">Informasi Traveler / Penumpang</h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase">Nama Traveler</label>
                                    <input type="text" x-model="travelerName" placeholder="Contoh: John Doe" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase">Email Traveler</label>
                                        <input type="email" x-model="travelerEmail" placeholder="client@example.com" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase">Telepon Traveler</label>
                                        <input type="text" x-model="travelerPhone" placeholder="+62..." class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Extra Baggage Section -->
                        <div class="border-t border-slate-100 pt-4">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Pilih Bagasi Tambahan (Extra Baggage)</label>
                            <div class="flex flex-wrap gap-2.5">
                                <template x-for="bag in baggageOptions" :key="bag.weight">
                                    <button type="button"
                                            @click="selectBaggage(bag)"
                                            :class="selectedBaggageWeight === bag.weight 
                                                ? 'bg-blue-50 border-2 border-[#0361fc] text-[#0361fc] shadow-sm' 
                                                : 'bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100'"
                                            class="flex flex-col items-center justify-center p-3 rounded-2xl min-w-[105px] transition cursor-pointer text-center outline-none">
                                        <span class="text-xs font-extrabold" x-text="'+' + bag.weight + ' kg'"></span>
                                        <span class="text-[10px] mt-1 font-bold" 
                                              x-text="bag.price === 0 ? 'Gratis' : 'Rp ' + Number(bag.price).toLocaleString('id-ID')"></span>
                                    </button>
                                </template>
                            </div>
                            <p class="mt-2 text-[10px] text-slate-400 font-medium">Bagasi default: Kabin 7kg & Check-in 20kg. Tambahan bagasi akan ditambahkan pada berat check-in.</p>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <label class="block text-xs font-bold text-slate-500 uppercase">Metode Pembayaran</label>
                            <select x-model="paymentMethodId" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#0361fc] focus:outline-none" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach($paymentMethods as $pm)
                                    <option value="{{ $pm['id'] }}">{{ $pm['name'] }} ({{ $pm['provider'] === 'xendit' ? 'Online/Direct' : 'Manual Transfer' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- STEP 3: Summary -->
                    <div x-show="step === 3" class="space-y-6">
                        <div class="p-5 bg-slate-50 rounded-2xl space-y-4">
                            <h4 class="text-sm font-extrabold text-slate-900 border-b border-slate-200 pb-2">Rincian Penerbangan</h4>
                            <div class="grid grid-cols-2 gap-y-3 text-xs">
                                <div><span class="text-slate-500 font-semibold">Airline:</span> <span class="font-bold text-slate-950" x-text="flight.airline_name"></span></div>
                                <div><span class="text-slate-500 font-semibold">Flight No:</span> <span class="font-bold text-[#0361fc]" x-text="flight.flight_numbers || '-'"></span></div>
                                <div><span class="text-slate-500 font-semibold">Rute:</span> <span class="font-bold text-slate-950" x-text="flight.origin.toUpperCase() + ' ➔ ' + flight.destination.toUpperCase()"></span></div>
                                <div><span class="text-slate-500 font-semibold">Kelas:</span> <span class="font-bold text-slate-950 capitalize" x-text="flight.cabin_class"></span></div>
                                <div><span class="text-slate-500 font-semibold">Keberangkatan:</span> <span class="font-bold text-slate-950" x-text="flight.depart_date + ' ' + (flight.depart_time || '')"></span></div>
                                <div x-show="flight.trip_type === 'R'"><span class="text-slate-500 font-semibold">Kepulangan:</span> <span class="font-bold text-slate-950" x-text="flight.return_date + ' ' + (flight.return_time || '')"></span></div>
                                <div><span class="text-slate-500 font-semibold">Bagasi:</span> <span class="font-bold text-slate-950" x-text="'7kg Kabin + 20kg Check-in' + (selectedBaggageWeight > 0 ? ' + ' + selectedBaggageWeight + 'kg Extra' : '')"></span></div>
                            </div>
                        </div>

                        <div class="p-5 bg-slate-50 rounded-2xl space-y-4">
                            <h4 class="text-sm font-extrabold text-slate-900 border-b border-slate-200 pb-2">Detail Traveler & Pembayaran</h4>
                            <div class="grid grid-cols-2 gap-y-3 text-xs">
                                <div><span class="text-slate-500 font-semibold">Nama Penumpang:</span> <span class="font-bold text-slate-950" x-text="travelerName"></span></div>
                                <div><span class="text-slate-500 font-semibold">Email:</span> <span class="font-bold text-slate-950" x-text="travelerEmail"></span></div>
                                <div><span class="text-slate-500 font-semibold">Telepon:</span> <span class="font-bold text-slate-950" x-text="travelerPhone || '-'"></span></div>
                                <div>
                                    <span class="text-slate-500 font-semibold">Metode Bayar:</span> 
                                    <span class="font-bold text-slate-950">
                                        @foreach($paymentMethods as $pm)
                                            <span x-show="paymentMethodId == '{{ $pm['id'] }}'">{{ $pm['name'] }}</span>
                                        @endforeach
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 bg-[#edf4ff]/40 rounded-2xl space-y-3 border border-[#edf4ff]">
                                <div class="flex justify-between text-xs font-semibold text-slate-600"><span>Harga Tiket</span><span class="font-bold text-slate-900" x-text="'Rp ' + Number(flight.price_value).toLocaleString('id-ID')"></span></div>
                                <div class="flex justify-between text-xs font-semibold text-slate-600" x-show="selectedBaggageWeight > 0">
                                    <span>Bagasi Tambahan (+<span x-text="selectedBaggageWeight"></span> kg)</span>
                                    <span class="font-bold text-slate-900" x-text="'Rp ' + Number(selectedBaggagePrice).toLocaleString('id-ID')"></span>
                                </div>
                                <div class="flex justify-between text-xs font-semibold text-slate-600 border-b border-slate-200 pb-2"><span>Pajak & Biaya</span><span class="font-bold text-slate-900" x-text="'Rp ' + Number(flight.tax).toLocaleString('id-ID')"></span></div>
                                <div class="flex justify-between text-sm font-extrabold text-slate-900"><span>Total Tagihan</span><span class="text-lg font-black text-[#0361fc]" x-text="'Rp ' + Number(flight.total).toLocaleString('id-ID')"></span></div>
                            </div>
                    </div>

                    <!-- STEP 4: Success & Links -->
                    <div x-show="step === 4" class="space-y-6 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 border border-emerald-100 shadow-sm">
                            <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        
                        <div>
                            <h4 class="text-2xl font-black text-slate-900">Invoice Berhasil Dibuat!</h4>
                            <p class="mt-2 text-sm text-slate-500 font-semibold">Kode Booking / Invoice: <span class="text-slate-900 font-bold" x-text="referenceNumber"></span></p>
                        </div>

                        <div class="space-y-3 pt-4 text-left">
                            <div class="p-4 bg-slate-50 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase">Link Invoice Client</p>
                                    <p class="text-xs font-bold text-slate-600 truncate max-w-sm mt-0.5" x-text="invoiceUrl"></p>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button @click="copyToClipboard(invoiceUrl)" class="rounded-full bg-white border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50">Salin Link</button>
                                    <a :href="invoiceUrl" target="_blank" class="rounded-full bg-[#0361fc] px-4 py-1.5 text-xs font-bold text-white hover:bg-blue-700">Buka</a>
                                </div>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-3" x-show="xenditUrl">
                                <div>
                                    <p class="text-xs font-bold text-[#0361fc] uppercase">Direct Payment Link (Xendit)</p>
                                    <p class="text-xs font-bold text-slate-600 truncate max-w-sm mt-0.5" x-text="xenditUrl"></p>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button @click="copyToClipboard(xenditUrl)" class="rounded-full bg-white border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50">Salin Link</button>
                                    <a :href="xenditUrl" target="_blank" class="rounded-full bg-[#0361fc] px-4 py-1.5 text-xs font-bold text-white hover:bg-blue-700">Bayar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="px-8 py-6 border-t border-slate-100 flex justify-between bg-slate-50 rounded-b-[32px]">
                    <button type="button" 
                            x-show="step > 1 && step < 4" 
                            @click="prevStep()" 
                            class="rounded-full border border-slate-200 bg-white px-6 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                        Sebelumnya
                    </button>
                    
                    <div class="grow"></div>
                    
                    <button type="button" 
                            x-show="step < 3" 
                            @click="nextStep()" 
                            class="rounded-full bg-[#0361fc] px-6 py-2.5 text-sm font-bold text-white hover:bg-blue-700 transition">
                        Lanjutkan
                    </button>

                    <button type="button" 
                            x-show="step === 3" 
                            @click="generateInvoice()" 
                            :disabled="loading"
                            class="rounded-full bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
                        <template x-if="loading">
                            <span class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        </template>
                        Generate Invoice
                    </button>

                    <button type="button" 
                            x-show="step === 4" 
                            @click="open = false" 
                            class="rounded-full bg-slate-900 px-6 py-2.5 text-sm font-bold text-white hover:bg-slate-800 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

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

                Alpine.data('airlineFilter', (allCodes) => ({
                    selectedAirlines: [],
                    toggleAirline(code) {
                        const idx = this.selectedAirlines.indexOf(code);
                        if (idx === -1) this.selectedAirlines.push(code);
                        else this.selectedAirlines.splice(idx, 1);
                    },
                    isVisible(code) {
                        return this.selectedAirlines.length === 0 || this.selectedAirlines.includes(code);
                    },
                }));

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
                    mobilePassengerOpen: false,
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

                Alpine.data('invoiceWizard', () => ({
                    open: false,
                    step: 1,
                    flight: {
                        airline: '',
                        airline_name: '',
                        flight_numbers: '',
                        origin: '',
                        destination: '',
                        depart_date: '',
                        depart_time: '',
                        return_date: '',
                        return_time: '',
                        cabin_class: 'economy',
                        trip_type: 'O',
                        price_value: 0,
                        tax: 0,
                        total: 0
                    },
                    clientId: '',
                    travelerName: '',
                    travelerEmail: '',
                    travelerPhone: '',
                    paymentMethodId: '',
                    invoiceUrl: '',
                    xenditUrl: '',
                    referenceNumber: '',
                    loading: false,
                    errorMessage: '',
                    
                    // Baggage pricing configuration
                    baggageOptions: [
                        { weight: 0, price: 0 },
                        { weight: 20, price: 1028872 },
                        { weight: 25, price: 1372268 },
                        { weight: 30, price: 1688253 },
                        { weight: 40, price: 2409026 },
                        { weight: 50, price: 3134682 },
                        { weight: 60, price: 4072871 }
                    ],
                    selectedBaggageWeight: 0,
                    selectedBaggagePrice: 0,
                    
                    initWizard(flightData, filters) {
                        this.flight.airline = flightData.airline || 'ZZ';
                        this.flight.airline_name = flightData.airline_name || 'Virtual Airline';
                        this.flight.flight_numbers = flightData.flight_numbers || '';
                        this.flight.origin = filters.origin || flightData.start_location || '';
                        this.flight.destination = filters.destination || flightData.end_location || '';
                        this.flight.depart_date = filters.depart_date || '';
                        this.flight.depart_time = flightData.start_time || '';
                        this.flight.return_date = filters.return_date || '';
                        this.flight.return_time = flightData.end_time || '';
                        this.flight.cabin_class = flightData.class || 'economy';
                        this.flight.trip_type = filters.trip_type || 'O';
                        this.flight.price_value = flightData.price_value || 0;
                        this.flight.tax = 0;
                        
                        this.selectedBaggageWeight = 0;
                        this.selectedBaggagePrice = 0;
                        this.calculateTotal();

                        this.clientId = '';
                        this.travelerName = '';
                        this.travelerEmail = '';
                        this.travelerPhone = '';
                        this.paymentMethodId = '';
                        this.invoiceUrl = '';
                        this.xenditUrl = '';
                        this.referenceNumber = '';
                        this.step = 1;
                        this.errorMessage = '';
                        this.open = true;
                    },
                    calculateTotal() {
                        this.flight.total = Number(this.flight.price_value) + Number(this.flight.tax) + Number(this.selectedBaggagePrice);
                    },
                    selectBaggage(bag) {
                        this.selectedBaggageWeight = bag.weight;
                        this.selectedBaggagePrice = bag.price;
                        this.calculateTotal();
                    },
                    nextStep() {
                        if (this.step === 1) {
                            if (!this.flight.origin || !this.flight.destination || !this.flight.depart_date) {
                                alert('Silakan isi rute asal, tujuan, dan tanggal keberangkatan.');
                                return;
                            }
                            this.step = 2;
                        } else if (this.step === 2) {
                            if (!this.clientId) {
                                alert('Silakan pilih client user.');
                                return;
                            }
                            if (!this.travelerName || !this.travelerEmail) {
                                alert('Silakan isi nama dan email traveler.');
                                return;
                            }
                            if (!this.paymentMethodId) {
                                alert('Silakan pilih metode pembayaran.');
                                return;
                            }
                            this.step = 3;
                        }
                    },
                    prevStep() {
                        if (this.step > 1 && this.step < 4) {
                            this.step--;
                        }
                    },
                    async generateInvoice() {
                        this.loading = true;
                        this.errorMessage = '';
                        try {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            const response = await fetch('/admin/flights/generate-invoice', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    client_id: this.clientId,
                                    traveler_name: this.travelerName,
                                    traveler_email: this.travelerEmail,
                                    traveler_phone: this.travelerPhone,
                                    payment_method_id: this.paymentMethodId,
                                    flight: this.flight,
                                    baggage_weight: this.selectedBaggageWeight,
                                    baggage_price: this.selectedBaggagePrice
                                })
                            });
                            const data = await response.json();
                            if (response.ok) {
                                this.invoiceUrl = data.invoice_url;
                                this.xenditUrl = data.xendit_url;
                                this.referenceNumber = data.reference_number;
                                this.step = 4;
                            } else {
                                this.errorMessage = data.message || 'Gagal membuat invoice.';
                            }
                        } catch (e) {
                            this.errorMessage = 'Terjadi kesalahan jaringan atau server.';
                        } finally {
                            this.loading = false;
                        }
                    },
                    copyToClipboard(text) {
                        navigator.clipboard.writeText(text);
                        alert('Link disalin ke clipboard!');
                    }
                }));
            });
        </script>
    @endpush
</x-layouts.app>
