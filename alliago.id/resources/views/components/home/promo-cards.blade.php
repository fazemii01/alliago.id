{{-- 
    Promo & Info Section Component
    Strict 1:1 match with alliago.pen node bi8Au / Promo & Info Section (Q9sSRx)
--}}
<section id="promos" class="box-border w-full shrink-0 flex flex-col gap-[20px] p-[36px_16px_28px_16px] lg:p-[48px_80px_40px_80px] justify-start items-center relative z-10 font-['Outfit',sans-serif]">
    
    {{-- Header Row (1280px) --}}
    <div class="box-border w-full max-w-[1280px] h-fit shrink-0 flex flex-row justify-between items-center gap-3">
        <h2 class="text-[20px] sm:text-[24px] text-[#0A2540] font-bold text-left leading-tight">
            Promo &amp; Penawaran Eksklusif AlliaGo
        </h2>
        
        {{-- Carousel Arrows --}}
        <div class="box-border w-fit shrink-0 h-fit flex flex-row gap-[10px] items-center">
            <button type="button"
                    @click="$refs.promoRow.scrollBy({ left: -340, behavior: 'smooth' })"
                    class="box-border w-[38px] shrink-0 h-[38px] shadow-[0px_2px_6px_rgba(0,0,0,0.08)] flex justify-center items-center bg-white border border-slate-200 rounded-[19px] hover:bg-slate-50 transition-colors"
                    aria-label="Promo Sebelumnya">
                <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[18px] h-[18px] fill-slate-700">
                    <path d="M8.59619 2.93945q-0.08545 0.02734-0.19482 0.1128-0.14014 0.12646-0.53321 0.51611l-1.28857 1.2749q-1.81836 1.82178-1.86279 1.90723-0.04102 0.08203-0.04102 0.24951 0 0.16748 0.04785 0.25977 0.05127 0.08887 1.8628 1.9038 1.81494 1.81152 1.9038 1.8628 0.09229 0.04785 0.2461 0.04785 0.15381 0 0.23584-0.03418 0.08545-0.0376 0.18115-0.1333 0.09912-0.09912 0.1333-0.18115 0.0376-0.08545 0.0376-0.23926 0-0.15381-0.04443-0.23584-0.04102-0.08545-1.62012-1.66797l-1.58252-1.58252 1.58252-1.58252q1.5791-1.58252 1.62012-1.66455 0.04443-0.08545 0.04443-0.23926 0-0.15381-0.0376-0.23584-0.03418-0.08545-0.11621-0.18457-0.16748-0.15381-0.39307-0.16748-0.14014 0-0.18115 0.01367z"></path>
                </svg>
            </button>
            <button type="button"
                    @click="$refs.promoRow.scrollBy({ left: 340, behavior: 'smooth' })"
                    class="box-border w-[38px] shrink-0 h-[38px] shadow-[0px_2px_6px_rgba(0,0,0,0.08)] flex justify-center items-center bg-white border border-slate-200 rounded-[19px] hover:bg-slate-50 transition-colors"
                    aria-label="Promo Selanjutnya">
                <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[18px] h-[18px] fill-slate-700">
                    <path d="M5.09619 2.93945q-0.25293 0.08545-0.37939 0.30762-0.04102 0.08545-0.04102 0.25293 0 0.16748 0.04102 0.25293 0.04443 0.08203 1.62353 1.66455l1.58252 1.58252-1.58252 1.58252q-1.5791 1.58252-1.62353 1.66797-0.04102 0.08203-0.04102 0.23584 0 0.15381 0.03418 0.23926 0.0376 0.08203 0.1333 0.18115 0.09912 0.0957 0.18115 0.1333 0.08545 0.03418 0.23926 0.03418 0.15381 0 0.24268-0.04785 0.09229-0.05127 1.90381-1.8628 1.81494-1.81494 1.86279-1.9038 0.05127-0.09229 0.05127-0.25977 0-0.16748-0.04443-0.24951-0.04102-0.08545-1.85938-1.90723l-1.44238-1.42871q-0.33496-0.33496-0.46143-0.41699-0.09912-0.07178-0.19824-0.07178l-0.04102 0q-0.14014 0-0.18115 0.01367z"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Promo Cards Row (1280px) --}}
    <div x-ref="promoRow"
         class="box-border w-full max-w-[1280px] h-fit shrink-0 flex flex-col md:flex-row gap-[20px] md:gap-[24px] justify-between items-center overflow-x-auto pb-2 scrollbar-hide">
        
        {{-- Card 1: Liburan Berkelas Batik Air --}}
        <div class="box-border w-full md:w-[410px] shrink-0 min-h-[220px] shadow-[0px_6px_16px_rgba(0,0,0,0.08)] flex flex-col justify-between items-start p-[24px_24px] sm:p-[24px_28px] rounded-[20px] overflow-hidden relative group hover:-translate-y-1 transition-transform duration-300"
             style="background: linear-gradient(-118.217deg, #0A2540 14.645%, #164E87 64.142%, #1E3A8A 85.355%); background-repeat: no-repeat; background-size: 100% 100%;">
            
            <div class="box-border w-full shrink-0 flex flex-col gap-[8px] justify-start items-start relative z-10">
                <div class="box-border w-fit h-[24px] shrink-0 flex items-center px-[10px] bg-white/15 rounded-[12px]">
                    <span class="text-[11px] text-[#FCD34D] font-semibold">
                        Rute Malaysia - Terbang Hemat Batik Air
                    </span>
                </div>
                <h3 class="text-[20px] sm:text-[24px] text-white font-extrabold leading-tight">
                    LIBURAN KE MALAYSIA
                </h3>
                <p class="text-[12px] text-[#BFDBFE] font-normal leading-relaxed">
                    Pilihan tarif terbaik dengan kemudahan bayar IDR &amp; RM
                </p>
            </div>

            <a href="{{ route('flights.index', ['destination' => 'KUL']) }}"
               class="box-border w-fit h-[36px] shrink-0 flex items-center px-[20px] mt-4 bg-[#FE6A00] hover:bg-[#E05D00] text-white rounded-[18px] relative z-10 transition-colors">
                <span class="text-[12px] font-bold whitespace-nowrap">
                    PESAN SEKARANG
                </span>
            </a>

            {{-- Batik Lattice Artwork & Plane Silhouette from alliago.pen --}}
            <div class="box-border w-full h-full absolute inset-0 overflow-hidden pointer-events-none z-0">
                @for ($x = -100; $x <= 400; $x += 50)
                    <svg viewBox="0 0 220 220" preserveAspectRatio="none" class="w-[220px] h-[220px] absolute top-0 overflow-hidden" style="left: {{ $x }}px;">
                        <line x1="0" y1="0" x2="220" y2="220" stroke="#fcd34d14" stroke-width="1" vector-effect="non-scaling-stroke"></line>
                    </svg>
                    <svg viewBox="0 0 1 220" preserveAspectRatio="none" class="w-[1px] h-[220px] absolute top-0 overflow-hidden" style="left: {{ $x + 220 }}px;">
                        <line x1="0" y1="0" x2="-220" y2="220" stroke="#fcd34d14" stroke-width="1" vector-effect="non-scaling-stroke"></line>
                    </svg>
                @endfor
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-[140px] h-[140px] absolute right-[-20px] top-[30px] overflow-hidden">
                    <path d="M20 80q30-20 50-50l25-20-15 30 10 30-30-5-20 20z" fill="#ffffff14"></path>
                </svg>
            </div>
        </div>

        {{-- Card 2: Thai Lion Air Bangkok / Ferry Hemat --}}
        <div class="box-border w-full md:w-[410px] shrink-0 min-h-[220px] shadow-[0px_6px_16px_rgba(0,0,0,0.08)] flex flex-col justify-between items-start p-[24px_24px] sm:p-[24px_28px] rounded-[20px] overflow-hidden relative group hover:-translate-y-1 transition-transform duration-300"
             style="background: linear-gradient(-118.217deg, #003366 14.645%, #0284C7 71.213%, #38BDF8 85.355%); background-repeat: no-repeat; background-size: 100% 100%;">
            
            <div class="box-border w-full shrink-0 flex flex-col gap-[8px] justify-start items-start relative z-10">
                <div class="box-border w-fit h-[24px] shrink-0 flex items-center px-[10px] bg-white/15 rounded-[12px]">
                    <span class="text-[11px] text-[#FEF08A] font-semibold">
                        Ferry Cepat Batam - Singapura - Johor
                    </span>
                </div>
                <h3 class="text-[20px] sm:text-[22px] text-white font-extrabold leading-tight">
                    TIKET FERRY HEMAT
                </h3>
                <p class="text-[12px] text-[#E0F2FE] font-normal leading-relaxed">
                    Booking ferry internasional praktis &amp; konfirmasi instan
                </p>
            </div>

            <a href="{{ route('ferry.index') }}"
               class="box-border w-fit h-[36px] shrink-0 flex items-center px-[20px] mt-4 bg-[#FE6A00] hover:bg-[#E05D00] text-white rounded-[18px] relative z-10 transition-colors">
                <span class="text-[12px] font-bold whitespace-nowrap">
                    CEK JADWAL
                </span>
            </a>

            {{-- Bangkok / Maritime Artwork from alliago.pen --}}
            <div class="box-border w-full h-full absolute inset-0 overflow-hidden pointer-events-none z-0">
                <svg viewBox="0 0 160 180" preserveAspectRatio="none" class="w-[160px] h-[180px] absolute right-[-20px] top-[40px] overflow-hidden">
                    <path d="M80 0l8 40 17 20-10 20 25 30-15 20 35 50-120 0 35-50-15-20 25-30-10-20 17-20z" fill="#ffffff12"></path>
                </svg>
                <svg viewBox="0 0 320 120" preserveAspectRatio="none" class="w-[320px] h-[120px] absolute left-[50px] top-[80px] overflow-hidden">
                    <path d="M0 100q160-90 320-40" fill="none" stroke="#38bdf840" stroke-width="2" vector-effect="non-scaling-stroke"></path>
                </svg>
            </div>
        </div>

        {{-- Card 3: Batam Aero Technic / Asistensi Visa Resmi --}}
        <div class="box-border w-full md:w-[410px] shrink-0 min-h-[220px] shadow-[0px_6px_16px_rgba(0,0,0,0.08)] flex flex-col justify-between items-start p-[24px_24px] sm:p-[24px_28px] rounded-[20px] overflow-hidden relative group hover:-translate-y-1 transition-transform duration-300"
             style="background: linear-gradient(-118.217deg, #0B1528 14.645%, #1E293B 57.071%, #334155 85.355%); background-repeat: no-repeat; background-size: 100% 100%;">
            
            <div class="box-border w-full shrink-0 flex flex-col gap-[8px] justify-start items-start relative z-10">
                <div class="box-border w-fit h-[24px] shrink-0 flex items-center px-[10px] bg-white/15 rounded-[12px]">
                    <span class="text-[11px] text-[#93C5FD] font-semibold">
                        Layanan Asistensi Visa Resmi AlliaGo
                    </span>
                </div>
                <h3 class="text-[20px] sm:text-[22px] text-white font-extrabold leading-tight">
                    VISA JEPANG &amp; KOREA
                </h3>
                <p class="text-[12px] text-[#94A3B8] font-normal leading-relaxed">
                    Bimbingan persyaratan lengkap dengan proses cepat &amp; transparan
                </p>
            </div>

            <a href="{{ route('visa.index') }}"
               class="box-border w-fit h-[36px] shrink-0 flex items-center px-[20px] mt-4 bg-[#FE6A00] hover:bg-[#E05D00] text-white rounded-[18px] relative z-10 transition-colors">
                <span class="text-[12px] font-bold whitespace-nowrap">
                    KONSULTASI VISA
                </span>
            </a>

            {{-- Aerospace Radar Blueprint Artwork from alliago.pen --}}
            <div class="box-border w-full h-full absolute inset-0 overflow-hidden pointer-events-none z-0">
                <div class="w-[180px] h-[180px] absolute right-[-20px] top-[20px] rounded-full border border-sky-400/20"></div>
                <div class="w-[120px] h-[120px] absolute right-[10px] top-[50px] rounded-full border border-sky-400/25"></div>
                <div class="w-[60px] h-[60px] absolute right-[40px] top-[80px] rounded-full border border-sky-400/30"></div>
                <svg viewBox="0 0 200 1" preserveAspectRatio="none" class="w-[200px] h-[1px] absolute right-0 top-[110px] overflow-hidden">
                    <line x1="0" y1="0" x2="200" y2="0" stroke="#38bdf826" stroke-width="1" vector-effect="non-scaling-stroke"></line>
                </svg>
                <svg viewBox="0 0 1 200" preserveAspectRatio="none" class="w-[1px] h-[200px] absolute right-[80px] top-[10px] overflow-hidden">
                    <line x1="0" y1="0" x2="0" y2="200" stroke="#38bdf826" stroke-width="1" vector-effect="non-scaling-stroke"></line>
                </svg>
            </div>
        </div>
    </div>
</section>
