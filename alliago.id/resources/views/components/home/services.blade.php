@props(['featuredProducts' => collect(), 'myrRate' => 3450.0])

<section id="services" class="py-14 px-4 sm:px-6">
    <div class="mx-auto max-w-7xl">
        {{-- Section Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 border border-blue-200/80 px-3.5 py-1 text-xs font-bold text-[#00275A] mb-3">
                    <svg class="h-4 w-4 text-[#FE6A00]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Layanan Asistensi Visa Resmi</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Destinasi Populer & Layanan Visa Terpercaya
                </h2>
                <p class="mt-2 text-xs sm:text-sm text-slate-500 max-w-2xl">
                    Pilih produk visa untuk kebutuhan liburan, perjalanan bisnis, atau studi. Bimbingan berkas akurat dengan persetujuan tinggi.
                </p>
            </div>

            <div class="mt-4 sm:mt-0">
                <a href="{{ route('visa.index') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-[#FE6A00] hover:text-[#E05D00] transition-colors group">
                    <span>Lihat Semua 50+ Negara</span>
                    <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Dynamic Visa Product Cards Grid with Prominent Cover Images --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredProducts as $product)
                @php
                    $countryName = $product->country->name ?? 'Internasional';
                    $hasDiscount = $product->discount_price && $product->discount_price < $product->base_price;
                    $displayPrice = $hasDiscount ? $product->discount_price : $product->base_price;
                    $approxRm = ($myrRate > 0) ? round($displayPrice / $myrRate) : 0;
                    $imageUrl = $product->icon_url;
                @endphp

                <div class="group flex flex-col justify-between overflow-hidden rounded-[20px] border border-slate-200/90 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-blue-200">
                    <div>
                        {{-- Prominent Visa Cover Image Header (Height ~190px) --}}
                        <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                            <img 
                                src="{{ $imageUrl }}" 
                                alt="{{ $product->name }}" 
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                loading="lazy"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1488085061387-422e29b40080?auto=format&fit=crop&w=600&q=80';"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/10"></div>

                            {{-- Floating Country Badge --}}
                            <div class="absolute top-3 left-3 flex items-center gap-1.5 rounded-full bg-white/95 backdrop-blur-md px-3 py-1 text-xs font-extrabold text-slate-800 shadow-sm">
                                <svg class="h-3.5 w-3.5 text-[#00275A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $countryName }}</span>
                            </div>

                            {{-- Floating Processing Time Badge --}}
                            <div class="absolute top-3 right-3 flex items-center gap-1 rounded-full bg-[#00275A]/90 backdrop-blur-md px-2.5 py-1 text-[11px] font-bold text-blue-100 shadow-sm">
                                <svg class="h-3 w-3 text-[#FE6A00]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $product->processing_time ?: '3-5 Hari Kerja' }}</span>
                            </div>

                            @if($hasDiscount)
                                <div class="absolute bottom-3 left-3 rounded-lg bg-red-600 px-2 py-0.5 text-[10px] font-black uppercase text-white shadow-md">
                                    Promo Diskon
                                </div>
                            @endif
                        </div>

                        {{-- Card Body --}}
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-blue-700">
                                    {{ $product->type ?: 'Tourist Visa' }}
                                </span>
                                @if($product->validity)
                                    <span class="text-[11px] text-slate-400 font-medium">
                                        Masa berlaku: {{ $product->validity }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-lg font-extrabold text-slate-900 group-hover:text-[#00275A] transition-colors leading-snug">
                                {{ $product->name }}
                            </h3>

                            <p class="mt-2 text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $product->short_description ?: 'Bantuan pengurusan dokumen, pendaftaran janji temu, dan asistensi resmi kedutaan sampai visa terbit.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Card Footer & Price Row --}}
                    <div class="border-t border-slate-100 p-5 pt-4 bg-slate-50/50">
                        <div class="flex items-end justify-between">
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    Mulai dari
                                </span>
                                <div class="flex items-baseline gap-1.5 mt-0.5">
                                    <span class="text-lg font-black text-[#FE6A00]">
                                        IDR {{ number_format($displayPrice, 0, ',', '.') }}
                                    </span>
                                    @if($hasDiscount)
                                        <span class="text-[11px] text-slate-400 line-through">
                                            {{ number_format($product->base_price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                @if($approxRm > 0)
                                    <div class="mt-1">
                                        <span class="inline-flex items-center gap-1 rounded-md bg-blue-100/70 px-1.5 py-0.5 text-[10px] font-bold text-blue-900">
                                            <span>~ RM {{ number_format($approxRm, 0) }}</span>
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('visa.show', $product->slug) }}" style="background-color: #00275A !important; color: #ffffff !important;" class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2.5 text-xs font-bold shadow-md shadow-blue-950/20 hover:opacity-95 transition-opacity">
                                <span>Lihat Detail</span>
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-400">
                    <p class="font-medium text-sm">Belum ada produk visa yang ditampilkan saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

