<x-layouts.app title="FAQ | Alliago.id">
    <div class="pt-[73px] md:pt-[81px]">
        <x-home.header />
        <div class="page-wrapper">
            <div class="content-section pt-10">
                <x-home.faq :siteFaqs="$siteFaqs" />
            </div>
            <x-home.cta />
        </div>
        <x-home.footer />
    </div>
</x-layouts.app>
