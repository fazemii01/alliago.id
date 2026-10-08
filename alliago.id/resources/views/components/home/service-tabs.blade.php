@props([
    'activeModel' => 'activeTab',
    'tabs' => null,
])

@php
    $defaultTabs = [
        [
            'id' => 'flight',
            'label' => 'Tiket Pesawat',
            'href' => route('flights.index'),
            'icon' => 'plane',
            'badge' => null,
        ],
        [
            'id' => 'ferry',
            'label' => 'Tiket Ferry',
            'href' => route('ferry.index'),
            'icon' => 'ferry',
            'badge' => null,
        ],
        [
            'id' => 'visa',
            'label' => 'Layanan Visa',
            'href' => route('visa.index'),
            'icon' => 'visa',
            'badge' => null,
        ],
        [
            'id' => 'hotel',
            'label' => 'Hotel',
            'href' => '#services',
            'icon' => 'hotel',
            'badge' => 'Populer',
            'badgeColor' => 'emerald',
        ],
        [
            'id' => 'tour',
            'label' => 'Paket Tour',
            'href' => '#packages',
            'icon' => 'tour',
            'badge' => 'Hemat',
            'badgeColor' => 'amber',
        ],
    ];

    $items = $tabs ?? $defaultTabs;
@endphp

{{-- 
    Reusable, Auto-Resizing Service Category Tabs Grid
    - On mobile: 5-column grid distributing 100% width evenly, pills shrink smoothly down to 38px/42px.
    - On tablet & desktop: expands to 56px x 72px pill design matching alliago.pen.
    - Zero horizontal cut-off across all screen sizes (320px - 1440px+).
--}}
<div {{ $attributes->merge(['class' => 'box-border w-full max-w-[680px] mx-auto px-1 min-[360px]:px-2 sm:px-4 grid grid-cols-5 gap-1 min-[360px]:gap-2 sm:gap-6 md:gap-7 justify-items-center items-end relative z-20']) }}>
    @foreach($items as $tab)
        <x-home.service-tab-item
            :id="$tab['id']"
            :label="$tab['label']"
            :href="$tab['href']"
            :badge="$tab['badge'] ?? null"
            :badgeColor="$tab['badgeColor'] ?? 'emerald'"
            :icon="$tab['icon'] ?? 'plane'"
            :activeModel="$activeModel"
        />
    @endforeach
</div>

