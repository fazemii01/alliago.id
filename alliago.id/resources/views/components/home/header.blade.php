<header class="site-header fixed inset-x-0 top-0 z-[100] border-b border-slate-200/80 bg-white/95 backdrop-blur transition-[background-color,box-shadow,border-color] duration-300">
  <div class="mx-auto flex max-w-5xl items-center justify-between gap-3 px-4 py-3 md:px-6">
    <a href="/" class="flex min-w-0 items-center gap-2.5 sm:gap-3">
      <img src="/images/alliago-logo.jpeg" alt="AlliaGo" class="h-9 w-9 shrink-0 rounded-xl object-cover sm:h-10 sm:w-10">

      <div class="min-w-0">
        <p class="truncate text-sm font-bold leading-none tracking-tight text-slate-900 sm:text-base md:text-lg">
          Alliago.id
        </p>
        <p class="truncate text-[9px] font-bold uppercase tracking-[0.18em] text-slate-400 sm:text-[10px] sm:tracking-[0.22em]">
          Visa Assistance
        </p>
      </div>
    </a>

    <nav class="hidden items-center gap-7 text-sm font-semibold text-slate-600 md:flex">
      <a href="#services" class="transition hover:text-[#0361fc]">Visa</a>
      <a href="#detail" class="transition hover:text-[#0361fc]">Detail</a>
      <a href="#process" class="transition hover:text-[#0361fc]">Proses</a>
      <a href="#faq" class="transition hover:text-[#0361fc]">FAQ</a>
    </nav>

    <div class="hidden items-center gap-3 md:flex">
      <a href="/login" class="px-3 text-sm font-semibold text-slate-600 transition hover:text-[#0361fc]">
        Log in
      </a>
      <a href="https://wa.me/6281334455616" class="rounded-full border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-[#0361fc]">
        WhatsApp
      </a>
      <a href="#search" class="rounded-full bg-[#0361fc] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
        Cek Visa
      </a>
    </div>

    <button
      id="menuBtn"
      type="button"
      aria-label="Toggle mobile menu"
      aria-expanded="false"
      aria-controls="mobileMenu"
      class="mobile-menu-btn flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white/90 text-slate-700 shadow-sm transition duration-300 hover:border-slate-300 hover:text-[#0361fc] md:hidden"
    >
      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path class="burger-line burger-line-top" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14" />
        <path class="burger-line burger-line-middle" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
        <path class="burger-line burger-line-bottom" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17h14" />
      </svg>
    </button>
  </div>

  <div id="mobileMenu" class="mobile-menu-panel absolute inset-x-0 top-full border-t border-slate-200/80 bg-white/96 shadow-2xl backdrop-blur md:hidden">
    <nav class="mx-auto flex max-h-[calc(100vh-4.5rem)] max-w-5xl flex-col gap-2 overflow-y-auto px-4 py-4">
      <a href="#services" class="mobile-nav-link rounded-2xl px-3 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-[#0361fc]">Visa</a>
      <a href="#detail" class="mobile-nav-link rounded-2xl px-3 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-[#0361fc]">Detail</a>
      <a href="#process" class="mobile-nav-link rounded-2xl px-3 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-[#0361fc]">Proses</a>
      <a href="#faq" class="mobile-nav-link rounded-2xl px-3 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-[#0361fc]">FAQ</a>

      <div class="mt-3 grid gap-3 border-t border-slate-100 pt-4">
        <a href="/login" class="mobile-nav-link rounded-full border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-[#0361fc]">
          Log in
        </a>
        <a href="https://wa.me/6281234567890" class="mobile-nav-link rounded-full border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-[#0361fc]">
          WhatsApp
        </a>
        <a href="#search" class="mobile-nav-link rounded-full bg-[#0361fc] px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-blue-700">
          Cek Visa
        </a>
      </div>
    </nav>
  </div>
</header>
