<x-layouts.app title="Alliago.id | Visa Assistance">
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
