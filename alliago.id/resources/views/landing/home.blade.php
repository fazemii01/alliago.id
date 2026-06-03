<x-layouts.app
    title="Alliago.id | Jasa Visa Terpercaya — Japan, Korea, Australia, Schengen"
    description="Alliago.id melayani pengurusan visa Japan, Korea, Australia, Schengen, dan lebih dari 50 negara. Proses mudah, cepat, dan terpercaya untuk semua kebutuhan perjalanan Anda."
>

@push('meta')
<!-- Load Alpine.js to power interactive elements like campaign popup modals on the home landing page -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Organization",
    "name": "Alliago.id",
    "url": "https://www.alliago.id",
    "logo": "https://www.alliago.id/images/alliago-logo.jpeg",
    "description": "Platform visa assistance dan tiket pesawat terpercaya di Indonesia",
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer service",
        "availableLanguage": ["Indonesian", "English"]
    }
}
</script>
@endpush

    <div class="pt-[73px] md:pt-[81px]">
        <x-home.header />
        <div class="page-wrapper">
            <x-home.hero :countries="$countries" :featuredProducts="$featuredProducts" />
            <div class="content-section">
                <!-- <x-home.features /> -->
                <x-home.services :featuredProducts="$featuredProducts" />
                @if($highlightProduct)
                <x-home.detail :product="$highlightProduct" />
                @endif
                <x-home.process />
            </div>
            <div class="content-section">
                <x-home.supported-visas :countries="$countries" />
            </div>
            <div class="content-section">
                <x-home.testimonials :testimonials="$testimonials" />
                <x-home.faq :siteFaqs="$siteFaqs" />
            </div>
            <x-home.cta />
        </div>
        <x-home.footer />
    </div>

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
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md transition-opacity duration-300"
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
                  
                  <!-- Close Button (Premium Floating Circle) -->
                  <button @click="dismiss()" 
                          type="button" 
                          class="absolute right-4 top-4 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-slate-950/50 text-white backdrop-blur-md transition hover:bg-slate-950/70 hover:rotate-90 hover:scale-105 active:scale-95 shadow-md duration-300"
                          aria-label="Close Promo">
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
