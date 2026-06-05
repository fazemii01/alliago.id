<x-layouts.app
    title="Kurs Mata Uang Real-Time | Alliago.id"
    description="Pantau nilai tukar mata uang asing real-time terupdate. Dilengkapi dengan kalkulator konversi mata uang interaktif untuk mempermudah persiapan perjalanan Anda."
>
    <x-home.header />

    {{-- ══════════════════════════════════════════════════════
         HERO — rounded image + gradient overlay + title
         ══════════════════════════════════════════════════════ --}}
    <section class="relative pt-24">
        <div class="page-wrapper">

            {{-- Banner image --}}
            <div class="relative h-[260px] overflow-hidden rounded-[32px] shadow-[0_20px_50px_rgba(15,23,42,0.18)] sm:h-[320px] lg:h-[360px]">
                <img
                    src="https://images.unsplash.com/photo-1580519542036-c47de6196ba5?q=85&w=2400&auto=format&fit=crop"
                    alt="Kurs Mata Uang Global"
                    class="absolute inset-0 h-full w-full object-cover object-center"
                    loading="eager"
                >
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/60 via-slate-950/25 to-slate-950/50"></div>

                {{-- Title overlay --}}
                <div class="relative z-10 flex h-full items-start px-6 pt-8 sm:px-10 sm:pt-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-white/70">Layanan Finansial & Perjalanan</p>
                        <h1 class="mt-2 text-3xl font-extrabold leading-tight tracking-tight text-white drop-shadow sm:text-4xl lg:text-5xl">
                            Kurs Mata Uang
                        </h1>
                        <p class="mt-2 max-w-xl text-sm font-medium text-white/80 drop-shadow sm:text-base">
                            Pantau nilai tukar valuta asing terupdate untuk visa, tiket pesawat, dan akomodasi perjalanan global Anda.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════
                 MAIN CONTENT — grid floats below hero banner
                 ══════════════════════════════════════════════════════ --}}
            <div class="relative z-20 -mt-10 sm:-mt-14 px-2 sm:px-4">
                <div class="flex flex-col gap-8">
                    
                    <!-- Top: Calculator Convert Widget (Full width) -->
                    <div 
                        class="bg-white rounded-3xl shadow-[0_24px_60px_rgba(0,0,0,0.18)] ring-1 ring-slate-200/60 p-6 md:p-8"
                        x-data="currencyConverter()"
                    >
                        <div class="flex items-center gap-3 mb-6">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#EDF4FF]">
                                <svg class="h-4 w-4 text-[#0361fc]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900">Kalkulator Kurs Mata Uang</h3>
                                <p class="text-xs text-slate-400 font-medium">Konversi mata uang real-time instan</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-11 gap-5 items-end">
                            <!-- Input Amount -->
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Jumlah</label>
                                <input 
                                    type="number" 
                                    x-model.number="amount"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0361fc] focus:border-transparent transition"
                                    placeholder="Masukkan jumlah..."
                                >
                            </div>

                            <!-- Source Currency -->
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Dari</label>
                                <div class="relative">
                                    <select 
                                        x-model="fromCurrency"
                                        class="w-full appearance-none rounded-xl border border-slate-200 bg-white px-4 py-3 pr-10 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#0361fc] focus:border-transparent transition cursor-pointer"
                                    >
                                        <option value="IDR">IDR - Rupiah Indonesia</option>
                                        <template x-for="r in Object.values(rates)" :key="r.code">
                                            <option :value="r.code" x-text="`${r.code} - ${r.name}`"></option>
                                        </template>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Swap Button -->
                            <div class="flex justify-center md:pb-2.5 md:col-span-1">
                                <button 
                                    type="button"
                                    @click="swap()"
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0361fc] hover:bg-blue-700 text-white shadow-md border border-[#0361fc]/10 transition cursor-pointer transform hover:scale-105 duration-200"
                                >
                                    <svg class="h-5 w-5 hidden md:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    <svg class="h-5 w-5 block md:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Target Currency -->
                            <div class="md:col-span-4">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ke</label>
                                <div class="relative">
                                    <select 
                                        x-model="toCurrency"
                                        class="w-full appearance-none rounded-xl border border-slate-200 bg-white px-4 py-3 pr-10 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#0361fc] focus:border-transparent transition cursor-pointer"
                                    >
                                        <option value="IDR">IDR - Rupiah Indonesia</option>
                                        <template x-for="r in Object.values(rates)" :key="r.code">
                                            <option :value="r.code" x-text="`${r.code} - ${r.name}`"></option>
                                        </template>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Result Output -->
                            <div class="md:col-span-11 mt-4 pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Hasil Konversi</span>
                                    <p class="text-3xl font-black text-[#0361fc] tracking-tight break-all" x-text="formatResult(convert())"></p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nilai Tukar Referensi</p>
                                    <p class="text-sm font-bold text-slate-950 mt-1">
                                        1 <span class="text-[#0361fc]" x-text="fromCurrency"></span> = 
                                        <span class="text-[#0361fc]" x-text="formatRateSingle()"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom: Exchange Rates List Table (Full width) -->
                    <div class="bg-white rounded-3xl shadow-[0_24px_60px_rgba(0,0,0,0.18)] ring-1 ring-slate-200/60 p-6 md:p-8" x-data="{ search: '' }">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                            <div>
                                <h2 class="text-xl font-extrabold text-slate-900">Nilai Tukar Rupiah</h2>
                                <p class="text-xs text-slate-400 mt-1 font-medium">Diupdate otomatis dari backend server</p>
                            </div>
                            
                            <!-- Search filter input -->
                            <div class="relative w-full sm:max-w-xs">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input 
                                    type="text" 
                                    x-model="search" 
                                    placeholder="Cari mata uang..." 
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#0361fc] focus:border-transparent transition"
                                >
                            </div>
                        </div>

                        <!-- Rates Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                        <th class="pb-3 pl-2">Mata Uang</th>
                                        <th class="pb-3 text-right">Nilai dalam Rupiah (IDR)</th>
                                        <th class="pb-3 text-right pr-2">Rupiah ke Valas</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 text-sm font-medium">
                                    @php
                                        $flags = [
                                            'USD' => '🇺🇸', 'SGD' => '🇸🇬', 'MYR' => '🇲🇾',
                                            'EUR' => '🇪🇺', 'AUD' => '🇦🇺', 'GBP' => '🇬🇧',
                                            'JPY' => '🇯🇵', 'CNY' => '🇨🇳', 'SAR' => '🇸🇦',
                                            'KRW' => '🇰🇷', 'THB' => '🇹🇭'
                                        ];
                                    @endphp
                                    @foreach ($exchangeRates as $rate)
                                    <tr 
                                        x-show="'{{ strtolower($rate['code']) }}'.includes(search.toLowerCase()) || '{{ strtolower($rate['name']) }}'.includes(search.toLowerCase())"
                                        class="hover:bg-[#EDF4FF]/30 transition duration-150"
                                    >
                                        <td class="py-4 pl-2 flex items-center gap-3">
                                            <span class="text-2xl" role="img" aria-label="{{ $rate['code'] }} Flag">
                                                {{ $flags[$rate['code']] ?? '🏳️' }}
                                            </span>
                                            <div>
                                                <p class="font-extrabold text-slate-900 leading-none">{{ $rate['code'] }}</p>
                                                <p class="text-[11px] text-slate-400 mt-1 font-semibold">{{ $rate['name'] }}</p>
                                            </div>
                                        </td>
                                        <td class="py-4 text-right font-bold text-slate-900">
                                            Rp {{ number_format($rate['rate_to_idr'], 2, ',', '.') }}
                                        </td>
                                        <td class="py-4 text-right text-slate-500 font-semibold pr-2">
                                            @if($rate['idr_to_currency'] < 0.001)
                                                {{ number_format($rate['idr_to_currency'], 6, '.', ',') }}
                                            @else
                                                {{ number_format($rate['idr_to_currency'], 4, '.', ',') }}
                                            @endif
                                            {{ $rate['code'] }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Update Timestamp Footer -->
                        <div class="mt-6 border-t border-slate-100 pt-4 flex items-center justify-between text-xs text-slate-400 font-medium">
                            <span class="flex items-center gap-1.5 font-bold text-emerald-500">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Terkoneksi Real-time
                            </span>
                            <span>
                                Terakhir diperbarui: {{ date('d F Y, H:i T', $lastUpdated) }}
                            </span>
                        </div>
                    </div>

            <!-- Info / Disclaimer Strip Section -->
            <div class="container mx-auto px-4 mt-16 max-w-4xl pb-16">
                <div class="bg-blue-50/50 border border-blue-100/50 rounded-3xl p-6 flex flex-col md:flex-row items-center gap-4 text-slate-500">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#EDF4FF] text-[#0361fc]">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">Pemberitahuan Penting</h4>
                        <p class="text-xs leading-relaxed font-medium">
                            Nilai tukar mata uang asing yang ditampilkan hanya berfungsi sebagai acuan/indikasi referensi perjalanan dan administrasi visa. Transaksi riil dengan pihak bank atau merchant pembayaran mungkin menggunakan kurs yang sedikit berbeda pada waktu yang sama.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <x-home.footer />

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function currencyConverter() {
            return {
                amount: 1,
                fromCurrency: 'USD',
                toCurrency: 'IDR',
                rates: {},

                init() {
                    const rawRates = @json($exchangeRates);
                    this.rates = JSON.parse(JSON.stringify(rawRates));
                    
                    // Inject IDR as base
                    this.rates['IDR'] = {
                        code: 'IDR',
                        name: 'Rupiah Indonesia',
                        rate_to_idr: 1,
                        idr_to_currency: 1
                    };
                },

                convert() {
                    if (!this.amount || isNaN(this.amount)) return 0;
                    
                    const rateFrom = this.rates[this.fromCurrency]?.rate_to_idr || 1;
                    const rateTo = this.rates[this.toCurrency]?.rate_to_idr || 1;
                    
                    return this.amount * (rateFrom / rateTo);
                },

                swap() {
                    const temp = this.fromCurrency;
                    this.fromCurrency = this.toCurrency;
                    this.toCurrency = temp;
                },

                formatResult(value) {
                    if (this.toCurrency === 'IDR') {
                        return new Intl.NumberFormat('id-ID', { 
                            style: 'currency', 
                            currency: 'IDR', 
                            minimumFractionDigits: 0, 
                            maximumFractionDigits: 2 
                        }).format(value);
                    }
                    return new Intl.NumberFormat('id-ID', { 
                        minimumFractionDigits: 2, 
                        maximumFractionDigits: 4 
                    }).format(value) + ' ' + this.toCurrency;
                },

                formatRateSingle() {
                    const rateFrom = this.rates[this.fromCurrency]?.rate_to_idr || 1;
                    const rateTo = this.rates[this.toCurrency]?.rate_to_idr || 1;
                    const single = rateFrom / rateTo;

                    if (this.toCurrency === 'IDR') {
                        return new Intl.NumberFormat('id-ID', { 
                            style: 'currency', 
                            currency: 'IDR', 
                            minimumFractionDigits: 0, 
                            maximumFractionDigits: 2 
                        }).format(single);
                    }
                    
                    if (single < 0.001) {
                        return single.toFixed(6) + ' ' + this.toCurrency;
                    }
                    return single.toFixed(4) + ' ' + this.toCurrency;
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>