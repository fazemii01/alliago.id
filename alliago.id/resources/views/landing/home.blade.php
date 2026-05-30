<x-layouts.app
    title="Alliago.id | Jasa Visa Terpercaya — Japan, Korea, Australia, Schengen"
    description="Alliago.id melayani pengurusan visa Japan, Korea, Australia, Schengen, dan lebih dari 50 negara. Proses mudah, cepat, dan terpercaya untuk semua kebutuhan perjalanan Anda."
>

@push('meta')
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
</x-layouts.app>
