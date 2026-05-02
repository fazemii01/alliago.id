@props(['countries' => collect()])

@php
$visaTypes = [
    ['icon' => '🗺️', 'name' => 'Visa Turis'],
    ['icon' => '💼', 'name' => 'Visa Pekerja'],
    ['icon' => '🎓', 'name' => 'Visa Pelajar'],
    ['icon' => '➕', 'name' => 'Add-ons visa'],
    ['icon' => '⚏', 'name' => 'Layanan lainnya'],
];

// Chunk countries into rows of 7–8 for marquee strips
$rows = $countries->chunk(ceil($countries->count() / 5))->values();
$speeds = ['35s', '40s', '30s', '38s', '45s'];
$directions = ['marquee', 'marquee-reverse', 'marquee', 'marquee-reverse', 'marquee'];
@endphp

<section class="py-20 px-4 bg-[#fcfcfd] overflow-hidden">
    <div class="container mx-auto text-center mb-10">
        <h2 class="text-3xl md:text-4xl font-bold tracking-[-0.05em] mb-4">Telah mendukung {{ $countries->count() }}+ tipe visa</h2>
        <p class="text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto">
            Kami telah mendukung ratusan jenis visa ke {{ $countries->count() }}+ negara di seluruh dunia — Alliago.id adalah platform visa online paling lengkap di Asia Tenggara
        </p>
    </div>

    <!-- Visa Types Pills -->
    <div class="flex flex-wrap justify-center gap-3 mb-12 max-w-4xl mx-auto">
        @foreach($visaTypes as $type)
        <div class="bg-white border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.04)] rounded-full px-5 py-2.5 flex items-center gap-2 text-sm font-bold text-slate-600 transition duration-300 hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.08)] hover:-translate-y-0.5">
            <span class="text-lg opacity-80">{{ $type['icon'] }}</span>
            <span>{{ $type['name'] }}</span>
        </div>
        @endforeach
    </div>

    <!-- Marquees -->
    <div class="relative max-w-5xl mx-auto">
        <!-- Mask gradients -->
        <div class="absolute inset-y-0 left-0 w-16 md:w-40 bg-gradient-to-r from-[#fcfcfd] to-transparent z-10 pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-16 md:w-40 bg-gradient-to-l from-[#fcfcfd] to-transparent z-10 pointer-events-none"></div>

        <div class="flex flex-col gap-4">
            @foreach($rows as $rowIndex => $rowCountries)
            <div class="flex overflow-hidden group">
                <div class="flex gap-4 group-hover:[animation-play-state:paused] w-max" style="animation: {{ $directions[$rowIndex % 5] }} {{ $speeds[$rowIndex % 5] }} linear infinite;">
                    @for($copy = 0; $copy < 2; $copy++)
                    <div class="flex gap-4 items-center" @if($copy > 0) aria-hidden="true" @endif>
                        @foreach($rowCountries as $country)
                        <div class="bg-white border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] rounded-full px-5 py-2.5 flex items-center gap-2 text-sm font-bold text-slate-700 transition duration-300 hover:-translate-y-1 hover:shadow-md cursor-default">
                            <span class="text-lg">{{ $country->flag_emoji }}</span>
                            <span>{{ $country->name }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endfor
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Approval Rate -->
    <div class="flex justify-center items-center gap-2 mt-12 mb-16">
        <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <path d="m9 12 2 2 4-4"/>
            </svg>
        </div>
        <span class="font-bold text-slate-800 tracking-tight">Approval rate 99%</span>
    </div>

    <!-- Partner Logos -->
    <!-- <div class="flex flex-wrap justify-center items-center gap-8 md:gap-14 opacity-50 grayscale hover:grayscale-0 transition duration-500 max-w-5xl mx-auto px-4">
        <div class="font-bold text-xl tracking-tight text-slate-900">Dipercayai</div>
        <div class="font-bold text-2xl tracking-tighter text-slate-900">!NUS</div>
        <div class="font-bold text-xl tracking-tighter text-slate-800 flex items-center gap-1 leading-none">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 22h20L12 2z"/></svg>
            <div>
                GLOBAL<br/>GATEWAY
            </div>
        </div>
        <div class="font-bold text-2xl tracking-widest text-slate-900">GEOLOG</div>
        <div class="font-bold text-lg italic tracking-tight text-slate-800">looknofurther</div>
        <div class="font-bold text-xl tracking-tight text-slate-900 flex items-center gap-2">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 22h20L12 2z"/></svg>
            <div class="leading-none text-left">
                <span class="text-[10px] block font-bold text-slate-500">Telkom</span>
                <span class="text-lg">University</span>
            </div>
        </div>
        <div class="font-bold text-xl tracking-[0.2em]  text-slate-900">BALIEASY</div>
        <div class="font-bold text-lg tracking-tight text-slate-900 flex items-center gap-2">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            <div class="leading-none text-left">
                <span class="block">Bina Antarbudaya</span>
            </div>
        </div>
    </div> -->
</section>
