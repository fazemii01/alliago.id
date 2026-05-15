<x-layouts.app title="Tiket pesawat | Alliago.id">
    <x-home.header />

    @php
        $routeLabel = data_get(collect($cities)->firstWhere('id', $filters['origin']), 'name', $filters['origin']);
        $destinationLabel = data_get(collect($cities)->firstWhere('id', $filters['destination']), 'name', $filters['destination']);
        $totalPassenger = (int) $filters['adult'] + (int) $filters['child'] + (int) $filters['infant'];
    @endphp

    <div class="min-h-screen bg-[#F8F9FB] pt-24 pb-16 text-slate-900">
        <div class="page-wrapper space-y-10">
            <section class="relative overflow-hidden rounded-[36px] bg-slate-950 shadow-[0_30px_80px_rgba(15,23,42,0.18)]">
                <img
                    src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=2070&auto=format&fit=crop"
                    alt="Flight hero"
                    class="absolute inset-0 h-full w-full object-cover object-center"
                >
                <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(2,6,23,0.88)_0%,rgba(2,6,23,0.62)_45%,rgba(2,6,23,0.35)_100%)]"></div>
                <div class="relative grid gap-10 px-6 py-10 sm:px-8 sm:py-12 lg:grid-cols-[minmax(0,1fr)_340px] lg:px-12 lg:py-14">
                    <div class="flex min-h-[260px] flex-col justify-end">
                        <div class="max-w-3xl">
                            <p class="text-sm font-bold uppercase tracking-[0.32em] text-white/70">Tiket pesawat</p>
                            <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">Cek estimasi harga tiket dengan tampilan lebih rapi dan cepat dipakai.</h1>
                            <p class="mt-4 max-w-2xl text-sm font-medium leading-7 text-white/80 sm:text-base">Pilih rute, tanggal, dan jumlah penumpang untuk melihat harga final yang akan kami tawarkan tanpa lanjut ke booking atau payment.</p>
                        </div>
                    </div>
                    <div class="hidden rounded-[28px] border border-white/15 bg-white/10 p-5 backdrop-blur md:block">
                        <div class="space-y-4 text-white">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-white/55">Ringkas</p>
                                <p class="mt-2 text-2xl font-bold">Cari tiket lebih cepat</p>
                            </div>
                            <div class="grid gap-3 text-sm text-white/80">
                                <div class="rounded-2xl bg-white/10 px-4 py-3">Route, tanggal, trip, dan penumpang dalam satu flow.</div>
                                <div class="rounded-2xl bg-white/10 px-4 py-3">Tanggal pergi dan pulang lebih jelas dibaca.</div>
                                <div class="rounded-2xl bg-white/10 px-4 py-3">Passenger picker lebih ringan dan mobile-friendly.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="relative -mt-8 sm:-mt-12 lg:px-8">
                <form method="POST" action="{{ route('flights.index') }}" class="rounded-[32px] bg-white p-4 shadow-[0_30px_60px_rgba(15,23,42,0.08)] ring-1 ring-slate-200/80 sm:p-5 lg:p-6" x-data="flightSearchForm()">
                    @csrf
                    <div class="flex flex-col gap-5">
                        <div class="flex flex-col gap-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Form pencarian</p>
                            <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">Atur pencarian tiket</h2>
                        </div>

                        <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-[1.2fr_0.9fr_0.9fr_0.75fr]">
                            <label class="block rounded-2xl border border-slate-200 px-4 py-4 transition hover:border-slate-300 focus-within:border-[#0361fc] focus-within:ring-4 focus-within:ring-[#0361fc]/10">
                                <span class="block text-[11px] font-semibold uppercase tracking-wide text-slate-400">Rute</span>
                                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                    <select name="origin" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#0361fc] focus:ring-0">
                                        <option value="">Asal</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city['id'] }}" @selected($filters['origin'] === $city['id'])>{{ $city['label'] }}</option>
                                        @endforeach
                                    </select>
                                    <select name="destination" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#0361fc] focus:ring-0">
                                        <option value="">Tujuan</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city['id'] }}" @selected($filters['destination'] === $city['id'])>{{ $city['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </label>

                            <label class="block rounded-2xl border border-slate-200 px-4 py-4 transition hover:border-slate-300 focus-within:border-[#0361fc] focus-within:ring-4 focus-within:ring-[#0361fc]/10">
                                <span class="block text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tanggal pergi</span>
                                <div class="relative mt-3">
                                    <input x-ref="departDate" type="text" name="depart_date" value="{{ $filters['depart_date'] }}" placeholder="Pilih tanggal pergi" class="h-12 w-full cursor-pointer rounded-xl border border-slate-200 bg-slate-50 px-4 pr-12 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#0361fc] focus:ring-0">
                                    <svg class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-13 9h16a1 1 0 0 0 1-1V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a1 1 0 0 0 1 1Z" /></svg>
                                </div>
                            </label>

                            <label class="block rounded-2xl border border-slate-200 px-4 py-4 transition hover:border-slate-300 focus-within:border-[#0361fc] focus-within:ring-4 focus-within:ring-[#0361fc]/10" :class="tripType !== 'R' ? 'border-dashed bg-slate-50/70 opacity-70' : ''">
                                <span class="block text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tanggal pulang</span>
                                <div class="relative mt-3">
                                    <input x-ref="returnDate" type="text" name="return_date" value="{{ $filters['return_date'] }}" placeholder="Aktif untuk round trip" class="h-12 w-full cursor-pointer rounded-xl border border-slate-200 bg-slate-50 px-4 pr-12 text-sm font-semibold text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-0" :disabled="tripType !== 'R'">
                                    <svg class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2" :class="tripType !== 'R' ? 'text-slate-300' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-13 9h16a1 1 0 0 0 1-1V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a1 1 0 0 0 1 1Z" /></svg>
                                </div>
                            </label>

                            <label class="block rounded-2xl border border-slate-200 px-4 py-4 transition hover:border-slate-300 focus-within:border-[#0361fc] focus-within:ring-4 focus-within:ring-[#0361fc]/10">
                                <span class="block text-[11px] font-semibold uppercase tracking-wide text-slate-400">Jenis trip</span>
                                <select name="trip_type" id="tripTypeField" x-model="tripType" class="mt-3 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#0361fc] focus:ring-0">
                                    @foreach ($tripTypes as $value => $label)
                                        <option value="{{ $value }}" @selected($filters['trip_type'] === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_220px] xl:items-end">
                            <div class="rounded-2xl border border-slate-200 px-4 py-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Penumpang</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-500">Atur jumlah penumpang tanpa bikin form terasa berat.</p>
                                    </div>
                                    <span class="w-fit rounded-full bg-[#EDF4FF] px-3 py-1 text-xs font-bold text-[#0361fc]" x-text="`${totalPassenger} penumpang`"></span>
                                </div>
                                <div class="mt-4 grid gap-3 md:grid-cols-3">
                                    <div class="rounded-2xl bg-slate-50 px-3 py-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-slate-900">Adult</p>
                                                <p class="truncate text-xs text-slate-400">12+ tahun</p>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" @click="decrement('adult', 1)" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-lg font-bold leading-none text-slate-500 transition hover:border-[#0361fc] hover:text-[#0361fc]">−</button>
                                                <input x-model="adult" type="number" name="adult" min="1" max="9" class="w-9 border-none bg-transparent p-0 text-center text-sm font-bold text-slate-900 outline-none">
                                                <button type="button" @click="increment('adult')" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-lg font-bold leading-none text-slate-500 transition hover:border-[#0361fc] hover:text-[#0361fc]">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 px-3 py-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-slate-900">Child</p>
                                                <p class="truncate text-xs text-slate-400">2–11 tahun</p>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" @click="decrement('child')" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-lg font-bold leading-none text-slate-500 transition hover:border-[#0361fc] hover:text-[#0361fc]">−</button>
                                                <input x-model="child" type="number" name="child" min="0" max="9" class="w-9 border-none bg-transparent p-0 text-center text-sm font-bold text-slate-900 outline-none">
                                                <button type="button" @click="increment('child')" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-lg font-bold leading-none text-slate-500 transition hover:border-[#0361fc] hover:text-[#0361fc]">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 px-3 py-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-slate-900">Infant</p>
                                                <p class="truncate text-xs text-slate-400">&lt; 2 tahun</p>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" @click="decrement('infant')" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-lg font-bold leading-none text-slate-500 transition hover:border-[#0361fc] hover:text-[#0361fc]">−</button>
                                                <input x-model="infant" type="number" name="infant" min="0" max="9" class="w-9 border-none bg-transparent p-0 text-center text-sm font-bold text-slate-900 outline-none">
                                                <button type="button" @click="increment('infant')" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-lg font-bold leading-none text-slate-500 transition hover:border-[#0361fc] hover:text-[#0361fc]">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="flex h-14 w-full items-center justify-center rounded-2xl bg-slate-950 px-6 text-sm font-bold text-white transition hover:bg-slate-800 xl:h-[72px]">
                                Cari tiket
                            </button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if ($error)
                        <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
                            {{ $error }}
                        </div>
                    @endif
                </form>
            </div>

            <div class="grid gap-8 lg:grid-cols-4">
                <aside class="space-y-6 lg:col-span-1">
                    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                        <h2 class="text-lg font-bold text-slate-900">Ringkasan pencarian</h2>
                        <dl class="mt-5 space-y-4 text-sm">
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-medium text-slate-500">Rute</dt>
                                <dd class="text-right font-bold text-slate-900">{{ $routeLabel && $destinationLabel ? $routeLabel . ' - ' . $destinationLabel : 'Pilih rute' }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-medium text-slate-500">Tanggal</dt>
                                <dd class="text-right font-bold text-slate-900">{{ $filters['depart_date'] ? \Illuminate\Support\Carbon::parse($filters['depart_date'])->translatedFormat('d M Y') : '-' }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-medium text-slate-500">Trip</dt>
                                <dd class="text-right font-bold text-slate-900">{{ $tripTypes[$filters['trip_type']] ?? 'One Way' }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-medium text-slate-500">Penumpang</dt>
                                <dd class="text-right font-bold text-slate-900">{{ $totalPassenger }} orang</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                        <h2 class="text-lg font-bold text-slate-900">Catatan</h2>
                        <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-500">
                            <li>Harga tampil setelah cek jadwal dan price H2H.</li>
                            <li>Halaman ini hanya untuk estimasi dan penawaran harga tiket.</li>
                            <li>Tidak lanjut ke booking, issued, atau payment.</li>
                        </ul>
                    </div>
                </aside>

                <section class="lg:col-span-3">
                    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-400">Hasil pencarian</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $routeLabel && $destinationLabel ? 'Flight from ' . $routeLabel . ' to ' . $destinationLabel : 'Pilih rute dan tanggal' }}</h2>
                        </div>
                        <div class="inline-flex rounded-2xl border border-slate-200 bg-white p-1 text-sm font-semibold text-slate-500 shadow-sm">
                            <span class="rounded-xl bg-slate-950 px-4 py-2 text-white">Cheapest</span>
                            <span class="px-4 py-2">Final fare</span>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @forelse ($results as $flight)
                            <article class="rounded-[32px] bg-white p-6 shadow-sm ring-1 ring-slate-200/80 transition hover:-translate-y-0.5 hover:shadow-md lg:p-8">
                                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                                    <div class="space-y-1">
                                        <p class="text-xl font-bold text-slate-900">{{ $flight['airline'] }}</p>
                                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">{{ $flight['flight_numbers'] ?: 'Flight info pending' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-slate-900">{{ $flight['duration'] }}</p>
                                        <p class="text-sm font-medium text-slate-400">{{ $flight['stops'] }}</p>
                                    </div>
                                    <div class="flex items-center justify-between gap-6 md:justify-end">
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-slate-900">{{ $flight['price'] }}</p>
                                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Final offer</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-2xl border border-[#0361fc] px-5 py-2.5 text-sm font-bold text-[#0361fc]">Price checked</span>
                                    </div>
                                </div>

                                <div class="my-8 flex items-center justify-between gap-4">
                                    <div class="min-w-[96px]">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Start</p>
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-lg font-bold text-slate-900">{{ $flight['start_time'] }}</span>
                                            <span class="border-l border-slate-300 pl-2 text-sm text-slate-400">{{ $flight['start_location'] }}</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-1 items-center justify-center px-4 lg:px-10">
                                        <div class="h-2 w-2 rounded-full bg-slate-300"></div>
                                        <div class="flex-1 border-b-2 border-dashed border-slate-200"></div>
                                        <svg class="mx-3 h-6 w-6 rotate-45 text-[#0361fc]" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5L21 16Z" /></svg>
                                        <div class="flex-1 border-b-2 border-dashed border-slate-200"></div>
                                        <div class="h-2 w-2 rounded-full bg-slate-300"></div>
                                    </div>

                                    <div class="min-w-[96px] text-right">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">End</p>
                                        <div class="mt-2 flex items-center justify-end gap-2">
                                            <span class="text-lg font-bold text-slate-900">{{ $flight['end_time'] }}</span>
                                            <span class="border-l border-slate-300 pl-2 text-sm text-slate-400">{{ $flight['end_location'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if ($flight['info'])
                                    <div class="rounded-2xl bg-[#FFF4F4] px-4 py-3 text-xs font-bold text-slate-700">
                                        {{ $flight['info'] }}
                                    </div>
                                @endif

                                <div class="mt-6 grid gap-4 border-t border-dashed border-slate-200 pt-5 md:grid-cols-3">
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Cabin class</p>
                                        <p class="mt-2 text-sm font-bold text-slate-900">{{ $flight['class'] ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Passport required</p>
                                        <p class="mt-2 text-sm font-bold text-slate-900">{{ $flight['passport_required'] ? 'Yes' : 'No' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Fare breakdown</p>
                                        <div class="mt-2 space-y-1 text-sm text-slate-600">
                                            @forelse ($flight['fare_breakdown'] as $fare)
                                                <p><span class="font-semibold text-slate-900">{{ $fare['pax_type'] }}:</span> {{ $fare['total_fare'] }}</p>
                                            @empty
                                                <p>-</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-[32px] border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                                <h3 class="text-xl font-bold text-slate-900">Belum ada hasil tiket.</h3>
                                <p class="mt-3 text-sm text-slate-500">Isi rute, tanggal, dan jumlah penumpang lalu tekan cari tiket untuk cek harga.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>

    <x-home.footer />

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <style>
            .flatpickr-calendar {
                width: 320px;
                border-radius: 1.5rem;
                border: 1px solid #e2e8f0;
                box-shadow: 0 24px 50px rgba(15, 23, 42, 0.16);
                padding: 14px;
                font-family: inherit;
            }
            .flatpickr-months .flatpickr-month,
            .flatpickr-current-month .flatpickr-monthDropdown-months,
            .flatpickr-current-month input.cur-year {
                font-weight: 700;
                color: #0f172a;
            }
            .flatpickr-weekdays {
                margin-top: 8px;
            }
            .flatpickr-weekday {
                color: #94a3b8;
                font-weight: 700;
            }
            .flatpickr-days,
            .dayContainer {
                width: 100%;
                min-width: 100%;
                max-width: 100%;
            }
            .flatpickr-day,
            .flatpickr-weekday {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .flatpickr-day {
                width: 38px;
                max-width: 38px;
                height: 38px;
                line-height: 38px;
                border-radius: 0.75rem;
                font-weight: 600;
                color: #334155;
                margin: 0;
            }
            .flatpickr-day.today {
                border-color: #0361fc;
                color: #0361fc;
            }
            .flatpickr-day.selected,
            .flatpickr-day.startRange,
            .flatpickr-day.endRange,
            .flatpickr-day.selected.inRange,
            .flatpickr-day.startRange.inRange,
            .flatpickr-day.endRange.inRange,
            .flatpickr-day.selected:hover,
            .flatpickr-day.startRange:hover,
            .flatpickr-day.endRange:hover {
                background: #0361fc;
                border-color: #0361fc;
                color: #fff;
            }
            .flatpickr-day.inRange {
                background: #edf4ff;
                border-color: #edf4ff;
                color: #0361fc;
                box-shadow: none;
            }
            .flatpickr-day.prevMonthDay,
            .flatpickr-day.nextMonthDay {
                color: #cbd5e1;
            }
            .flatpickr-day.flatpickr-disabled,
            .flatpickr-day.flatpickr-disabled:hover {
                color: #cbd5e1;
            }
        </style>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('flightSearchForm', () => ({
                    tripType: '{{ $filters['trip_type'] }}',
                    adult: {{ (int) $filters['adult'] }},
                    child: {{ (int) $filters['child'] }},
                    infant: {{ (int) $filters['infant'] }},
                    departPicker: null,
                    returnPicker: null,
                    get totalPassenger() {
                        return Number(this.adult) + Number(this.child) + Number(this.infant);
                    },
                    init() {
                        const jakartaToday = '{{ now('Asia/Jakarta')->toDateString() }}';

                        this.departPicker = flatpickr(this.$refs.departDate, {
                            dateFormat: 'Y-m-d',
                            minDate: jakartaToday,
                            defaultDate: this.$refs.departDate.value || null,
                            disableMobile: true,
                            onChange: (selectedDates, dateStr) => {
                                if (this.returnPicker) {
                                    this.returnPicker.set('minDate', dateStr || jakartaToday);
                                }
                            },
                        });

                        this.returnPicker = flatpickr(this.$refs.returnDate, {
                            dateFormat: 'Y-m-d',
                            minDate: this.$refs.departDate.value || jakartaToday,
                            defaultDate: this.$refs.returnDate.value || null,
                            disableMobile: true,
                        });

                        this.$watch('tripType', (value) => {
                            if (value !== 'R') {
                                this.$refs.returnDate.value = '';
                                this.returnPicker.clear();
                            }
                        });
                    },
                    increment(field) {
                        if (this.totalPassenger >= 9) {
                            return;
                        }
                        this[field] = Math.min(9, Number(this[field]) + 1);
                    },
                    decrement(field, min = 0) {
                        this[field] = Math.max(min, Number(this[field]) - 1);
                    },
                }));
            });
        </script>
    @endpush
</x-layouts.app>
