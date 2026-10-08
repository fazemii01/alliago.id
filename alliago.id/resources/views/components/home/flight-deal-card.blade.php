@props([
    'destination' => 'Bali',
    'iata' => 'DPS',
    'price' => 'Mulai IDR 942.170',
    'origin' => 'Jakarta ke',
    'gradient' => 'linear-gradient(180deg, #0D9488 0%, #0F766E 60%, #0B192C 100%)',
    'vectorType' => 'bali',
    'url' => route('flights.index')
])

<a href="{{ $url }}"
   class="group relative box-border w-[243px] shrink-0 h-[260px] shadow-[0px_4px_12px_rgba(0,0,0,0.06)] hover:shadow-[0px_12px_28px_rgba(0,0,0,0.18)] flex flex-col justify-between items-start p-[18px_16px] rounded-[20px] overflow-hidden transition-all duration-300 hover:-translate-y-1 block"
   style="background: {{ $gradient }}; background-repeat: no-repeat; background-size: 100% 100%;">
   
    {{-- Origin Badge --}}
    <div class="relative z-10 w-fit h-[24px] flex items-center px-[10px] bg-black/25 backdrop-blur-xs rounded-[12px]">
        <span class="text-[11px] text-white font-['Outfit',sans-serif] font-medium whitespace-nowrap">
            {{ $origin }}
        </span>
    </div>

    {{-- Bottom Destination Info --}}
    <div class="relative z-10 w-fit flex flex-col gap-[8px] items-start">
        <h4 class="text-[20px] text-white font-['Outfit',sans-serif] font-bold leading-tight whitespace-nowrap group-hover:text-amber-200 transition-colors">
            {{ $destination }}
        </h4>
        <div class="w-fit flex items-center px-[10px] py-[6px] bg-white rounded-[10px] shadow-sm">
            <span class="text-[12px] text-[#FE6A00] font-['Outfit',sans-serif] font-bold whitespace-nowrap">
                {{ $price }}
            </span>
        </div>
    </div>

    {{-- IATA Code Badge (Top Right) --}}
    <div class="absolute right-[16px] top-[18px] z-10 w-fit h-[24px] flex items-center px-[8px] bg-white/20 backdrop-blur-xs rounded-[12px]">
        <span class="text-[11px] text-white font-['Outfit',sans-serif] font-bold whitespace-nowrap tracking-wide">
            {{ $iata }}
        </span>
    </div>

    {{-- Scenic Vector Artwork Overlays (1:1 from alliago.pen) --}}
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
        @if ($vectorType === 'lombok')
            <svg viewBox="0 0 200 120" preserveAspectRatio="none" class="w-[200px] h-[120px] absolute left-[20px] top-[60px] overflow-visible">
                <path d="M0 120l70-90 30 15 40-30 60 105z" fill="#ffffff1a"></path>
            </svg>
            <svg viewBox="0 0 60 90" preserveAspectRatio="none" class="w-[60px] h-[90px] absolute left-[170px] top-[80px] overflow-visible">
                <path d="M30 90q-5-40 0-70-15-5-30 5 25-15 30-5 5-10 25-5-20 0-25 5 0 30 5 70z" fill="#ffffff24"></path>
            </svg>
        @elseif ($vectorType === 'bali')
            <svg viewBox="0 0 150 120" preserveAspectRatio="none" class="w-[150px] h-[120px] absolute left-[45px] top-[65px] overflow-visible">
                <path d="M35 120l0-80 10 0 0-15 10 0 0-15 10-10 0 120m20 0l0-120 10 10 0 15 10 0 0 15 10 0 0 80z" fill="#ffffff1f"></path>
            </svg>
            <div class="w-[50px] h-[50px] absolute left-[85px] top-[50px] bg-[#fef08a33] rounded-full"></div>
        @elseif ($vectorType === 'yogyakarta')
            <svg viewBox="0 0 160 130" preserveAspectRatio="none" class="w-[160px] h-[130px] absolute left-[40px] top-[55px] overflow-visible">
                <path d="M80 0l8 35 17 20-7 20 17 20-7 20 17 15-90 0 17-15-7-20 17-20-7-20 17-20z" fill="#ffffff1f"></path>
            </svg>
        @elseif ($vectorType === 'labuan-bajo')
            <svg viewBox="0 0 240 110" preserveAspectRatio="none" class="w-[243px] h-[110px] absolute left-0 top-[80px] overflow-visible">
                <path d="M0 110l0-50q50-40 100 5t80-25 60 30l0 40z" fill="#ffffff1a"></path>
            </svg>
            <svg viewBox="0 0 70 60" preserveAspectRatio="none" class="w-[70px] h-[60px] absolute left-[140px] top-[90px] overflow-visible">
                <path d="M35 10l13 25-13 0z m-15 10l12 18-12 0z m-10 22l45 0q7 6-5 8l-35 0z" fill="#ffffff29"></path>
            </svg>
        @elseif ($vectorType === 'surabaya')
            <svg viewBox="0 0 180 120" preserveAspectRatio="none" class="w-[180px] h-[120px] absolute left-[30px] top-[65px] overflow-visible">
                <path d="M85 0l10 0 10 120-30 0z m5 20l-60 90m60-70l-45 70m45-90l60 90m-60-70l45 70" fill="none" stroke="#ffffff24" stroke-width="2" vector-effect="non-scaling-stroke"></path>
            </svg>
        @elseif ($vectorType === 'singapore')
            <svg viewBox="0 0 150 120" preserveAspectRatio="none" class="w-[150px] h-[120px] absolute left-[45px] top-[65px] overflow-visible">
                <path d="M30 50l8 70m27-72l8 72m27-74l8 74m-93-78q55-10 110 3z" fill="none" stroke="#ffffff29" stroke-width="3" vector-effect="non-scaling-stroke"></path>
            </svg>
        @elseif ($vectorType === 'bangkok')
            <svg viewBox="0 0 150 130" preserveAspectRatio="none" class="w-[150px] h-[130px] absolute left-[45px] top-[60px] overflow-visible">
                <path d="M75 0l7 30 13 20-5 20 15 20-7 20 17 15-80 0 17-15-7-20 15-20-5-20 13-20z" fill="#ffffff21"></path>
            </svg>
        @elseif ($vectorType === 'tokyo')
            <svg viewBox="0 0 160 120" preserveAspectRatio="none" class="w-[160px] h-[120px] absolute left-[40px] top-[65px] overflow-visible">
                <path d="M10 120l60-90 20 0 60 90z" fill="#ffffff1f"></path>
            </svg>
            <svg viewBox="0 0 90 40" preserveAspectRatio="none" class="w-[90px] h-[40px] absolute left-[75px] top-[75px] overflow-visible">
                <path d="M25 10l20-10 20 10q-20 8-40 0z" fill="#ffffff40"></path>
            </svg>
        @elseif ($vectorType === 'seoul')
            <svg viewBox="0 0 120 130" preserveAspectRatio="none" class="w-[120px] h-[130px] absolute left-[60px] top-[55px] overflow-visible">
                <path d="M60 0l1 40 11 5-11 5 0 75-2 0 0-75-11-5 11-5z" fill="#ffffff26"></path>
            </svg>
        @elseif ($vectorType === 'sydney')
            <svg viewBox="0 0 170 100" preserveAspectRatio="none" class="w-[170px] h-[100px] absolute left-[35px] top-[75px] overflow-visible">
                <path d="M20 100q25-60 50 0m-15 0q30-75 60 0m-20 0q30-55 55 0" fill="none" stroke="#ffffff29" stroke-width="3" vector-effect="non-scaling-stroke"></path>
            </svg>
        @endif
    </div>
</a>

