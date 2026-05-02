@props(['siteFaqs' => collect()])

<section id="faq" class="py-20 px-4 bg-white scroll-mt-32 md:scroll-mt-36">
  <div class="container mx-auto grid lg:grid-cols-[0.8fr_1.2fr] gap-12">
    <div>
      <p class="text-xs font-bold tracking-wide font-medium text-brand mb-3">FAQ</p>
      <h2 class="text-4xl md:text-5xl font-bold tracking-[-0.05em] mb-5">Pertanyaan umum sebelum apply.</h2>
      <p class="text-slate-600 font-medium leading-relaxed">FAQ diletakkan dekat bawah seperti product page agar user punya jawaban sebelum klik bayar.</p>
    </div>
    <div class="space-y-4">
      @forelse($siteFaqs as $faq)
      <div class="faq-item card rounded-3xl p-6 {{ $loop->first ? 'open border-brand' : 'hover:border-brand/30' }} transition-colors">
        <button class="faq-toggle w-full flex items-center justify-between gap-5 text-left">
          <span class="font-bold text-lg text-slate-800">{{ $faq->question }}</span>
          <span class="faq-plus text-2xl font-light {{ $loop->first ? 'text-brand' : 'text-slate-400' }} transition-transform">+</span>
        </button>
        <p class="faq-answer text-slate-600 font-medium leading-relaxed mt-4">{{ $faq->answer }}</p>
      </div>
      @empty
      <div class="text-center py-12 text-slate-500 font-medium">
        <p>Belum ada FAQ. Tambahkan dari admin panel.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>
