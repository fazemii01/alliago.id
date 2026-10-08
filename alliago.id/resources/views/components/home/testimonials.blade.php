@props(['testimonials' => collect()])

<section class="py-20 px-4 section-soft">
  <div class="container mx-auto">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">
      <div>
        <p class="text-xs font-bold tracking-wide font-medium text-brand mb-3">Testimoni</p>
        <h2 class="text-4xl md:text-5xl font-bold tracking-[-0.05em]">Dibantu sampai submit.</h2>
      </div>
      <div class="rounded-2xl bg-white border border-slate-200/90 shadow-sm px-5 py-4">
        <div class="flex items-center gap-1.5 text-amber-500 font-extrabold text-sm mb-0.5">
          <svg class="h-4 w-4 fill-amber-400 text-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <span>4.9 / 5 Rating Klien</span>
        </div>
        <p class="text-xs text-slate-500 font-medium">Berdasarkan ulasan traveler & pemohon visa</p>
      </div>
    </div>
    <div class="home-testimonials-grid">
      @forelse($testimonials as $testimonial)
      <div class="rounded-3xl border border-slate-200/90 bg-white p-7 shadow-sm hover:shadow-xl transition-all flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-1 mb-4 text-amber-400">
            @for($i = 0; $i < ($testimonial->rating ?? 5); $i++)
              <svg class="h-4 w-4 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
          </div>
          <p class="text-slate-700 text-sm leading-relaxed mb-6 font-normal">"{{ $testimonial->content }}"</p>
        </div>
        <div class="border-t border-slate-100 pt-4">
          <p class="font-extrabold text-slate-900 text-sm">{{ $testimonial->name }}</p>
          @if($testimonial->visa_label)
            <p class="text-xs text-[#00275A] font-bold mt-0.5">{{ $testimonial->visa_label }}</p>
          @endif
        </div>
      </div>
      @empty
      <div class="col-span-full text-center py-12 text-slate-500 font-medium">
        <p>Belum ada testimoni. Tambahkan dari admin panel.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>
