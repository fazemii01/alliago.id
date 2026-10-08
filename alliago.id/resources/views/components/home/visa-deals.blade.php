@props(['featuredProducts' => collect(), 'myrRate' => 3450.0])

{{-- 
    Visa Deals & Services Section Component
    Replaces static flight deals with dynamic, official Visa Assistance products.
    Strictly follows alliago.pen design tokens:
    - Outfit Typography
    - Palette: Deep Navy (#0A2540, #00275A, #001D44), White, Neutral Slate (#64748B), Brand Orange (#FE6A00)
    - Card Geometry: rounded-[20px] - rounded-[24px]
    - Direct linking to route('visa.show', $product->slug)
    - Lucide SVG icons (Zero emojis)
--}}
<section id="visa-services" class="scroll-mt-28 box-border w-full shrink-0 flex flex-col gap-[20px] p-[36px_16px_28px_16px] lg:p-[40px_80px_32px_80px] justify-start items-center relative z-10 font-['Outfit',sans-serif] bg-[#F8FAFC]">
    <div class="box-border w-full max-w-[1280px] flex flex-col gap-[22px] justify-start items-start"
         x-data="{ activeCategory: 'all' }">
        
        {{-- Section Header Row --}}
        <div class="box-border w-full flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="flex flex-col gap-[6px] justify-start items-start">
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 border border-blue-200/80 px-3.5 py-1 text-xs font-bold text-[#00275A]">
                    <svg class="h-4 w-4 text-[#FE6A00]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Layanan Asistensi Visa Resmi</span>
                </div>
                <h2 class="text-[22px] sm:text-[28px] font-bold text-[#0A2540] tracking-tight leading-tight">
                    Destinasi Populer &amp; Layanan Visa Terpercaya
                </h2>
                <p class="text-[13px] sm:text-[14px] text-[#64748B] font-normal max-w-2xl leading-relaxed">
                    Pilihan pengurusan visa 50+ negara untuk liburan, perjalanan bisnis, atau studi. Bimbingan berkas akurat, tingkat persetujuan tinggi, dan opsi pembayaran IDR &amp; RM.
                </p>
            </div>
            
            <div class="shrink-0">
                <a href="{{ route('visa.index') }}" 
                   class="inline-flex items-center gap-2 text-[13px] sm:text-[14px] font-bold text-[#FE6A00] hover:text-[#E05D00] transition-colors group">
                    <span>Lihat Semua 50+ Negara</span>
                    <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Destination Category Filter Pills --}}
        <div class="flex flex-wrap items-center gap-2 pt-1 pb-1">
            <button type="button" 
                    @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-[#00275A] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-1.5 rounded-full text-[12px] font-bold transition-all duration-200">
                Semua Destinasi
            </button>
            <button type="button" 
                    @click="activeCategory = 'asia'"
                    :class="activeCategory === 'asia' ? 'bg-[#00275A] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-1.5 rounded-full text-[12px] font-bold transition-all duration-200">
                Asia Favorit
            </button>
            <button type="button" 
                    @click="activeCategory = 'oceania'"
                    :class="activeCategory === 'oceania' ? 'bg-[#00275A] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-1.5 rounded-full text-[12px] font-bold transition-all duration-200">
                Australia &amp; Oseania
            </button>
            <button type="button" 
                    @click="activeCategory = 'europe'"
                    :class="activeCategory === 'europe' ? 'bg-[#00275A] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-1.5 rounded-full text-[12px] font-bold transition-all duration-200">
                Eropa &amp; Schengen
            </button>
            <button type="button" 
                    @click="activeCategory = 'americas'"
                    :class="activeCategory === 'americas' ? 'bg-[#00275A] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-1.5 rounded-full text-[12px] font-bold transition-all duration-200">
                Amerika &amp; Global
            </button>
        </div>

        {{-- Visa Product Cards Responsive Grid (4 columns on xl, 3 on lg, 2 on sm, 1 on mobile) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[20px] w-full">
            @forelse($featuredProducts as $product)
                @php
                    $countryName = $product->country->name ?? 'Internasional';
                    $hasDiscount = $product->discount_price && $product->discount_price < $product->base_price;
                    $displayPrice = $hasDiscount ? $product->discount_price : $product->base_price;
                    $approxRm = ($myrRate > 0) ? round($displayPrice / $myrRate) : 0;
                    $imageUrl = $product->icon_url;

                    // Category grouping helper
                    $countryLower = strtolower($countryName . ' ' . $product->name);
                    $cardCategory = 'americas';
                    if (str_contains($countryLower, 'jepang') || str_contains($countryLower, 'japan') || str_contains($countryLower, 'korea') || str_contains($countryLower, 'china') || str_contains($countryLower, 'taiwan') || str_contains($countryLower, 'singapore') || str_contains($countryLower, 'singapura') || str_contains($countryLower, 'thailand') || str_contains($countryLower, 'vietnam') || str_contains($countryLower, 'asia')) {
                        $cardCategory = 'asia';
                    } elseif (str_contains($countryLower, 'australia') || str_contains($countryLower, 'new zealand') || str_contains($countryLower, 'selandia baru')) {
                        $cardCategory = 'oceania';
                    } elseif (str_contains($countryLower, 'schengen') || str_contains($countryLower, 'eropa') || str_contains($countryLower, 'europe') || str_contains($countryLower, 'united kingdom') || str_contains($countryLower, 'inggris') || str_contains($countryLower, 'belanda') || str_contains($countryLower, 'netherlands') || str_contains($countryLower, 'prancis') || str_contains($countryLower, 'france') || str_contains($countryLower, 'jerman') || str_contains($countryLower, 'germany') || str_contains($countryLower, 'italy') || str_contains($countryLower, 'spain')) {
                        $cardCategory = 'europe';
                    }
                @endphp

                <a href="{{ route('visa.show', $product->slug) }}"
                   x-show="activeCategory === 'all' || activeCategory === '{{ $cardCategory }}'"
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="opacity-0 scale-95"
                   x-transition:enter-end="opacity-100 scale-100"
                   class="group relative flex flex-col justify-between overflow-hidden rounded-[20px] border border-slate-200/90 bg-white shadow-[0px_4px_12px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0px_16px_32px_rgba(0,39,90,0.12)] hover:border-blue-200 block text-left">
                    
                    <div>
                        {{-- Destination Cover Header (Height 175px) --}}
                        <div class="relative h-[175px] w-full overflow-hidden bg-slate-100">
                            <img 
                                src="{{ $imageUrl }}" 
                                alt="{{ $product->name }}" 
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                loading="lazy"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1488085061387-422e29b40080?auto=format&fit=crop&w=600&q=80';"
                            >
                            {{-- Ambient gradient scrims for badge contrast --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-black/30"></div>

                            {{-- Floating Country Badge (Top Left) --}}
                            <div class="absolute top-3 left-3 flex items-center gap-1.5 rounded-full bg-white/95 backdrop-blur-md px-3 py-1 text-xs font-bold text-slate-800 shadow-sm">
                                <svg class="h-3.5 w-3.5 text-[#00275A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $countryName }}</span>
                            </div>

                            {{-- Floating Processing Time Badge (Top Right) --}}
                            <div class="absolute top-3 right-3 flex items-center gap-1 rounded-full bg-[#00275A]/90 backdrop-blur-md px-2.5 py-1 text-[11px] font-bold text-blue-100 shadow-sm">
                                <svg class="h-3 w-3 text-[#FE6A00]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $product->processing_time ?: '3–5 Hari Kerja' }}</span>
                            </div>

                            {{-- Floating Tag (Bottom Left): Promo or Visa Type --}}
                            <div class="absolute bottom-3 left-3 flex items-center gap-1.5">
                                @if($hasDiscount)
                                    <span class="rounded-md bg-[#FE6A00] px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-white shadow-md">
                                        PROMO SPESIAL
                                    </span>
                                @elseif($product->promo_label)
                                    <span class="rounded-md bg-blue-600 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-white shadow-md">
                                        {{ $product->promo_label }}
                                    </span>
                                @else
                                    <span class="rounded-md bg-white/90 backdrop-blur-xs px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[#00275A] shadow-xs">
                                        {{ $product->type ?: 'TOURIST VISA' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-[16px_18px_12px_18px] flex flex-col justify-start">
                            <h3 class="text-[17px] font-bold text-[#0A2540] group-hover:text-[#FE6A00] transition-colors leading-snug line-clamp-1">
                                {{ $product->name }}
                            </h3>

                            <p class="mt-1 text-[12px] text-[#64748B] line-clamp-2 leading-relaxed">
                                {{ $product->short_description ?: 'Bantuan pengurusan dokumen, pendaftaran janji temu, dan asistensi resmi kedutaan sampai visa terbit.' }}
                            </p>

                            {{-- Validity / Stay Duration Metadata --}}
                            <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px] text-slate-500">
                                @if($product->stay_duration)
                                    <div class="flex items-center gap-1 bg-slate-50 border border-slate-100 rounded-md px-2 py-0.5">
                                        <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Stay: {{ $product->stay_duration }}</span>
                                    </div>
                                @endif
                                @if($product->validity)
                                    <div class="flex items-center gap-1 bg-slate-50 border border-slate-100 rounded-md px-2 py-0.5">
                                        <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Berlaku: {{ $product->validity }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Card Footer & Price Row --}}
                    <div class="border-t border-slate-100 p-[12px_18px_16px_18px] bg-[#FAFBFD] mt-2">
                        <div class="flex items-end justify-between gap-2">
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    Mulai dari
                                </span>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span class="text-[17px] sm:text-[18px] font-black text-[#FE6A00]">
                                        IDR {{ number_format($displayPrice, 0, ',', '.') }}
                                    </span>
                                    @if($hasDiscount)
                                        <span class="text-[10.5px] text-slate-400 line-through">
                                            {{ number_format($product->base_price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                @if($approxRm > 0)
                                    <div class="mt-1">
                                        <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 border border-blue-200/60 px-1.5 py-0.5 text-[10px] font-bold text-[#00275A]">
                                            <span>~ RM {{ number_format($approxRm, 0) }}</span>
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <span class="inline-flex items-center gap-1.5 rounded-[12px] bg-[#00275A] group-hover:bg-[#FE6A00] text-white px-3 py-2 text-[11.5px] font-bold transition-all duration-200 shadow-sm shrink-0">
                                <span>Lihat Detail</span>
                                <svg class="h-3.5 w-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-[20px] border border-dashed border-slate-200">
                    <svg class="h-10 w-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="font-medium text-sm text-slate-500">Layanan visa sedang dipersiapkan.</p>
                    <a href="{{ route('visa.index') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-[#FE6A00] hover:underline">
                        Buka Katalog Visa
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</section>
