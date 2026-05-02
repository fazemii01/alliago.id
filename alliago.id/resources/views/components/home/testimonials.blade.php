@props(['testimonials' => collect()])

<section class="py-20 px-4 section-soft">
  <div class="container mx-auto">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">
      <div>
        <p class="text-xs font-bold tracking-wide font-medium text-brand mb-3">Testimoni</p>
        <h2 class="text-4xl md:text-5xl font-bold tracking-[-0.05em]">Dibantu sampai submit.</h2>
      </div>
      <div class="card rounded-2xl px-5 py-4"><p class="font-bold">⭐ 4.9/5 dari traveler</p><p class="text-sm text-slate-600 font-medium">Dari {{ $testimonials->count() }} ulasan klien</p></div>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
      @forelse($testimonials as $testimonial)
      <div class="card rounded-[24px] p-7">
        <p class="text-yellow-400 mb-4">{{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}</p>
        <p class="text-slate-700 font-medium leading-relaxed mb-6">"{{ $testimonial->content }}"</p>
        <p class="font-bold text-slate-900">{{ $testimonial->name }}</p>
        @if($testimonial->visa_label)
        <p class="text-sm text-slate-500 font-bold">{{ $testimonial->visa_label }}</p>
        @endif
      </div>
      @empty
      <div class="col-span-full text-center py-12 text-slate-500 font-medium">
        <p>Belum ada testimoni. Tambahkan dari admin panel.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>
