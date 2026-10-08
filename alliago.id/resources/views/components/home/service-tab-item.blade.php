@props([
    'id' => 'flight',
    'label' => 'Tiket Pesawat',
    'href' => '#',
    'badge' => null,
    'badgeColor' => 'emerald', // emerald | amber | orange
    'icon' => 'plane', // plane | ferry | visa | hotel | tour
    'activeModel' => 'activeTab',
])

@php
    $badgeBg = match($badgeColor) {
        'amber' => 'bg-[#F59E0B]',
        'orange' => 'bg-[#FE6A00]',
        default => 'bg-[#10B981]',
    };
@endphp

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'service-tab-item w-full max-w-[76px] sm:max-w-[88px] flex flex-col justify-end items-center group cursor-pointer select-none text-center']) }}
   @click.prevent="{{ $activeModel }} = '{{ $id }}'"
   style="display: flex; flex-direction: column; align-items: center; justify-content: flex-end;">
    
    {{-- Badge or Vertical Spacer (Keeps all 5 pill baselines perfectly aligned) --}}
    <div class="h-[16px] sm:h-[18px] flex items-center justify-center mb-1 w-full">
        @if($badge)
            <div class="box-border px-1.5 min-[360px]:px-2 py-0.5 {{ $badgeBg }} rounded-[6px] sm:rounded-[9px] shadow-sm flex items-center justify-center">
                <span class="text-[8px] min-[360px]:text-[9px] sm:text-[10px] text-white font-bold whitespace-nowrap leading-none">
                    {{ $badge }}
                </span>
            </div>
        @endif
    </div>

    {{-- Glassmorphic / Solid Active Pill (Dynamically resizing from 38px to 56px) --}}
    <div class="box-border w-[38px] min-[360px]:w-[42px] min-[400px]:w-[48px] sm:w-[54px] md:w-[56px] h-[48px] min-[360px]:h-[54px] min-[400px]:h-[62px] sm:h-[68px] md:h-[72px] shrink-0 flex items-center justify-center rounded-[18px] min-[360px]:rounded-[20px] min-[400px]:rounded-[24px] sm:rounded-[28px] transition-all duration-200"
         :class="{{ $activeModel }} === '{{ $id }}' ? 'bg-[#FE6A00] ring-2 sm:ring-4 ring-[#FE6A00]/30 shadow-lg shadow-orange-500/25 scale-105' : 'bg-white/12 backdrop-blur-md border border-white/20 hover:bg-white/20 hover:border-white/35'">
        
        @if($icon === 'plane')
            <svg viewBox="0 0 24 24" class="w-[18px] h-[18px] min-[360px]:w-[20px] min-[360px]:h-[20px] min-[400px]:w-[22px] min-[400px]:h-[22px] sm:w-[26px] sm:h-[26px] md:w-[28px] md:h-[28px] transition-colors shrink-0"
                 :class="{{ $activeModel }} === '{{ $id }}' ? 'fill-white' : 'fill-white/80 group-hover:fill-white'">
                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
            </svg>
        @elseif($icon === 'ferry')
            <svg class="w-[18px] h-[18px] min-[360px]:w-[20px] min-[360px]:h-[20px] min-[400px]:w-[22px] min-[400px]:h-[22px] sm:w-[24px] sm:h-[24px] md:w-[26px] md:h-[26px] transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 :class="{{ $activeModel }} === '{{ $id }}' ? 'text-white' : 'text-white/80 group-hover:text-white'">
                <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/>
                <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/>
                <path d="M12 10v4"/>
                <path d="M12 2v3"/>
            </svg>
        @elseif($icon === 'visa')
            <svg class="w-[18px] h-[18px] min-[360px]:w-[20px] min-[360px]:h-[20px] min-[400px]:w-[22px] min-[400px]:h-[22px] sm:w-[24px] sm:h-[24px] md:w-[26px] md:h-[26px] transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 :class="{{ $activeModel }} === '{{ $id }}' ? 'text-white' : 'text-white/80 group-hover:text-white'">
                <rect width="18" height="20" x="3" y="2" rx="2"/>
                <line x1="7" x2="17" y1="7" y2="7"/>
                <line x1="7" x2="17" y1="11" y2="11"/>
                <circle cx="12" cy="16" r="1.5" fill="currentColor"/>
            </svg>
        @elseif($icon === 'hotel')
            <svg class="w-[18px] h-[18px] min-[360px]:w-[20px] min-[360px]:h-[20px] min-[400px]:w-[22px] min-[400px]:h-[22px] sm:w-[24px] sm:h-[24px] md:w-[26px] md:h-[26px] transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 :class="{{ $activeModel }} === '{{ $id }}' ? 'text-white' : 'text-white/80 group-hover:text-white'">
                <path d="M18 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/>
                <path d="m9 16 .348-.24c1.465-1.013 3.84-1.013 5.304 0L15 16"/>
                <path d="M8 7h.01"/>
                <path d="M16 7h.01"/>
                <path d="M12 7h.01"/>
                <path d="M12 11h.01"/>
                <path d="M16 11h.01"/>
                <path d="M8 11h.01"/>
                <path d="M10 22v-4h4v4"/>
            </svg>
        @elseif($icon === 'tour')
            <svg class="w-[18px] h-[18px] min-[360px]:w-[20px] min-[360px]:h-[20px] min-[400px]:w-[22px] min-[400px]:h-[22px] sm:w-[24px] sm:h-[24px] md:w-[26px] md:h-[26px] transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 :class="{{ $activeModel }} === '{{ $id }}' ? 'text-white' : 'text-white/80 group-hover:text-white'">
                <circle cx="12" cy="12" r="10"/>
                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
            </svg>
        @else
            {{ $slot }}
        @endif
    </div>

    {{-- Responsive Category Label (Responsive font size with zero truncation overflow) --}}
    <span class="w-full text-[10px] min-[360px]:text-[10.5px] min-[400px]:text-[11.5px] sm:text-[13px] leading-tight tracking-tight mt-1.5 sm:mt-2 transition-colors block"
          :class="{{ $activeModel }} === '{{ $id }}' ? 'text-white font-bold' : 'text-white/80 group-hover:text-white font-medium'">
        {{ $label }}
    </span>
</a>

