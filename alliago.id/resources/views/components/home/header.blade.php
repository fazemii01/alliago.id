@php $locale = app()->getLocale(); @endphp

<header class="site-header fixed inset-x-0 top-0 z-[100] border-b border-slate-200/80 bg-white/95 backdrop-blur transition-[background-color,box-shadow,border-color] duration-300">
  <div class="mx-auto flex max-w-5xl items-center justify-between gap-3 px-4 py-3 md:px-6">
    <a href="/" class="flex min-w-0 items-center gap-2.5 sm:gap-3">
      <img src="/images/alliago-logo.jpeg" alt="AlliaGo" class="h-9 w-9 shrink-0 rounded-xl object-cover sm:h-10 sm:w-10">
      <div class="min-w-0">
        <p class="truncate text-sm font-bold leading-none tracking-tight text-slate-900 sm:text-base md:text-lg">Alliago.id</p>
        <p class="truncate text-[9px] font-bold uppercase tracking-[0.18em] text-slate-400 sm:text-[10px] sm:tracking-[0.22em]">{{ __('common.footer_visa_assistance') }}</p>
      </div>
    </a>

    <nav class="hidden items-center gap-7 text-sm font-semibold text-slate-600 md:flex">
      <a href="{{ route('visa.index') }}" class="transition hover:text-[#0361fc] {{ request()->routeIs('visa.index') ? 'text-[#0361fc]' : '' }}">{{ __('common.nav_visa') }}</a>
      <a href="{{ route('flights.index') }}" class="transition hover:text-[#0361fc] {{ request()->routeIs('flights.index') ? 'text-[#0361fc]' : '' }}">{{ __('common.nav_flights') }}</a>
      <a href="{{ route('pages.process') }}" class="transition hover:text-[#0361fc] {{ request()->routeIs('pages.process') ? 'text-[#0361fc]' : '' }}">{{ __('common.nav_process') }}</a>
      <a href="{{ route('pages.faq') }}" class="transition hover:text-[#0361fc] {{ request()->routeIs('pages.faq') ? 'text-[#0361fc]' : '' }}">{{ __('common.nav_faq') }}</a>
    </nav>

    <div class="hidden items-center gap-3 md:flex">

      {{-- Language Toggle --}}
      <div class="flex items-center rounded-full border border-slate-200 bg-slate-50 p-0.5 text-xs font-bold">
        <a href="{{ route('lang.switch', 'id') }}"
           class="rounded-full px-3 py-1.5 transition {{ $locale === 'id' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
          🇮🇩 ID
        </a>
        <a href="{{ route('lang.switch', 'en') }}"
           class="rounded-full px-3 py-1.5 transition {{ $locale === 'en' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
          🇬🇧 EN
        </a>
      </div>

      @auth
        <div class="relative">
          <button id="userMenuBtn" class="flex items-center gap-2 rounded-full border border-slate-200 py-1 pl-1 pr-2 transition hover:border-slate-300 bg-white">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-orange-500 text-xs font-bold text-white">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex flex-col items-start text-left">
                <span class="text-sm font-semibold leading-none text-slate-900">{{ Auth::user()->name }}</span>
                <span class="text-[10px] text-slate-500">
                  @if(Auth::user()->hasRole('admin'))
                    {{ __('common.administrator') }}
                  @elseif(Auth::user()->hasRole('staff'))
                    {{ __('common.staff') }}
                  @else
                    {{ __('common.personal_account') }}
                  @endif
                </span>
            </div>
            <svg class="ml-1 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <div id="userDropdown" class="absolute right-0 mt-2 hidden w-64 origin-top-right rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-md p-2 shadow-2xl ring-1 ring-slate-900/5 focus:outline-none z-50 transition-all duration-200 opacity-0 translate-y-1">
              <div class="flex items-center gap-3 border-b border-slate-100 px-3 pb-3 pt-2">
                  <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-orange-400 to-orange-600 text-base font-bold text-white shadow-sm">
                      {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                  </div>
                  <div class="flex min-w-0 flex-col">
                      <span class="truncate text-sm font-bold text-slate-900">{{ Auth::user()->name }}</span>
                      <span class="text-[11px] font-medium text-slate-500">
                        @if(Auth::user()->hasRole('admin'))
                          {{ __('common.administrator') }}
                        @elseif(Auth::user()->hasRole('staff'))
                          {{ __('common.staff') }}
                        @else
                          {{ __('common.personal_account_full') }}
                        @endif
                      </span>
                  </div>
              </div>
              <div class="py-1.5">
                  <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 hover:text-[#0361fc]">
                      <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                      {{ __('common.nav_dashboard') }}
                  </a>
                  @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('staff'))
                  <a href="/admin" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 hover:text-[#0361fc]">
                      <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                      {{ __('common.nav_admin_dashboard') }}
                  </a>
                  @endif
              </div>
              <div class="py-1.5 border-t border-slate-100">
                  <div class="px-3 pb-2 pt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('common.nav_settings') }}</div>
                  <a href="{{ route('client.profile.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 hover:text-[#0361fc]">
                      <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                      {{ __('common.nav_edit_profile') }}
                  </a>
                  <a href="{{ route('client.profile.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 hover:text-[#0361fc]">
                      <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                      {{ __('common.nav_change_password') }}
                  </a>
              </div>
              <div class="py-1.5 border-t border-slate-100">
                  <form method="POST" action="{{ route('client.logout') }}" class="block w-full">
                      @csrf
                      <button type="submit" class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-red-600 transition-colors hover:bg-red-50">
                          <svg class="h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                          {{ __('common.nav_logout') }}
                      </button>
                  </form>
              </div>
          </div>
        </div>
      @else
        <a href="/login" class="px-3 text-sm font-semibold text-slate-600 transition hover:text-[#0361fc]">{{ __('common.nav_login') }}</a>
        <a href="https://wa.me/6281334455616" class="rounded-full border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-[#0361fc]">{{ __('common.nav_whatsapp') }}</a>
        <a href="#search" class="rounded-full bg-[#0361fc] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">{{ __('common.nav_check_visa') }}</a>
      @endauth
    </div>

    <button id="menuBtn" type="button" aria-label="Toggle mobile menu" aria-expanded="false" aria-controls="mobileMenu"
      class="mobile-menu-btn flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white/90 text-slate-700 shadow-sm transition duration-300 hover:border-slate-300 hover:text-[#0361fc] md:hidden">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path class="burger-line burger-line-top" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14" />
        <path class="burger-line burger-line-middle" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
        <path class="burger-line burger-line-bottom" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17h14" />
      </svg>
    </button>
  </div>

  <div id="mobileMenu" class="mobile-menu-panel absolute inset-x-0 top-full border-t border-slate-200/80 bg-white/96 shadow-2xl backdrop-blur md:hidden">
    <nav class="mx-auto flex max-h-[calc(100vh-4.5rem)] max-w-5xl flex-col gap-2 overflow-y-auto px-4 py-4">
      @auth
        <div class="mb-4 flex items-center gap-3 rounded-2xl bg-slate-50 p-3">
          <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-orange-500 text-sm font-bold text-white">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
          </div>
          <div class="flex min-w-0 flex-col">
            <span class="truncate text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</span>
            <span class="text-xs text-slate-500">
              @if(Auth::user()->hasRole('admin'))
                {{ __('common.administrator') }}
              @elseif(Auth::user()->hasRole('staff'))
                {{ __('common.staff') }}
              @else
                {{ __('common.personal_account') }}
              @endif
            </span>
          </div>
        </div>
      @endauth

      {{-- Mobile Language Toggle --}}
      <div class="flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-slate-50 p-1 mb-2">
        <a href="{{ route('lang.switch', 'id') }}"
           class="flex-1 text-center rounded-full py-2 text-xs font-bold transition {{ $locale === 'id' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500' }}">
          🇮🇩 Bahasa Indonesia
        </a>
        <a href="{{ route('lang.switch', 'en') }}"
           class="flex-1 text-center rounded-full py-2 text-xs font-bold transition {{ $locale === 'en' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500' }}">
          🇬🇧 English
        </a>
      </div>

      <div class="grid gap-3 pt-2">
        @auth
          <a href="{{ route('client.dashboard') }}" class="mobile-nav-link rounded-full border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-[#0361fc]">
            {{ __('common.nav_dashboard') }}
          </a>
          @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('staff'))
          <a href="/admin" class="mobile-nav-link rounded-full border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-[#0361fc]">
            {{ __('common.nav_admin_dashboard') }}
          </a>
          @endif
          <a href="{{ route('client.profile.edit') }}" class="mobile-nav-link rounded-full border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-[#0361fc]">
            {{ __('common.nav_edit_profile') }}
          </a>
          <a href="{{ route('client.profile.edit') }}" class="mobile-nav-link rounded-full border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-[#0361fc]">
            {{ __('common.nav_change_password') }}
          </a>
          <form method="POST" action="{{ route('client.logout') }}" class="block w-full">
            @csrf
            <button type="submit" class="mobile-nav-link w-full rounded-full border border-red-200 bg-red-50 px-4 py-3 text-center text-sm font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-100 hover:text-red-700">
                {{ __('common.nav_logout') }}
            </button>
          </form>
        @else
          <a href="/login" class="mobile-nav-link rounded-full border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-[#0361fc]">
            {{ __('common.nav_login') }}
          </a>
          <a href="https://wa.me/6281334455616" class="mobile-nav-link rounded-full border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-[#0361fc]">
            {{ __('common.nav_whatsapp') }}
          </a>
          <a href="#search" class="mobile-nav-link rounded-full bg-[#0361fc] px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-blue-700">
            {{ __('common.nav_check_visa') }}
          </a>
        @endauth
      </div>
    </nav>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const userBtn = document.getElementById('userMenuBtn');
    const dropdown = document.getElementById('userDropdown');

    if (userBtn && dropdown) {
      const closeDropdown = () => {
        dropdown.classList.remove('opacity-100', 'translate-y-0');
        dropdown.classList.add('opacity-0', 'translate-y-1', 'pointer-events-none');
        setTimeout(() => {
          if (dropdown.classList.contains('opacity-0')) {
            dropdown.classList.add('hidden');
          }
        }, 200);
      };

      const openDropdown = () => {
        dropdown.classList.remove('hidden');
        void dropdown.offsetWidth;
        dropdown.classList.remove('opacity-0', 'translate-y-1', 'pointer-events-none');
        dropdown.classList.add('opacity-100', 'translate-y-0');
      };

      userBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (dropdown.classList.contains('hidden') || dropdown.classList.contains('opacity-0')) {
          openDropdown();
        } else {
          closeDropdown();
        }
      });

      document.addEventListener('click', (e) => {
        if (!userBtn.contains(e.target) && !dropdown.contains(e.target) && !dropdown.classList.contains('hidden')) {
          closeDropdown();
        }
      });
    }
  });
</script>
