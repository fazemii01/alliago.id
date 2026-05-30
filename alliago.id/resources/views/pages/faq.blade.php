<x-layouts.app
    title="FAQ | Alliago.id — Pertanyaan yang Sering Diajukan"
    description="Temukan jawaban atas pertanyaan umum seputar layanan visa dan tiket pesawat Alliago.id. Proses pengajuan, persyaratan dokumen, pembayaran, dan informasi lainnya."
>

@push('meta')
@if($siteFaqs->isNotEmpty())
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach ($siteFaqs as $faq)
        {
            "@type": "Question",
            "name": "{{ addslashes($faq->question) }}",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{ addslashes(strip_tags($faq->answer)) }}"
            }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endif
@endpush
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
