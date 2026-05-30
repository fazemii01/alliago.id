<div class="print:hidden fixed bottom-0 inset-x-0 z-[100] border-t border-slate-200 bg-white pb-safe md:hidden shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
    <div class="flex h-16 items-center justify-around px-2">
        <a href="/" class="flex flex-col items-center justify-center gap-1 w-full h-full transition {{ request()->is('/') ? 'text-[#0361fc]' : 'text-slate-500 hover:text-[#0361fc]' }}">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] font-semibold">Beranda</span>
        </a>
        
        <a href="{{ route('visa.index') }}" class="flex flex-col items-center justify-center gap-1 w-full h-full transition {{ request()->routeIs('visa.index') ? 'text-[#0361fc]' : 'text-slate-500 hover:text-[#0361fc]' }}">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-[10px] font-semibold">Visa</span>
        </a>

        <a href="{{ route('flights.index') }}" class="flex flex-col items-center justify-center gap-1 w-full h-full transition {{ request()->routeIs('flights.index') ? 'text-[#0361fc]' : 'text-slate-500 hover:text-[#0361fc]' }}">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5L21 16Z" />
            </svg>
            <span class="text-[10px] font-semibold">{{ __('common.nav_flights') }}</span>
        </a>

        <a href="{{ route('pages.process') }}" class="flex flex-col items-center justify-center gap-1 w-full h-full transition {{ request()->routeIs('pages.process') ? 'text-[#0361fc]' : 'text-slate-500 hover:text-[#0361fc]' }}">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span class="text-[10px] font-semibold">Proses</span>
        </a>

        <a href="{{ route('pages.faq') }}" class="flex flex-col items-center justify-center gap-1 w-full h-full transition {{ request()->routeIs('pages.faq') ? 'text-[#0361fc]' : 'text-slate-500 hover:text-[#0361fc]' }}">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-[10px] font-semibold">FAQ</span>
        </a>
    </div>
</div>
