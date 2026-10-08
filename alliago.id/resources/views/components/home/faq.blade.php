@props(['siteFaqs' => collect()])

<section id="faq" class="py-16 px-4 sm:px-6 bg-[#F8FAFC] scroll-mt-24">
    <div class="mx-auto max-w-7xl home-faq-grid">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 border border-blue-200/80 px-3.5 py-1 text-xs font-bold text-[#00275A] mb-3">
                <svg class="h-4 w-4 text-[#FE6A00]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Tanya Jawab</span>
            </div>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Pertanyaan yang Sering Diajukan
            </h2>
            <p class="mt-3 text-xs sm:text-sm text-slate-500 leading-relaxed max-w-md">
                Ketahui lebih lanjut mengenai proses pengajuan visa, pemesanan tiket, opsi pembayaran IDR/RM, dan jaminan keamanan data di AlliaGo.
            </p>
            <div class="mt-6">
                <a href="https://wa.me/6281334455616?text=Halo%20AlliaGo,%20saya%20ada%20pertanyaan%20seputar%20layanan" target="_blank" rel="noopener noreferrer" style="background-color: #00275A !important; color: #ffffff !important;" class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold shadow-md hover:opacity-95 transition-all">
                    <span>Masih ada pertanyaan? Chat CS</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>
        </div>

        <div class="space-y-3.5">
            @forelse($siteFaqs as $faq)
                <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }" class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm transition-all hover:border-blue-200">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between gap-4 text-left">
                        <span class="text-sm font-extrabold text-slate-900" :class="open ? 'text-[#00275A]' : ''">
                            {{ $faq->question }}
                        </span>
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 font-bold text-sm transition-transform" :class="open ? 'rotate-45 bg-[#FE6A00] text-white' : ''">
                            +
                        </span>
                    </button>
                    <div x-show="open" x-cloak x-transition class="mt-3.5 border-t border-slate-100 pt-3 text-xs sm:text-sm text-slate-600 font-normal leading-relaxed">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-400">
                    <p class="text-xs sm:text-sm">Belum ada pertanyaan FAQ yang diterbitkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

