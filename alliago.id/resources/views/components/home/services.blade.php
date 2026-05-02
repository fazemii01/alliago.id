@props(['featuredProducts' => collect()])

@php
$flagImages = [
    'JP' => '🇯🇵', 'KR' => '🇰🇷', 'AU' => '🇦🇺', 'CN' => '🇨🇳', 'TW' => '🇹🇼',
    'US' => '🇺🇸', 'GB' => '🇬🇧', 'NL' => '🇳🇱', 'DE' => '🇩🇪', 'FR' => '🇫🇷',
    'SG' => '🇸🇬', 'MY' => '🇲🇾', 'TH' => '🇹🇭', 'IN' => '🇮🇳', 'IT' => '🇮🇹',
];

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

// Collect unique categories / regions from products
$categories = [
    ['label' => 'Trending Visas', 'icon' => '🔥', 'filter' => 'trending'],
    ['label' => '100% Online', 'icon' => '🌐', 'filter' => 'online'],
    ['label' => 'Schengen', 'icon' => '🇪🇺', 'filter' => 'schengen'],
    ['label' => 'Asia', 'icon' => '🌏', 'filter' => 'asia'],
    ['label' => 'Amerika', 'icon' => '🦅', 'filter' => 'amerika'],
    ['label' => 'Timur Tengah', 'icon' => '🕌', 'filter' => 'timteng'],
];
@endphp

<section id="services" class="pt-8 pb-16 px-4 scroll-mt-32 md:scroll-mt-36">
  <div class="container mx-auto">

    {{-- Horizontal scrollable category pills --}}
    <div class="flex gap-3 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-hide mb-6">
      @foreach($categories as $cat)
      <button 
        type="button"
        class="category-pill flex items-center gap-2 px-5 py-3 rounded-full bg-white border border-slate-200 text-sm font-semibold text-slate-700 whitespace-nowrap shadow-sm hover:border-blue-200 hover:text-brand transition-all shrink-0"
      >
        <span class="text-base">{{ $cat['icon'] }}</span>
        {{ $cat['label'] }}
        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>
      @endforeach
    </div>

    {{-- Filter bar --}}
    <div class="flex flex-wrap items-center gap-3 mb-8">
      <button type="button" class="filter-btn flex items-center gap-2 px-4 py-2.5 rounded-full border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:border-blue-200 hover:text-brand transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
        Filter
      </button>
      <button type="button" class="filter-btn flex items-center gap-2 px-4 py-2.5 rounded-full border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:border-blue-200 hover:text-brand transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        Keberangkatan
      </button>
      <button type="button" class="filter-btn flex items-center gap-2 px-4 py-2.5 rounded-full border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:border-blue-200 hover:text-brand transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        Populer
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
      </button>
    </div>

    {{-- 3-column Visa product cards (spun.global style) --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
      @forelse($featuredProducts as $product)
      @php
        $hasDiscount = $product->discount_price && $product->discount_price < $product->base_price;
        $discountPercent = $hasDiscount ? round((1 - $product->discount_price / $product->base_price) * 100) : 0;
        $countryName = $product->country->name ?? '';
        $countryCode = $product->country->code ?? '';
        $flagEmoji = $flagImages[$countryCode] ?? ($product->country->flag_emoji ?? '🏳️');
        $cardImage = $cardImages[$countryName] ?? $defaultImage;
      @endphp
      <a href="{{ route('visa.show', $product->slug) }}" class="visa-card group block rounded-2xl border border-slate-200/80 bg-white p-5 transition-all hover:shadow-lg hover:border-blue-200/60">
        <div class="flex items-start justify-between gap-3">
          {{-- Left: Visa info --}}
          <div class="flex-1 min-w-0">
            <h3 class="text-base font-bold text-slate-900 leading-snug mb-1 group-hover:text-brand transition-colors">
              {{ $product->name }}
            </h3>
            <p class="text-sm text-slate-500 font-medium mb-4">
              @if($product->processing_time)
                Selesai dalam {{ $product->processing_time }}
              @else
                {{ $product->short_description }}
              @endif
            </p>
          </div>

          {{-- Right: Country flag image --}}
          <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 border border-slate-100 shadow-sm">
            <img 
              src="{{ $cardImage }}" 
              alt="{{ $countryName }}" 
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              loading="lazy"
            >
          </div>
        </div>

        {{-- Price row --}}
        <div class="flex items-center gap-2 mt-1">
          @if($hasDiscount)
          <span class="text-sm font-bold text-brand">Dari IDR {{ number_format($product->discount_price, 0, ',', '.') }}</span>
          <span class="text-xs text-slate-400 line-through font-medium">IDR {{ number_format($product->base_price, 0, ',', '.') }}</span>
          <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-red-50 text-[11px] font-bold text-red-500 border border-red-100">
            {{ $discountPercent }}% OFF
          </span>
          @else
          <span class="text-sm font-bold text-brand">Dari IDR {{ number_format($product->base_price, 0, ',', '.') }}</span>
          @endif
        </div>
      </a>
      @empty
      <div class="col-span-full text-center py-12 text-slate-500 font-medium">
        <p>Belum ada produk visa tersedia. Silakan tambahkan dari admin panel.</p>
      </div>
      @endforelse
    </div>

    {{-- "Lihat Semua" link --}}
    @if($featuredProducts->count() > 0)
    <div class="flex justify-center mt-10">
      <a href="{{ route('visa.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-brand text-white font-bold text-sm hover:brightness-110 transition-all shadow-md shadow-blue-500/20">
        Lihat Semua Visa
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
        </svg>
      </a>
    </div>
    @endif

  </div>
</section>
