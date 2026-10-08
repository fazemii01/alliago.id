@php
    $locale = app()->getLocale();
@endphp

<header class="fixed inset-x-0 top-0 z-[100] font-['Outfit',sans-serif]">
    {{-- Top Utility & Announcement Bar (36px) --}}
    <div style="background-color: #001D44;" class="border-b border-blue-900/50 text-blue-200 text-xs">
        <div class="mx-auto flex h-9 max-w-7xl items-center justify-between px-4 sm:px-6">
            {{-- Left: Announcement & Support Link --}}
            {{-- Left: Announcement & Support Link --}}
            <div class="flex items-center gap-3 min-w-0">
                <span class="inline-flex items-center gap-1.5 font-medium text-slate-200 truncate">
                    <svg class="h-3.5 w-3.5 text-[#93C5FD] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="hidden sm:inline truncate">Layanan Resmi Tiket Pesawat, Ferry & Asistensi Visa</span>
                    <span class="inline sm:hidden truncate">AlliaGo Travel & Visa</span>
                </span>
                <span class="hidden md:inline text-blue-400">|</span>
                <a href="https://wa.me/6281334455616" target="_blank" rel="noopener noreferrer" class="hidden md:inline-flex items-center gap-1 text-[#FE6A00] font-semibold hover:underline transition-colors">
                    <span>Hubungi Kami</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            {{-- Right: Help, Orders, Visa Services, Currency & Language --}}
            <div class="flex items-center gap-3 sm:gap-5 shrink-0">
                <a href="{{ route('pages.faq') }}" class="home-topbar-link hidden sm:inline-block transition-colors">
                    Pusat bantuan
                </a>
                <a href="{{ route('client.dashboard') }}" class="home-topbar-link hidden sm:inline-block transition-colors">
                    Pesanan
                </a>
                <a href="{{ route('visa.index') }}" class="home-topbar-link hidden md:inline-block transition-colors">
                    Layanan Visa
                </a>
                
                {{-- Dual Currency Rate Badge --}}
                <a href="{{ route('pages.currency_rates') }}" style="background-color: rgba(255, 255, 255, 0.12); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2);" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-bold hover:bg-white/20 transition-colors whitespace-nowrap shrink-0">
                    <span>IDR / RM</span>
                    <svg class="h-3 w-3 text-blue-200 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>

                {{-- Language Switcher (No Emojis) --}}
                <div class="flex items-center rounded-full bg-blue-950 border border-blue-800/60 p-0.5 text-[10px] font-bold shrink-0">
                    <a href="{{ route('lang.switch', 'id') }}" 
                       class="rounded-full px-2 py-0.5 transition-all {{ $locale === 'id' ? 'bg-[#FE6A00] text-white shadow-sm' : 'text-blue-300 hover:text-white' }}">
                        ID
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" 
                       class="rounded-full px-2 py-0.5 transition-all {{ $locale === 'en' ? 'bg-[#FE6A00] text-white shadow-sm' : 'text-blue-300 hover:text-white' }}">
                        EN
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Navbar (72px) --}}
    <div style="background-color: #00275A;" class="border-b border-blue-800/40 shadow-lg backdrop-blur-md"
         x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
        <div class="mx-auto flex h-[72px] max-w-7xl items-center justify-between px-4 sm:px-6">
            {{-- Brand Logo --}}
            <a href="/" class="flex items-center gap-3 group">
                <img src="/images/alliago-logo.jpeg" alt="AlliaGo" class="h-10 w-10 rounded-xl object-cover ring-2 ring-white/10 group-hover:scale-105 transition-transform duration-300">
                <div class="flex flex-col">
                    <span class="text-xl font-extrabold tracking-tight text-white leading-none">
                        ALLIAGO<span class="text-[#FE6A00]">.ID</span>
                    </span>
                    <span style="color: #BFDBFE !important;" class="text-[10px] font-semibold uppercase tracking-[0.2em] mt-1">
                        Travel & Visa Assistance
                    </span>
                </div>
            </a>

            {{-- Desktop Navigation Menu --}}
            <nav class="hidden lg:flex items-center gap-7 text-sm font-semibold">
                <a href="{{ route('flights.index') }}" class="home-header-link flex items-center gap-1.5 transition-colors {{ request()->routeIs('flights.*') ? 'text-[#FE6A00] !important' : '' }}">
                    <span>Tiket Pesawat</span>
                </a>
                <a href="{{ route('ferry.index') }}" class="home-header-link flex items-center gap-1.5 transition-colors {{ request()->routeIs('ferry.*') ? 'text-[#FE6A00] !important' : '' }}">
                    <span>Tiket Ferry</span>
                </a>
                <a href="{{ route('visa.index') }}" class="home-header-link flex items-center gap-1.5 transition-colors {{ request()->routeIs('visa.*') ? 'text-[#FE6A00] !important' : '' }}">
                    <span>Layanan Visa</span>
                </a>
                <a href="#services" class="home-header-link flex items-center gap-1.5 transition-colors">
                    <span>Hotel</span>
                    <span class="rounded bg-emerald-500/20 px-1.5 py-0.5 text-[9px] uppercase font-bold text-emerald-300">Soon</span>
                </a>
                <a href="#packages" class="home-header-link flex items-center gap-1.5 transition-colors">
                    <span>Paket Tour</span>
                    <span class="rounded bg-emerald-500/20 px-1.5 py-0.5 text-[9px] uppercase font-bold text-emerald-300">Hemat</span>
                </a>
                <a href="{{ route('pages.currency_rates') }}" class="home-header-link transition-colors {{ request()->routeIs('pages.currency_rates') ? 'text-[#FE6A00] !important' : '' }}">
                    <span>Cek Kurs</span>
                </a>
            </nav>

            {{-- Auth Action Controls --}}
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    {{-- Authenticated User Menu (1:1 with user design) --}}
                    <div class="relative" @click.away="userMenuOpen = false">
                        <button @click="userMenuOpen = !userMenuOpen" 
                                type="button" 
                                class="flex items-center gap-2 rounded-full border border-slate-200 py-1 pl-1 pr-2.5 transition hover:border-slate-300 bg-white shadow-xs text-slate-900">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#FE6A00] text-xs font-bold text-white shadow-xs shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex flex-col items-start text-left leading-tight">
                                <span class="text-sm font-semibold leading-none text-slate-900 whitespace-nowrap">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] text-slate-500 mt-0.5 whitespace-nowrap">
                                    @if(Auth::user()->hasRole('admin'))
                                        Administrator
                                    @elseif(Auth::user()->hasRole('staff'))
                                        Staff
                                    @else
                                        Akun Pribadi
                                    @endif
                                </span>
                            </div>
                            <svg class="ml-1 h-4 w-4 text-slate-400 transition-transform shrink-0" :class="userMenuOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown Card --}}
                        <div x-show="userMenuOpen" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             style="display: none;"
                             class="absolute right-0 mt-2 w-64 origin-top-right rounded-2xl border border-slate-200/80 bg-white/98 backdrop-blur-md p-2 shadow-2xl ring-1 ring-slate-900/5 focus:outline-none z-50 text-slate-800">
                            
                            {{-- Header: Avatar + Name + Role --}}
                            <div class="flex items-center gap-3 border-b border-slate-100 px-3 pb-3 pt-2">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-orange-400 to-[#FE6A00] text-base font-bold text-white shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="flex min-w-0 flex-col">
                                    <span class="truncate text-sm font-bold text-slate-900">{{ Auth::user()->name }}</span>
                                    <span class="text-[11px] font-medium text-slate-500">
                                        @if(Auth::user()->hasRole('admin'))
                                            Administrator
                                        @elseif(Auth::user()->hasRole('staff'))
                                            Staff
                                        @else
                                            Akun Pribadi
                                        @endif
                                    </span>
                                </div>
                            </div>

                            {{-- Navigation Links --}}
                            <div class="py-1.5">
                                <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 hover:text-[#0361fc]">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    <span>Dashboard &amp; Dokumen</span>
                                </a>
                                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('staff'))
                                    <a href="/admin" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 hover:text-[#0361fc]">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>Admin Dashboard</span>
                                    </a>
                                @endif
                            </div>

                            {{-- Settings Group --}}
                            <div class="py-1.5 border-t border-slate-100">
                                <div class="px-3 pb-2 pt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">PENGATURAN</div>
                                <a href="{{ route('client.profile.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 hover:text-[#0361fc]">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    <span>Ubah Profil</span>
                                </a>
                                <a href="{{ route('client.profile.edit') }}#password" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 hover:text-[#0361fc]">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Ubah Password</span>
                                </a>
                            </div>

                            {{-- Logout Group --}}
                            <div class="py-1.5 border-t border-slate-100">
                                <form method="POST" action="{{ route('client.logout') }}" class="block w-full">
                                    @csrf
                                    <button type="submit" class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-red-600 transition-colors hover:bg-red-50">
                                        <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Guest Buttons --}}
                    <a href="{{ route('client.login') }}" 
                       style="background-color: transparent; border: 1.5px solid rgba(255, 255, 255, 0.35); color: #ffffff !important;"
                       class="rounded-full px-5 py-2 text-xs sm:text-sm font-semibold backdrop-blur-md transition-all hover:bg-white/10 hover:border-white/50">
                        Masuk
                    </a>
                    <a href="{{ route('client.register') }}" 
                       style="background-color: #FE6A00;"
                       class="rounded-full px-5 py-2 text-xs sm:text-sm font-bold text-white shadow-lg shadow-orange-500/30 hover:bg-[#E05D00] transition-all">
                        Daftar
                    </a>
                @endauth
            </div>

            {{-- Mobile Hamburger Toggle Button --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    type="button" 
                    class="lg:hidden flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white hover:bg-white/20 transition-colors"
                    aria-label="Toggle navigation">
                <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" style="display: none;" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Mobile Drawer Panel --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             style="display: none; background-color: #001D44;"
             class="lg:hidden border-b border-blue-900/60 px-4 py-5 space-y-3">
            <a href="{{ route('flights.index') }}" class="block rounded-xl px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                Tiket Pesawat
            </a>
            <a href="{{ route('ferry.index') }}" class="block rounded-xl px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                Tiket Ferry
            </a>
            <a href="{{ route('visa.index') }}" class="block rounded-xl px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                Layanan Visa
            </a>
            <a href="#services" class="block rounded-xl px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                Hotel (Soon)
            </a>
            <a href="#packages" class="block rounded-xl px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                Paket Tour
            </a>
            <a href="{{ route('pages.currency_rates') }}" class="block rounded-xl px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                Cek Kurs IDR / RM
            </a>
            <div class="border-t border-blue-800/40 pt-3 space-y-2">
                @auth
                    <div class="flex items-center gap-3 rounded-2xl bg-white/10 p-3 text-white">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FE6A00] text-sm font-bold text-white shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex min-w-0 flex-col text-left">
                            <span class="truncate text-sm font-bold leading-tight">{{ Auth::user()->name }}</span>
                            <span class="text-[11px] text-blue-200 mt-0.5">
                                @if(Auth::user()->hasRole('admin'))
                                    Administrator
                                @elseif(Auth::user()->hasRole('staff'))
                                    Staff
                                @else
                                    Akun Pribadi
                                @endif
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('client.dashboard') }}" class="block rounded-xl px-4 py-2.5 text-sm font-medium text-slate-200 hover:bg-white/10 transition-colors">
                        Dashboard &amp; Dokumen
                    </a>
                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('staff'))
                        <a href="/admin" class="block rounded-xl px-4 py-2.5 text-sm font-medium text-slate-200 hover:bg-white/10 transition-colors">
                            Admin Dashboard
                        </a>
                    @endif
                    <a href="{{ route('client.profile.edit') }}" class="block rounded-xl px-4 py-2.5 text-sm font-medium text-slate-200 hover:bg-white/10 transition-colors">
                        Ubah Profil &amp; Password
                    </a>
                    <form method="POST" action="{{ route('client.logout') }}" class="pt-1">
                        @csrf
                        <button type="submit" class="w-full text-left rounded-xl px-4 py-2.5 text-sm font-bold text-rose-300 hover:bg-white/10 transition-colors">
                            Keluar
                        </button>
                    </form>
                @else
                    <div class="flex items-center justify-between gap-3 pt-1">
                        <a href="{{ route('client.login') }}" class="block w-1/2 text-center rounded-xl bg-white/10 px-4 py-2.5 text-sm font-bold text-white hover:bg-white/20 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('client.register') }}" style="background-color: #FE6A00;" class="block w-1/2 text-center rounded-xl px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-[#E05D00] transition-colors">
                            Daftar Akun
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
