@props(['product'])

@php
$tags = array_filter([
    $product->country->name ?? null,
    $product->type ?? null,
    $product->promo_label ?? null,
]);
@endphp

<section id="detail" class="scroll-mt-32 px-4 py-14 section-soft md:scroll-mt-36 md:py-20">
  <div class="container mx-auto">
    <div class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_340px] lg:gap-8 xl:grid-cols-[minmax(0,1fr)_370px]">
      <div class="card overflow-hidden rounded-[24px] p-4 md:rounded-[28px] md:p-8">
        <div class="mb-6 flex flex-col gap-4 md:mb-8 md:flex-row md:items-start md:justify-between md:gap-5">
          <div>
            <div class="mb-3 flex flex-wrap gap-2 md:mb-4">
              @foreach($tags as $tag)
              <span class="tag-blue rounded-full px-3 py-1 text-xs font-bold">{{ $tag }}</span>
              @endforeach
            </div>
            <h2 class="mb-3 text-3xl font-bold tracking-[-0.05em] sm:text-[2.15rem] md:text-5xl">{{ $product->name }}</h2>
            <p class="max-w-3xl text-base font-medium text-slate-600 sm:text-[17px] md:text-lg">{{ $product->short_description }}</p>
          </div>
          <div class="min-w-0 rounded-2xl border border-slate-100 bg-slate-50 p-4 sm:min-w-[160px] md:self-start">
            <p class="text-[10px] font-bold tracking-wide font-medium text-slate-500">Mulai dari</p>
            <p class="text-2xl font-bold text-brand">{{ $product->formatted_display_price }}</p>
          </div>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-3 md:mb-8 md:grid-cols-4">
          @if($product->processing_time)
          <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3.5 md:p-4"><p class="text-[10px] font-bold tracking-wide text-slate-500 font-semibold">Proses</p><p class="text-lg font-bold text-slate-800 md:text-xl">{{ $product->processing_time }}</p></div>
          @endif
          @if($product->stay_duration)
          <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3.5 md:p-4"><p class="text-[10px] font-bold tracking-wide text-slate-500 font-semibold">Stay</p><p class="text-lg font-bold text-slate-800 md:text-xl">{{ $product->stay_duration }}</p></div>
          @endif
          @if($product->validity)
          <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3.5 md:p-4"><p class="text-[10px] font-bold tracking-wide text-slate-500 font-semibold">Berlaku</p><p class="text-lg font-bold text-slate-800 md:text-xl">{{ $product->validity }}</p></div>
          @endif
          <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3.5 md:p-4"><p class="text-[10px] font-bold tracking-wide text-slate-500 font-semibold">Tipe</p><p class="text-lg font-bold text-slate-800 md:text-xl">{{ $product->type }}</p></div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
          <div>
            <h3 class="mb-4 text-xl font-bold md:text-2xl">Deskripsi</h3>
            <p class="mb-5 text-sm leading-relaxed font-medium text-slate-700 sm:text-base">{{ $product->description }}</p>
            @if($product->requirements->count())
            <ul class="space-y-3 text-slate-800 font-semibold">
              @foreach($product->requirements as $req)
              <li>✓ {{ $req->title }}</li>
              @endforeach
            </ul>
            @endif
          </div>
          @if($product->documents->count())
          <div>
            <h3 class="mb-4 text-xl font-bold md:text-2xl">Dokumen utama</h3>
            <div class="space-y-3">
              @foreach($product->documents as $doc)
              <div class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-white p-3.5 md:p-4">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-soft-blue font-bold text-brand">{{ $loop->iteration }}</span>
                <p class="text-sm font-bold text-slate-800 sm:text-base">{{ $doc->name }}</p>
              </div>
              @endforeach
            </div>
          </div>
          @endif
        </div>
      </div>

      <aside class="summary-card rounded-[24px] p-4 md:rounded-[28px] md:p-6 lg:sticky lg:top-28">
        <div class="mb-5 flex items-start justify-between gap-3">
          <div>
            <p class="mb-1 text-xs font-bold tracking-wide font-medium text-slate-500">Paket visa</p>
            <h3 class="text-xl font-bold md:text-2xl">{{ $product->type }}</h3>
          </div>
          @if($product->discount_price && $product->discount_price < $product->base_price)
          @php $pct = round((1 - $product->discount_price / $product->base_price) * 100); @endphp
          <span class="tag-pink rounded-full px-3 py-1 text-xs font-bold">{{ $pct }}% OFF</span>
          @endif
        </div>

        <div class="mb-5 grid grid-cols-2 gap-3">
          @if($product->processing_time)
          <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3.5 md:p-4"><p class="text-xs font-bold text-slate-500">Proses</p><p class="font-bold text-slate-800">{{ $product->processing_time }}</p></div>
          @endif
          <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3.5 md:p-4"><p class="text-xs font-bold text-slate-500">Tipe</p><p class="font-bold text-slate-800">{{ $product->type }}</p></div>
        </div>

        <div class="mb-5 border-t border-slate-100 pt-5">
          @if($product->discount_price && $product->discount_price < $product->base_price)
          <div class="mb-2 flex justify-between gap-3 text-sm font-bold text-slate-500"><span>Harga normal</span><span class="line-through">{{ $product->formatted_base_price }}</span></div>
          <div class="flex items-end justify-between gap-3"><span class="font-bold">Total</span><span class="text-2xl font-bold text-brand sm:text-3xl">{{ $product->formatted_discount_price }}</span></div>
          @else
          <div class="flex items-end justify-between gap-3"><span class="font-bold">Total</span><span class="text-2xl font-bold text-brand sm:text-3xl">{{ $product->formatted_base_price }}</span></div>
          @endif
        </div>

        <a href="{{ route('visa.show', $product->slug) }}" class="btn-primary mb-3 w-full rounded-2xl py-3.5 font-bold tracking-wide font-semibold md:py-4 block text-center">Ajukan Sekarang</a>
        <a href="https://wa.me/6281334455616" class="btn-light w-full rounded-2xl py-3.5 font-bold tracking-wide font-semibold md:py-4 block text-center">Chat Konsultan</a>

        <ul class="mt-5 space-y-2 text-sm font-semibold text-slate-700">
          <li>✓ Harga terlihat sebelum bayar</li>
          <li>✓ Reminder dokumen kurang</li>
          <li>✓ Update status via WhatsApp</li>
        </ul>
      </aside>
    </div>
  </div>
</section>
