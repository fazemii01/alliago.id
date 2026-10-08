{{-- 
    Flight Deals Section Component
    Strict 1:1 match with alliago.pen:
    1. Domestic Deals Section (node y5Olv)
    2. International Deals Section (node K6FvT)
    Reusable architecture using <x-home.flight-deal-card />
--}}
<div class="w-full bg-[#F8FAFC] font-['Outfit',sans-serif]">
    
    {{-- ================================================================= --}}
    {{-- 1. Domestic Deals Section (1440px Canvas, 1280px Content Box)      --}}
    {{-- ================================================================= --}}
    <section class="box-border w-full shrink-0 flex flex-col gap-[18px] p-[36px_16px_24px_16px] lg:p-[36px_80px_24px_80px] justify-start items-center relative z-10"
             x-data>
        <div class="box-border w-full max-w-[1280px] h-fit shrink-0 flex flex-col gap-[18px] justify-start items-start">
            
            {{-- Header Row --}}
            <div class="box-border w-full h-fit shrink-0 flex flex-row justify-between items-center">
                <div class="flex flex-col gap-[4px] justify-start items-start">
                    <h2 class="text-[20px] sm:text-[24px] text-[#0A2540] font-bold leading-tight">
                        Jelajahi Indonesia bersama AlliaGo
                    </h2>
                    <p class="text-[13px] text-[#64748B] font-normal">
                        Pilihan tiket pesawat domestik terbaik dengan harga transparan dan konfirmasi cepat
                    </p>
                </div>
                
                {{-- Arrow Buttons --}}
                <div class="hidden sm:flex flex-row gap-[10px] items-center">
                    <button type="button"
                            @click="$refs.domRow.scrollBy({ left: -260, behavior: 'smooth' })"
                            class="w-[36px] h-[36px] flex justify-center items-center bg-white border border-slate-200 rounded-[18px] shadow-xs hover:bg-slate-50 transition-colors"
                            aria-label="Geser ke kiri">
                        <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[18px] h-[18px] fill-slate-700">
                            <path d="M8.59619 2.93945q-0.08545 0.02734-0.19482 0.1128-0.14014 0.12646-0.53321 0.51611l-1.28857 1.2749q-1.81836 1.82178-1.86279 1.90723-0.04102 0.08203-0.04102 0.24951 0 0.16748 0.04785 0.25977 0.05127 0.08887 1.8628 1.9038 1.81494 1.81152 1.9038 1.8628 0.09229 0.04785 0.2461 0.04785 0.15381 0 0.23584-0.03418 0.08545-0.0376 0.18115-0.1333 0.09912-0.09912 0.1333-0.18115 0.0376-0.08545 0.0376-0.23926 0-0.15381-0.04443-0.23584-0.04102-0.08545-1.62012-1.66797l-1.58252-1.58252 1.58252-1.58252q1.5791-1.58252 1.62012-1.66455 0.04443-0.08545 0.04443-0.23926 0-0.15381-0.0376-0.23584-0.03418-0.08545-0.11621-0.18457-0.16748-0.15381-0.39307-0.16748-0.14014 0-0.18115 0.01367z"></path>
                        </svg>
                    </button>
                    <button type="button"
                            @click="$refs.domRow.scrollBy({ left: 260, behavior: 'smooth' })"
                            class="w-[36px] h-[36px] flex justify-center items-center bg-white border border-slate-200 rounded-[18px] shadow-xs hover:bg-slate-50 transition-colors"
                            aria-label="Geser ke kanan">
                        <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[18px] h-[18px] fill-slate-700">
                            <path d="M5.09619 2.93945q-0.25293 0.08545-0.37939 0.30762-0.04102 0.08545-0.04102 0.25293 0 0.16748 0.04102 0.25293 0.04443 0.08203 1.62353 1.66455l1.58252 1.58252-1.58252 1.58252q-1.5791 1.58252-1.62353 1.66797-0.04102 0.08203-0.04102 0.23584 0 0.15381 0.03418 0.23926 0.0376 0.08203 0.1333 0.18115 0.09912 0.0957 0.18115 0.1333 0.08545 0.03418 0.23926 0.03418 0.15381 0 0.24268-0.04785 0.09229-0.05127 1.90381-1.8628 1.81494-1.81494 1.86279-1.9038 0.05127-0.09229 0.05127-0.25977 0-0.16748-0.04443-0.24951-0.04102-0.08545-1.85938-1.90723l-1.44238-1.42871q-0.33496-0.33496-0.46143-0.41699-0.09912-0.07178-0.19824-0.07178l-0.04102 0q-0.14014 0-0.18115 0.01367z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- 5 Domestic Cards Row (1280px) --}}
            <div x-ref="domRow"
                 class="box-border w-full flex flex-row gap-[16px] justify-between items-center overflow-x-auto pb-2 scrollbar-hide">
                <x-home.flight-deal-card
                    destination="Lombok"
                    iata="LOP"
                    price="Mulai IDR 980.000"
                    origin="Jakarta ke"
                    gradient="linear-gradient(180deg, #0284C7 0%, #0369A1 60%, #0B192C 100%)"
                    vectorType="lombok"
                    :url="route('flights.index', ['origin' => 'CGK', 'destination' => 'LOP'])"
                />
                <x-home.flight-deal-card
                    destination="Bali"
                    iata="DPS"
                    price="Mulai IDR 942.170"
                    origin="Jakarta ke"
                    gradient="linear-gradient(180deg, #0D9488 0%, #0F766E 60%, #0B192C 100%)"
                    vectorType="bali"
                    :url="route('flights.index', ['origin' => 'CGK', 'destination' => 'DPS'])"
                />
                <x-home.flight-deal-card
                    destination="Yogyakarta"
                    iata="YIA"
                    price="Mulai IDR 710.000"
                    origin="Jakarta ke"
                    gradient="linear-gradient(180deg, #4F46E5 0%, #4338CA 60%, #0B192C 100%)"
                    vectorType="yogyakarta"
                    :url="route('flights.index', ['origin' => 'CGK', 'destination' => 'YIA'])"
                />
                <x-home.flight-deal-card
                    destination="Labuan Bajo"
                    iata="LBJ"
                    price="Mulai IDR 1.450.000"
                    origin="Jakarta ke"
                    gradient="linear-gradient(180deg, #EA580C 0%, #C2410C 60%, #0B192C 100%)"
                    vectorType="labuan-bajo"
                    :url="route('flights.index', ['origin' => 'CGK', 'destination' => 'LBJ'])"
                />
                <x-home.flight-deal-card
                    destination="Surabaya"
                    iata="SUB"
                    price="Mulai IDR 830.000"
                    origin="Jakarta ke"
                    gradient="linear-gradient(180deg, #2563EB 0%, #1D4ED8 60%, #0B192C 100%)"
                    vectorType="surabaya"
                    :url="route('flights.index', ['origin' => 'CGK', 'destination' => 'SUB'])"
                />
            </div>
        </div>
    </section>

    {{-- ================================================================= --}}
    {{-- 2. International Deals Section (1440px Canvas, 1280px Box)        --}}
    {{-- ================================================================= --}}
    <section class="box-border w-full shrink-0 flex flex-col gap-[18px] p-[24px_16px] lg:p-[24px_80px] justify-start items-center relative z-10"
             x-data>
        <div class="box-border w-full max-w-[1280px] h-fit shrink-0 flex flex-col gap-[18px] justify-start items-start">
            
            {{-- Header Row --}}
            <div class="box-border w-full h-fit shrink-0 flex flex-row justify-between items-center">
                <div class="flex flex-col gap-[4px] justify-start items-start">
                    <h2 class="text-[20px] sm:text-[24px] text-[#0A2540] font-bold leading-tight">
                        Jelajahi Rute Internasional Terbaik
                    </h2>
                    <p class="text-[13px] text-[#64748B] font-normal">
                        Penerbangan dan ferry internasional favorit dengan opsi bayar IDR &amp; RM (Ringgit)
                    </p>
                </div>
                
                {{-- Arrow Buttons --}}
                <div class="hidden sm:flex flex-row gap-[10px] items-center">
                    <button type="button"
                            @click="$refs.intlRow.scrollBy({ left: -260, behavior: 'smooth' })"
                            class="w-[36px] h-[36px] flex justify-center items-center bg-white border border-slate-200 rounded-[18px] shadow-xs hover:bg-slate-50 transition-colors"
                            aria-label="Geser ke kiri">
                        <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[18px] h-[18px] fill-slate-700">
                            <path d="M8.59619 2.93945q-0.08545 0.02734-0.19482 0.1128-0.14014 0.12646-0.53321 0.51611l-1.28857 1.2749q-1.81836 1.82178-1.86279 1.90723-0.04102 0.08203-0.04102 0.24951 0 0.16748 0.04785 0.25977 0.05127 0.08887 1.8628 1.9038 1.81494 1.81152 1.9038 1.8628 0.09229 0.04785 0.2461 0.04785 0.15381 0 0.23584-0.03418 0.08545-0.0376 0.18115-0.1333 0.09912-0.09912 0.1333-0.18115 0.0376-0.08545 0.0376-0.23926 0-0.15381-0.04443-0.23584-0.04102-0.08545-1.62012-1.66797l-1.58252-1.58252 1.58252-1.58252q1.5791-1.58252 1.62012-1.66455 0.04443-0.08545 0.04443-0.23926 0-0.15381-0.0376-0.23584-0.03418-0.08545-0.11621-0.18457-0.16748-0.15381-0.39307-0.16748-0.14014 0-0.18115 0.01367z"></path>
                        </svg>
                    </button>
                    <button type="button"
                            @click="$refs.intlRow.scrollBy({ left: 260, behavior: 'smooth' })"
                            class="w-[36px] h-[36px] flex justify-center items-center bg-white border border-slate-200 rounded-[18px] shadow-xs hover:bg-slate-50 transition-colors"
                            aria-label="Geser ke kanan">
                        <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[18px] h-[18px] fill-slate-700">
                            <path d="M5.09619 2.93945q-0.25293 0.08545-0.37939 0.30762-0.04102 0.08545-0.04102 0.25293 0 0.16748 0.04102 0.25293 0.04443 0.08203 1.62353 1.66455l1.58252 1.58252-1.58252 1.58252q-1.5791 1.58252-1.62353 1.66797-0.04102 0.08203-0.04102 0.23584 0 0.15381 0.03418 0.23926 0.0376 0.08203 0.1333 0.18115 0.09912 0.0957 0.18115 0.1333 0.08545 0.03418 0.23926 0.03418 0.15381 0 0.24268-0.04785 0.09229-0.05127 1.90381-1.8628 1.81494-1.81494 1.86279-1.9038 0.05127-0.09229 0.05127-0.25977 0-0.16748-0.04443-0.24951-0.04102-0.08545-1.85938-1.90723l-1.44238-1.42871q-0.33496-0.33496-0.46143-0.41699-0.09912-0.07178-0.19824-0.07178l-0.04102 0q-0.14014 0-0.18115 0.01367z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- 5 International Cards Row (1280px) --}}
            <div x-ref="intlRow"
                 class="box-border w-full flex flex-row gap-[16px] justify-between items-center overflow-x-auto pb-2 scrollbar-hide">
                <x-home.flight-deal-card
                    destination="Singapore"
                    iata="SIN"
                    price="Mulai IDR 620.000"
                    origin="Jakarta ke"
                    gradient="linear-gradient(180deg, #E11D48 0%, #BE123C 60%, #0B1528 100%)"
                    vectorType="singapore"
                    :url="route('flights.index', ['origin' => 'CGK', 'destination' => 'SIN'])"
                />
                <x-home.flight-deal-card
                    destination="Bangkok"
                    iata="BKK"
                    price="Mulai IDR 1.150.000"
                    origin="Jakarta ke"
                    gradient="linear-gradient(180deg, #D97706 0%, #B45309 60%, #0B1528 100%)"
                    vectorType="bangkok"
                    :url="route('flights.index', ['origin' => 'CGK', 'destination' => 'BKK'])"
                />
                <x-home.flight-deal-card
                    destination="Tokyo Narita"
                    iata="NRT"
                    price="Mulai IDR 3.420.000"
                    origin="Jakarta ke"
                    gradient="linear-gradient(180deg, #0284C7 0%, #0369A1 60%, #0B1528 100%)"
                    vectorType="tokyo"
                    :url="route('flights.index', ['origin' => 'CGK', 'destination' => 'NRT'])"
                />
                <x-home.flight-deal-card
                    destination="Seoul Incheon"
                    iata="ICN"
                    price="Mulai IDR 3.650.000"
                    origin="Jakarta ke"
                    gradient="linear-gradient(180deg, #7C3AED 0%, #6D28D9 60%, #0B1528 100%)"
                    vectorType="seoul"
                    :url="route('flights.index', ['origin' => 'CGK', 'destination' => 'ICN'])"
                />
                <x-home.flight-deal-card
                    destination="Sydney"
                    iata="SYD"
                    price="Mulai IDR 4.180.000"
                    origin="Jakarta ke"
                    gradient="linear-gradient(180deg, #059669 0%, #047857 60%, #0B1528 100%)"
                    vectorType="sydney"
                    :url="route('flights.index', ['origin' => 'CGK', 'destination' => 'SYD'])"
                />
            </div>
        </div>
    </section>
</div>
