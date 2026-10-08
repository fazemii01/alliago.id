<x-layouts.app
    title="Alliago.id | Tiket Pesawat, Ferry Internasional & Asistensi Visa Resmi"
    description="Alliago.id melayani pemesanan tiket penerbangan, tiket ferry Batam-Singapura-Malaysia, dan asistensi resmi visa 50+ negara dengan kemudahan pembayaran IDR & RM."
>

@push('meta')
<!-- Google Font Outfit Scoped for Home Redesign -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    /* Scoped Homepage Style Enforcements from alliago.pen */
    .hero-section {
        background: linear-gradient(180deg, rgba(8,24,56,0.92) 0%, rgba(12,38,84,0.78) 45%, rgba(20,50,100,0.55) 100%) !important;
    }
    .hero-divider {
        position: absolute !important;
        bottom: -1px !important;
        left: 0 !important;
        width: 100% !important;
        line-height: 0 !important;
    }
    .hero-divider svg {
        display: block !important;
        width: 100% !important;
        height: 80px !important;
    }
    .bg-brand-navy { background-color: #001D44 !important; }
    .bg-brand-blue { background-color: #00275A !important; }
    .bg-brand-dark { background-color: #0A2540 !important; }
    .bg-brand-orange { background-color: #FE6A00 !important; color: #ffffff !important; }
    .bg-brand-orange-hover:hover { background-color: #E05D00 !important; }
    .text-brand-orange { color: #FE6A00 !important; }
    .border-brand-orange { border-color: #FE6A00 !important; }
    .shadow-brand-orange { box-shadow: 0 10px 25px -4px rgba(254, 106, 0, 0.45) !important; }
    .btn-brand-orange {
        background-color: #FE6A00 !important;
        color: #ffffff !important;
        transition: all 0.2s ease;
    }
    .btn-brand-orange:hover {
        background-color: #E05D00 !important;
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -4px rgba(254, 106, 0, 0.5) !important;
    }
    .home-header-link {
        color: #ffffff !important;
        transition: color 0.15s ease;
    }
    .home-header-link:hover {
        color: #FE6A00 !important;
    }
    .home-topbar-link {
        color: #BFDBFE !important;
        transition: color 0.15s ease;
    }
    .home-topbar-link:hover {
        color: #ffffff !important;
    }

    /* Alpine Cloak & Hidden State Preservation */
    [x-cloak],
    [style*="display: none"],
    [style*="display:none"] {
        display: none !important;
    }

    /* Hero Search Grids */
    @media (min-width: 768px) {
        .flight-search-grid {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 0.75rem !important;
            position: relative !important;
        }
        .flight-swap-btn {
            display: flex !important;
            position: absolute !important;
            left: calc(25% - 18px) !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 20 !important;
        }
        .ferry-search-grid,
        .visa-search-grid {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 0.75rem !important;
        }
    }
    @media (max-width: 767px) {
        .flight-search-grid,
        .ferry-search-grid,
        .visa-search-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 0.75rem !important;
        }
        .flight-swap-btn {
            display: flex !important;
            position: absolute !important;
            right: 1.25rem !important;
            top: 76px !important;
            transform: translateY(-50%) !important;
            z-index: 20 !important;
        }
    }

    /* Centered Custom Calendar Modal & Backdrop */
    .alliago-cal-backdrop {
        position: absolute !important;
        inset: 0 !important;
        background: rgba(0, 29, 68, 0.35) !important;
        backdrop-filter: blur(2px) !important;
        border-radius: 1.5rem !important;
        z-index: 45 !important;
    }
    .alliago-cal-modal {
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        z-index: 50 !important;
        width: 370px !important;
        max-width: 95% !important;
        background: #ffffff !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 25px 50px -12px rgba(0, 29, 68, 0.4), 0 0 0 1px rgba(0, 0, 0, 0.08) !important;
    }

    /* 7-column calendar day grid */
    .cal-grid-7 {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr) !important;
        gap: 4px !important;
    }

    /* Deals 5-column grid on desktop */
    @media (min-width: 1024px) {
        .home-deals-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr) !important;
            gap: 1rem !important;
        }
    }
    @media (min-width: 640px) and (max-width: 1023px) {
        .home-deals-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1rem !important;
        }
    }
    @media (max-width: 639px) {
        .home-deals-grid {
            display: grid;
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
    }
    .home-deals-grid[style*="display: none"],
    .home-deals-grid[style*="display:none"] {
        display: none !important;
    }
</style>

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Organization",
    "name": "Alliago.id",
    "url": "https://www.alliago.id",
    "logo": "https://www.alliago.id/images/alliago-logo.jpeg",
    "description": "Platform visa assistance, tiket ferry, dan tiket pesawat terpercaya di Indonesia",
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer service",
        "availableLanguage": ["Indonesian", "English", "Malay"]
    }
}
</script>
@endpush

    {{-- Root Homepage Scoped Wrapper (Outfit Typography & 1:1 alliago.pen Canvas) --}}
    <div class="font-['Outfit',sans-serif] text-slate-800 antialiased bg-[#F8FAFC] w-full max-w-full overflow-x-hidden">
        {{-- 1. Dedicated Header Navigation Bar with Top Announcement Bar (alliago.pen node x-home.home-header) --}}
        <x-home.home-header />

        {{-- Main Home Content Container --}}
        <main class="pt-[108px]">
            {{-- 2. Hero Section with Tabbed Search Dock & Custom Non-Native Calendar (alliago.pen node W6lSTk) --}}
            <x-home.hero :countries="$countries" :featuredProducts="$featuredProducts" />

            {{-- 3. Promo & Exclusive Offers (alliago.pen node Q9sSRx) --}}
            <x-home.promo-cards />

            {{-- 4. Destinasi Populer & Layanan Asistensi Visa Resmi --}}
            <x-home.visa-deals :featuredProducts="$featuredProducts" :myrRate="$myrRate" />

            {{-- 5. Curated Tour & Holiday Bundled Packages (alliago.pen node HrWjE) --}}
            <x-home.packages />

            {{-- 6. Value Proposition 6-Pillar Bento Grid (alliago.pen node yJJ8K) --}}
            <x-home.features />

            {{-- 7. Official Consultation & App Download Banner (alliago.pen node c04yD) --}}
            <x-home.consultation-banner />
        </main>

        {{-- 8. Comprehensive Footer with Partners & Route Arcs (alliago.pen node b1PhN) --}}
        <x-home.home-footer />

        {{-- 9. Floating Support WhatsApp Button (alliago.pen node IQjpw) --}}
        <x-home.floating-support />
    </div>

    {{-- Retained Campaign / Promotion Popup Modal --}}
    @if ($popupBanner && ($popupBanner->desktop_banner_path || $popupBanner->desktop_banner_url || $popupBanner->mobile_banner_path || $popupBanner->mobile_banner_url))
        @php
            $desktopSrc = $popupBanner->desktop_banner_path 
                ? Storage::disk('s3')->url($popupBanner->desktop_banner_path) 
                : $popupBanner->desktop_banner_url;
                
            $mobileSrc = $popupBanner->mobile_banner_path 
                ? Storage::disk('s3')->url($popupBanner->mobile_banner_path) 
                : $popupBanner->mobile_banner_url;
                
            if (!$mobileSrc) {
                $mobileSrc = $desktopSrc;
            }
        @endphp

        <!-- Landing Page Home Promo Popup Modal -->
        <div x-data="{
                show: false,
                init() {
                    setTimeout(() => {
                        this.show = true;
                    }, {{ ($popupBanner->delay_seconds ?? 3) * 1000 }});
                },
                dismiss() {
                    this.show = false;
                }
             }"
             x-show="show"
             style="display: none;"
             class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md transition-opacity duration-300"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="dismiss()">
             
             <!-- Modal Card -->
             <div class="relative bg-white dark:bg-slate-900 rounded-[32px] overflow-hidden shadow-2xl w-full max-w-[85vw] sm:max-w-[70vw] md:max-w-2xl lg:max-w-3xl transform transition-all duration-500 ring-1 ring-white/10 max-h-[85vh] flex flex-col"
                  x-show="show"
                  x-transition:enter="transition ease-out duration-500 transform"
                  x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                  x-transition:enter-end="opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-300 transform"
                  x-transition:leave-start="opacity-100 scale-100"
                  x-transition:leave-end="opacity-0 scale-95"
                  @click.away="dismiss()">
                  
                  <!-- Close Button -->
                  <button @click="dismiss()" 
                          type="button" 
                          class="absolute right-4 top-4 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-slate-950/50 text-white backdrop-blur-md transition hover:bg-slate-950/70 hover:rotate-90 hover:scale-105 active:scale-95 shadow-md duration-300"
                          aria-label="Tutup Promo">
                      <svg class="h-4.5 w-4.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                  </button>

                  <!-- Banner Area -->
                  @if ($popupBanner->redirect_link)
                      <a href="{{ $popupBanner->redirect_link }}" target="_blank" rel="noopener noreferrer" class="block w-full cursor-pointer group overflow-hidden">
                          <picture class="block w-full">
                              <source media="(max-width: 767px)" srcset="{{ $mobileSrc }}">
                              <img src="{{ $desktopSrc }}" alt="{{ $popupBanner->name }}" class="w-full max-w-full h-auto max-h-[80vh] object-contain block transition-transform duration-500 group-hover:scale-[1.03] mx-auto">
                          </picture>
                      </a>
                  @else
                      <div class="block w-full overflow-hidden">
                          <picture class="block w-full">
                              <source media="(max-width: 767px)" srcset="{{ $mobileSrc }}">
                              <img src="{{ $desktopSrc }}" alt="{{ $popupBanner->name }}" class="w-full max-w-full h-auto max-h-[80vh] object-contain block transition-transform duration-500 hover:scale-[1.03] mx-auto">
                          </picture>
                      </div>
                  @endif
             </div>
        </div>
    @endif
</x-layouts.app>
