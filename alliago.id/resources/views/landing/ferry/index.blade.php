<x-layouts.app
    title="Tiket Ferry | Alliago.id — Pesan Tiket Kapal Cepat"
    description="Pesan tiket kapal ferry lintas selat dengan mudah. Rute Port Dickson–Dumai, Port Dickson–Tanjung Balai, dan Stulang Laut–Batam Center. Harga terjangkau, pemesanan cepat bersama Alliago.id."
>
<x-home.header />

{{-- ══════════════════════════════════════════════════════
     HERO — rounded image + gradient overlay + title
══════════════════════════════════════════════════════════ --}}
<section class="relative pt-24">
    <div class="page-wrapper">

        {{-- Banner image --}}
        <div class="relative h-[260px] overflow-hidden rounded-[32px] shadow-[0_20px_50px_rgba(15,23,42,0.18)] sm:h-[320px] lg:h-[360px]">
            <img
                src="https://images.unsplash.com/photo-1548574505-5e239809ee19?q=85&w=2400&auto=format&fit=crop"
                alt="Kapal Ferry Lintas Selat"
                class="absolute inset-0 h-full w-full object-cover object-center"
                loading="eager"
            >
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/60 via-slate-950/25 to-slate-950/50"></div>

            {{-- Title overlay --}}
            <div class="relative z-10 flex h-full items-start px-6 pt-8 sm:px-10 sm:pt-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-white/70">Transportasi Laut</p>
                    <h1 class="mt-2 text-3xl font-extrabold leading-tight tracking-tight text-white drop-shadow sm:text-4xl lg:text-5xl">
                        Tiket Kapal Ferry
                    </h1>
                    <p class="mt-2 max-w-xl text-sm font-medium text-white/80 drop-shadow sm:text-base">
                        Lintas selat mudah — pilih rute, isi data, bayar. Tiket langsung di genggaman.
                    </p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             TICKET CARDS — float below hero
        ══════════════════════════════════════════════════════════ --}}
        <div class="relative z-20 -mt-10 sm:-mt-14">
            <div class="overflow-hidden rounded-2xl bg-white shadow-[0_24px_60px_rgba(0,0,0,0.18)] ring-1 ring-slate-200/60">

                {{-- Section label bar --}}
                <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-7">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#EDF4FF]">
                        <svg class="h-4 w-4 text-[#0361fc]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75l3.75-9h12l3.75 9M2.25 12.75h19.5M2.25 12.75L.75 18.75h22.5L21.75 12.75M12 4.5V2.25" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Pilih Rute Ferry</p>
                        <p class="text-xs text-slate-400">Klik rute untuk mulai pemesanan</p>
                    </div>
                </div>

                {{-- Route cards grid --}}
                <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                    @foreach ($routes as $route)
                    @if($canOrder)
                    <button
                        type="button"
                        onclick="openWizard({{ $route->id }}, '{{ $route->origin }}', '{{ $route->destination }}', {{ $route->price }}, '{{ $route->origin }} → {{ $route->destination }}')"
                        class="group flex flex-col gap-3 px-5 py-5 text-left transition-colors hover:bg-[#EDF4FF]/60 sm:px-6 sm:py-6 cursor-pointer relative overflow-hidden"
                    >
                    @else
                    <a
                        href="https://wa.me/6281334455616?text={{ urlencode('Halo, saya ingin pesan tiket ferry ' . $route->origin . ' → ' . $route->destination . ' (RM ' . number_format($route->price, 0, ',', '.') . ')') }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group flex flex-col gap-3 px-5 py-5 text-left transition-colors hover:bg-[#EDF4FF]/60 sm:px-6 sm:py-6 cursor-pointer relative overflow-hidden"
                    >
                    @endif
                        <div class="relative z-10 flex flex-col h-full gap-3">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-sm font-extrabold text-slate-900 truncate">{{ $route->origin }}</span>
                                <svg class="h-3.5 w-3.5 text-[#0361fc] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                                <span class="text-sm font-extrabold text-slate-900 truncate">{{ $route->destination }}</span>
                            </div>
                            <div>
                                <p class="text-xl font-black text-[#0361fc] leading-none">RM {{ number_format($route->price, 0, ',', '.') }}</p>
                                <p class="mt-1 text-[11px] font-medium text-slate-400">per penumpang</p>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs font-bold text-[#0361fc] group-hover:underline mt-auto">
                                @if($canOrder) Pesan Sekarang @else Hubungi via WhatsApp @endif
                                <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </div>
                        </div>

                        @if ($route->ship_image_path)
                        <div class="absolute right-0 top-0 bottom-0 w-[45%] overflow-hidden pointer-events-none z-0">
                            <img
                                src="{{ \Storage::url($route->ship_image_path) }}"
                                alt="Kapal {{ $route->origin }} - {{ $route->destination }}"
                                class="h-full w-full object-cover object-right transition-transform duration-300 group-hover:scale-105"
                            >
                            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/50 to-transparent group-hover:from-[#EDF4FF]/90 group-hover:via-[#EDF4FF]/50 transition-colors duration-200"></div>
                        </div>
                        @endif
                    @if($canOrder) </button> @else </a> @endif
                    @endforeach
                </div>

            </div>
        </div>

        {{-- Info strip --}}
        <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4 pb-16">
            @foreach ([
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Terpercaya', 'sub' => 'Layanan resmi'],
                ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Cepat', 'sub' => 'Booking < 2 menit'],
                ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'label' => 'Pembayaran Aman', 'sub' => 'Transfer & Xendit'],
                ['icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Support 24/7', 'sub' => 'Siap membantu'],
            ] as $item)
            <div class="flex items-center gap-3 rounded-2xl bg-white border border-slate-200 px-4 py-3">
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-[#EDF4FF]">
                    <svg class="h-4 w-4 text-[#0361fc]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ $item['label'] }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ $item['sub'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

@if($canOrder)
{{-- ══════════════════════════════════════════════════════
     BOOKING WIZARD MODAL
══════════════════════════════════════════════════════════ --}}
<div
    x-data="ferryWizard()"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @open-ferry-wizard.window="openWith($event.detail)"
    @keydown.escape.window="if(step < 4) close()"
    class="fixed inset-0 z-[200] flex items-end sm:items-center justify-center p-0 sm:p-4"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="if(step < 4) close()"></div>

    {{-- Panel --}}
    <div
        class="relative w-full sm:max-w-lg bg-white sm:rounded-3xl shadow-2xl overflow-hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
        x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100"
        x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
    >
        {{-- Progress bar --}}
        <div class="h-1 bg-slate-100">
            <div class="h-full bg-[#0361fc] transition-all duration-500"
                 :style="`width: ${step < 4 ? (step / 3) * 100 : 100}%`"></div>
        </div>

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-[#0361fc]" x-text="stepLabel()"></p>
                <h2 class="mt-0.5 text-lg font-extrabold tracking-tight text-slate-900" x-text="stepTitle()"></h2>
            </div>
            <button
                @click="if(step < 4) close()"
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 transition cursor-pointer"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-6 max-h-[65vh] overflow-y-auto">

            {{-- Step 1: Informasi Tiket --}}
            <div x-show="step === 1" x-transition>
                {{-- Route card --}}
                <div class="mb-6 rounded-2xl bg-[#EDF4FF] border border-[#0361fc]/10 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#0361fc]/70 mb-3">Rute Perjalanan</p>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 text-center">
                            <p class="text-xs font-medium text-slate-500">Asal</p>
                            <p class="text-base font-extrabold text-slate-900 mt-0.5" x-text="ticket.origin"></p>
                        </div>
                        <div class="flex flex-col items-center gap-1 px-2">
                            <svg class="h-5 w-5 text-[#0361fc]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                            <span class="text-[10px] font-bold text-[#0361fc]/60 uppercase tracking-wider">Ferry</span>
                        </div>
                        <div class="flex-1 text-center">
                            <p class="text-xs font-medium text-slate-500">Tujuan</p>
                            <p class="text-base font-extrabold text-slate-900 mt-0.5" x-text="ticket.destination"></p>
                        </div>
                    </div>
                    <div class="mt-3 border-t border-[#0361fc]/10 pt-3 flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500">Harga/penumpang</span>
                        <span class="text-base font-black text-[#0361fc]" x-text="`RM ${formatPrice(ticket.price)}`"></span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Tanggal Keberangkatan</label>
                        <div class="relative">
                            <button type="button"
                                @click="calOpen = !calOpen"
                                class="w-full flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm cursor-pointer transition hover:border-[#0361fc]/40 focus:outline-none focus:ring-2 focus:ring-[#0361fc]"
                                :class="form.travel_date ? 'text-slate-900' : 'text-slate-400'"
                            >
                                <span x-text="form.travel_date ? formatDate(form.travel_date) : 'Pilih tanggal keberangkatan'"></span>
                                <svg class="h-4 w-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </button>

                            <div x-show="calOpen"
                                @click.outside="calOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute left-0 right-0 z-50 mt-1 rounded-2xl border border-slate-200 bg-white shadow-xl p-4"
                                style="display:none;"
                            >
                                <div class="flex items-center justify-between mb-3">
                                    <button type="button" @click="calPrev()" class="flex h-8 w-8 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100 transition cursor-pointer">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <span class="text-sm font-bold text-slate-900" x-text="calMonthName()"></span>
                                    <button type="button" @click="calNext()" class="flex h-8 w-8 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100 transition cursor-pointer">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-7 mb-1">
                                    <template x-for="d in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']">
                                        <div class="py-1 text-center text-[11px] font-bold text-slate-400" x-text="d"></div>
                                    </template>
                                </div>

                                <div class="grid grid-cols-7 gap-y-0.5">
                                    <template x-for="cell in calGrid()" :key="cell.key">
                                        <button
                                            type="button"
                                            @click="calSelect(cell)"
                                            :disabled="cell.disabled"
                                            :class="{
                                                'text-slate-200 cursor-not-allowed': cell.disabled,
                                                'text-slate-400': !cell.disabled && cell.type === \'other\',
                                                'text-slate-700 hover:bg-[#EDF4FF] hover:text-[#0361fc] rounded-full cursor-pointer': !cell.disabled && cell.type === \'current\' && !cell.isSelected,
                                                'bg-[#0361fc] text-white rounded-full font-bold cursor-pointer': cell.isSelected,
                                                'ring-2 ring-[#0361fc] rounded-full text-[#0361fc] font-bold': cell.isToday && !cell.isSelected,
                                            }"
                                            class="flex h-8 w-full items-center justify-center text-xs transition"
                                            x-text="cell.label"
                                        ></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <p x-show="errors.travel_date" x-text="errors.travel_date" class="mt-1 text-xs font-medium text-rose-500"></p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Jumlah Penumpang</label>
                        <div class="flex items-center gap-4">
                            <button type="button"
                                @click="form.passenger_count = Math.max(1, form.passenger_count - 1)"
                                class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xl transition cursor-pointer">−</button>
                            <span class="w-10 text-center text-2xl font-extrabold text-slate-900" x-text="form.passenger_count"></span>
                            <button type="button"
                                @click="form.passenger_count = Math.min(10, form.passenger_count + 1)"
                                class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xl transition cursor-pointer">+</button>
                            <span class="text-sm text-slate-500">penumpang</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">
                            Subtotal: <span class="font-bold text-slate-900" x-text="`RM ${formatPrice(ticket.price * form.passenger_count)}`"></span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Step 2: Informasi Pembeli --}}
            <div x-show="step === 2" x-transition>
                <div class="space-y-4">
                    @if (isset($users) && count($users) > 0)
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Pilih Akun Client (User ID)</label>
                        <select x-model="form.client_id"
                            @change="const selectedUser = users.find(u => u.id == $event.target.value); if(selectedUser) { form.passenger_name = selectedUser.name; form.passenger_email = selectedUser.email; form.passenger_phone = selectedUser.phone || ''; }"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#0361fc] focus:border-transparent transition" required>
                            <option value="">-- Pilih Akun --</option>
                            <template x-for="u in users" :key="u.id">
                                <option :value="u.id" x-text="`${u.name} (${u.email})`"></option>
                            </template>
                        </select>
                        <p class="mt-1 text-[10px] text-slate-400 font-medium">Akun client ini yang akan menerima invoice di dashboard mereka.</p>
                        <p x-show="errors.client_id" x-text="errors.client_id" class="mt-1 text-xs font-medium text-rose-500"></p>
                    </div>
                    @endif

                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Nama Lengkap</label>
                        <input type="text" x-model="form.passenger_name"
                            placeholder="Nama sesuai identitas"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0361fc] focus:border-transparent transition">
                        <p x-show="errors.passenger_name" x-text="errors.passenger_name" class="mt-1 text-xs font-medium text-rose-500"></p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Email</label>
                        <input type="email" x-model="form.passenger_email"
                            placeholder="email@contoh.com"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0361fc] focus:border-transparent transition">
                        <p x-show="errors.passenger_email" x-text="errors.passenger_email" class="mt-1 text-xs font-medium text-rose-500"></p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">No. Telepon / WhatsApp</label>
                        <input type="tel" x-model="form.passenger_phone"
                            placeholder="08xx xxxx xxxx"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0361fc] focus:border-transparent transition">
                        <p x-show="errors.passenger_phone" x-text="errors.passenger_phone" class="mt-1 text-xs font-medium text-rose-500"></p>
                    </div>
                </div>
            </div>

            {{-- Step 3: Ringkasan --}}
            <div x-show="step === 3" x-transition>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 divide-y divide-slate-200 mb-4 overflow-hidden">
                    <div class="flex justify-between px-4 py-3 text-sm">
                        <span class="text-slate-500">Rute</span>
                        <span class="font-bold text-slate-900" x-text="`${ticket.origin} → ${ticket.destination}`"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3 text-sm">
                        <span class="text-slate-500">Tanggal</span>
                        <span class="font-bold text-slate-900" x-text="formatDate(form.travel_date)"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3 text-sm">
                        <span class="text-slate-500">Penumpang</span>
                        <span class="font-bold text-slate-900" x-text="`${form.passenger_count} orang`"></span>
                    </div>
                    <div class="bg-white px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2">Data Pembeli</p>
                        <p class="text-sm font-bold text-slate-900" x-text="form.passenger_name"></p>
                        <p class="text-xs text-slate-500 mt-0.5" x-text="form.passenger_email"></p>
                        <p class="text-xs text-slate-500" x-text="form.passenger_phone"></p>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3.5 bg-[#EDF4FF]">
                        <span class="text-sm font-bold text-slate-900">Total Pembayaran</span>
                        <span class="text-lg font-black text-[#0361fc]" x-text="`RM ${formatPrice(ticket.price * form.passenger_count)}`"></span>
                    </div>
                </div>

                <p x-show="submitError" x-text="submitError"
                   class="rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-600"></p>
            </div>

            {{-- Step 4: Selesai --}}
            <div x-show="step === 4" x-transition class="py-2 text-center">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full border-2 border-emerald-200 bg-emerald-50">
                    <svg class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900">Pesanan Berhasil Dibuat!</h3>
                <p class="mt-1 text-sm text-slate-500">No. Referensi</p>
                <p class="mt-0.5 text-lg font-black text-[#0361fc]" x-text="result.reference_number"></p>
                <p class="mt-4 text-sm text-slate-500">Klik <strong>Buka Invoice</strong> untuk melanjutkan ke pembayaran.</p>
            </div>

        </div>

        {{-- Footer --}}
        <div class="border-t border-slate-100 px-6 pb-6 pt-3">
            <div class="flex gap-3">
                <button x-show="step > 1 && step < 4" @click="step--" type="button"
                    class="flex-1 rounded-full border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                    Kembali
                </button>
                <button x-show="step < 3" @click="nextStep()" type="button"
                    class="flex-1 rounded-full bg-[#0361fc] px-5 py-3 text-sm font-bold text-white hover:bg-blue-700 transition cursor-pointer">
                    Lanjut
                </button>
                <button x-show="step === 3" @click="submit()" type="button"
                    :disabled="loading"
                    class="flex-1 rounded-full bg-[#0361fc] px-5 py-3 text-sm font-bold text-white hover:bg-blue-700 transition disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer"
                    x-text="loading ? 'Memproses...' : 'Buat Pesanan'">
                </button>
                <a x-show="step === 4" :href="result.invoice_url"
                    class="flex-1 text-center rounded-full bg-[#0361fc] px-5 py-3 text-sm font-bold text-white hover:bg-blue-700 transition">
                    Buka Invoice
                </a>
            </div>
        </div>
    </div>
</div>

@endif

<x-home.footer />

@if($canOrder)
@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function ferryWizard() {
        return {
            users: @json($users ?? []),
            open: false,
            step: 1,
            loading: false,
            ticket: { key: '', origin: '', destination: '', price: 0, label: '' },
            form: { client_id: '', travel_date: '', passenger_count: 1, passenger_name: '', passenger_email: '', passenger_phone: '' },
            errors: {},
            submitError: '',
            result: { reference_number: '', invoice_url: '' },
            calOpen: false,
            calYear: new Date().getFullYear(),
            calMonth: new Date().getMonth(),

            openWith(detail) {
                this.ticket = detail;
                this.form = { client_id: '', travel_date: '', passenger_count: 1, passenger_name: '', passenger_email: '', passenger_phone: '' };
                this.errors = {};
                this.submitError = '';
                this.step = 1;
                this.open = true;
            },

            close() { this.open = false; },

            stepLabel() {
                return this.step === 4 ? 'Selesai' : `Langkah ${this.step} dari 3`;
            },

            stepTitle() {
                return { 1: 'Informasi Tiket', 2: 'Informasi Pembeli', 3: 'Ringkasan Pesanan', 4: 'Pesanan Dibuat' }[this.step] || '';
            },

            minDate() { return new Date().toISOString().split('T')[0]; },

            formatPrice(n) { return new Intl.NumberFormat('en-US').format(n); },

            formatDate(d) {
                if (!d) return '-';
                return new Date(d + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            },

            calMonthName() {
                return new Date(this.calYear, this.calMonth, 1)
                    .toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            },

            calGrid() {
                const today = new Date(); today.setHours(0, 0, 0, 0);
                const firstDay = new Date(this.calYear, this.calMonth, 1).getDay();
                const daysInMonth = new Date(this.calYear, this.calMonth + 1, 0).getDate();
                const prevMonthDays = new Date(this.calYear, this.calMonth, 0).getDate();
                const cells = [];

                for (let i = firstDay - 1; i >= 0; i--) {
                    cells.push({ label: prevMonthDays - i, type: 'other', disabled: true, key: `p${i}` });
                }

                for (let d = 1; d <= daysInMonth; d++) {
                    const dt = new Date(this.calYear, this.calMonth, d);
                    const str = `${this.calYear}-${String(this.calMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                    cells.push({
                        label: d,
                        type: 'current',
                        disabled: dt < today,
                        isToday: dt.getTime() === today.getTime(),
                        isSelected: this.form.travel_date === str,
                        dateStr: str,
                        key: str,
                    });
                }

                const remaining = 42 - cells.length;
                for (let d = 1; d <= remaining; d++) {
                    cells.push({ label: d, type: 'other', disabled: true, key: `n${d}` });
                }

                return cells;
            },

            calPrev() {
                if (this.calMonth === 0) { this.calMonth = 11; this.calYear--; }
                else this.calMonth--;
            },

            calNext() {
                if (this.calMonth === 11) { this.calMonth = 0; this.calYear++; }
                else this.calMonth++;
            },

            calSelect(cell) {
                if (cell.disabled || cell.type !== 'current') return;
                this.form.travel_date = cell.dateStr;
                this.calOpen = false;
            },

            nextStep() {
                this.errors = {};
                if (this.step === 1) {
                    if (!this.form.travel_date) { this.errors.travel_date = 'Pilih tanggal keberangkatan.'; return; }
                    if (this.form.travel_date < this.minDate()) { this.errors.travel_date = 'Tanggal tidak boleh di masa lalu.'; return; }
                }
                if (this.step === 2) {
                    if (this.users.length > 0 && !this.form.client_id) { this.errors.client_id = 'Pilih akun client.'; return; }
                    if (!this.form.passenger_name.trim()) { this.errors.passenger_name = 'Nama wajib diisi.'; return; }
                    if (!this.form.passenger_email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.passenger_email)) {
                        this.errors.passenger_email = 'Email tidak valid.'; return;
                    }
                    if (!this.form.passenger_phone.trim()) { this.errors.passenger_phone = 'No. telepon wajib diisi.'; return; }
                }
                this.step++;
            },

            async submit() {
                this.loading = true;
                this.submitError = '';
                try {
                    const res = await fetch('{{ route('ferry.order.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            route_id: this.ticket.key,
                            passenger_name: this.form.passenger_name,
                            passenger_email: this.form.passenger_email,
                            passenger_phone: this.form.passenger_phone,
                            travel_date: this.form.travel_date,
                            passenger_count: this.form.passenger_count,
                            client_id: this.form.client_id,
                        }),
                    });
                    const data = await res.json();
                    if (res.ok && data.status === 'success') {
                        this.result = data;
                        this.step = 4;
                    } else if (data.errors) {
                        const firstKey = Object.keys(data.errors)[0];
                        this.submitError = data.errors[firstKey][0];
                    } else {
                        this.submitError = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                    }
                } catch (e) {
                    this.submitError = 'Gagal terhubung ke server. Silakan coba lagi.';
                } finally {
                    this.loading = false;
                }
            },
        };
    }

    function openWizard(key, origin, destination, price, label) {
        window.dispatchEvent(new CustomEvent('open-ferry-wizard', {
            detail: { key, origin, destination, price, label }
        }));
    }
</script>
@endpush
@endif

</x-layouts.app>
