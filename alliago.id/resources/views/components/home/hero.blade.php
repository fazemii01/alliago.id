<main class="hero-main pt-36 md:pt-40 pb-16 px-4">
  <div class="hero-decor" aria-hidden="true">
    <div class="hero-shape-blue"></div>
    <div class="hero-shape-pink"></div>

    <svg class="hero-globe" viewBox="0 0 600 600" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="300" cy="300" r="210" class="globe-blue" />
      <ellipse cx="300" cy="300" rx="210" ry="70" class="globe-blue" />
      <ellipse cx="300" cy="300" rx="210" ry="120" class="globe-blue" />
      <ellipse cx="300" cy="300" rx="210" ry="165" class="globe-pink" />
      <ellipse cx="300" cy="300" rx="70" ry="210" class="globe-blue" />
      <ellipse cx="300" cy="300" rx="120" ry="210" class="globe-blue" />
      <ellipse cx="300" cy="300" rx="165" ry="210" class="globe-pink" />
      <line x1="90" y1="300" x2="510" y2="300" class="globe-blue" />
      <line x1="300" y1="90" x2="300" y2="510" class="globe-blue" />
      <circle cx="220" cy="210" r="6" class="globe-dots" />
      <circle cx="365" cy="245" r="6" class="globe-dots" />
      <circle cx="270" cy="360" r="6" class="globe-dots" />
      <circle cx="410" cy="330" r="6" class="globe-dots" />
    </svg>

    <div class="hero-chip blue chip-1">Japan Visa</div>
    <div class="hero-chip pink chip-2">Korea Visa</div>
    <div class="hero-chip blue chip-3">Schengen</div>
    <div class="hero-chip pink chip-4">Australia</div>
  </div>

  <section class="container mx-auto hero-content">
    <div class="grid lg:grid-cols-[1fr_420px] gap-10 items-center">
      <div>
        <div class="inline-flex items-center gap-2 bg-soft-blue border border-[#dbe8ff] rounded-full px-4 py-2 mb-7">
          <span class="w-2 h-2 rounded-full bg-pink-brand"></span>
          <span class="text-xs font-black uppercase tracking-[0.18em] text-brand">Visa online & assisted application</span>
        </div>

        <h1 class="hero-title text-5xl md:text-7xl font-black tracking-[-0.065em] leading-[0.95] text-slate-950 mb-7">
          Urus visa tanpa ribet, dari rumah.
        </h1>
        <p class="text-lg md:text-xl text-slate-600 leading-relaxed max-w-2xl mb-9 font-medium">
          Cari visa berdasarkan negara tujuan, lihat syarat dokumen, estimasi proses, harga, dan add-ons sebelum mulai mengajukan.
        </p>

        <div class="flex flex-wrap gap-3 mb-9">
          <span class="tag-blue rounded-full px-4 py-2 text-sm font-black">Harga transparan</span>
          <span class="tag-pink rounded-full px-4 py-2 text-sm font-black">Review dokumen</span>
          <span class="tag-blue rounded-full px-4 py-2 text-sm font-black">Update via WhatsApp</span>
        </div>

        <div class="grid grid-cols-3 gap-4 max-w-xl">
          <div class="card rounded-2xl p-4">
            <p class="text-3xl font-black text-brand">190+</p>
            <p class="text-xs font-bold text-slate-500">Negara tujuan</p>
          </div>
          <div class="card rounded-2xl p-4">
            <p class="text-3xl font-black text-pink">4.9</p>
            <p class="text-xs font-bold text-slate-500">Rating klien</p>
          </div>
          <div class="card rounded-2xl p-4">
            <p class="text-3xl font-black text-brand">24/7</p>
            <p class="text-xs font-bold text-slate-500">Support</p>
          </div>
        </div>
      </div>

      <aside id="search" class="search-shell rounded-[30px] p-5 md:p-6">
        <div class="flex items-start justify-between gap-4 mb-6">
          <div>
            <p class="text-xs font-black uppercase tracking-[0.22em] text-brand mb-2">Cari visa</p>
            <h2 class="text-2xl font-black tracking-tight">Mau pergi ke mana?</h2>
          </div>
          <span class="tag-pink rounded-full px-3 py-1 text-xs font-black">Gratis cek</span>
        </div>

        <form class="grid gap-3">
          <label class="field rounded-2xl p-4 block">
            <span class="block text-[10px] font-black uppercase tracking-[0.18em] text-slate-400 mb-1">Paspor saya</span>
            <select class="font-extrabold text-slate-900">
              <option>Indonesia</option>
              <option>Malaysia</option>
              <option>Singapore</option>
            </select>
          </label>

          <label class="field rounded-2xl p-4 block">
            <span class="block text-[10px] font-black uppercase tracking-[0.18em] text-slate-400 mb-1">Negara tujuan</span>
            <input list="countries" placeholder="Contoh: Jepang" class="font-extrabold text-slate-900 placeholder:text-slate-300" />
            <datalist id="countries">
              <option value="Jepang"></option>
              <option value="Korea Selatan"></option>
              <option value="Australia"></option>
              <option value="Schengen"></option>
              <option value="Amerika Serikat"></option>
            </datalist>
          </label>

          <label class="field rounded-2xl p-4 block">
            <span class="block text-[10px] font-black uppercase tracking-[0.18em] text-slate-400 mb-1">Tujuan perjalanan</span>
            <select class="font-extrabold text-slate-900">
              <option>Wisata / Liburan</option>
              <option>Bisnis</option>
              <option>Keluarga</option>
              <option>Pelajar</option>
            </select>
          </label>

          <button type="button" class="btn-primary rounded-2xl py-4 font-black uppercase tracking-wider mt-1">Tampilkan Visa</button>
        </form>
      </aside>
    </div>
  </section>
</main>
