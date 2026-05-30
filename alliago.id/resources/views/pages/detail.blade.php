<x-layouts.app
    title="Detail Layanan | Alliago.id — Layanan Visa Profesional"
    description="Kenali layanan lengkap Alliago.id: konsultasi visa, persiapan dokumen, pengajuan, hingga selesai. Tim profesional siap membantu semua kebutuhan visa perjalanan internasional Anda."
>
    <div class="pt-[73px] md:pt-[81px]">
        <x-home.header />
        <div class="page-wrapper">
            <div class="content-section pt-10">
                @if($highlightProduct)
                    <x-home.detail :product="$highlightProduct" />
                @else
                    <div class="container mx-auto px-4 py-20 text-center">
                        <h1 class="text-3xl font-bold text-slate-900 mb-4">Detail Layanan</h1>
                        <p class="text-slate-600">Informasi detail layanan belum tersedia saat ini.</p>
                    </div>
                @endif
            </div>
            <x-home.cta />
        </div>
        <x-home.footer />
    </div>
</x-layouts.app>
