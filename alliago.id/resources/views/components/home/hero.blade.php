@props(['countries' => collect(), 'featuredProducts' => collect()])

<section class="box-border w-full shrink-0 flex flex-col gap-[20px] p-[28px_16px_80px_16px] lg:p-[32px_0px_96px_0px] justify-start items-center overflow-hidden relative font-['Outfit',sans-serif] min-h-[640px]"
         style="background: #081838;"
         x-data="{
             activeTab: 'flight',
             tripType: 'one-way',
             cabinClass: 'Ekonomi',
             classOpen: false,
             
             // Airport selection
             originCity: 'Jakarta (CGK)',
             originCode: 'CGK',
             destinationCity: 'Pilih kota / bandara',
             destinationCode: '',
             airportModalOpen: false,
             airportTarget: 'destination', // 'origin' or 'destination'
             airportSearch: '',
             
             airports: [
                 { code: 'CGK', city: 'Jakarta', name: 'Soekarno-Hatta Int', country: 'Indonesia' },
                 { code: 'DPS', city: 'Bali', name: 'I Gusti Ngurah Rai', country: 'Indonesia' },
                 { code: 'SUB', city: 'Surabaya', name: 'Juanda Int', country: 'Indonesia' },
                 { code: 'KNO', city: 'Medan', name: 'Kualanamu Int', country: 'Indonesia' },
                 { code: 'YIA', city: 'Yogyakarta', name: 'Yogyakarta Int', country: 'Indonesia' },
                 { code: 'LOP', city: 'Lombok', name: 'Zainuddin Abdul Madjid', country: 'Indonesia' },
                 { code: 'LBJ', city: 'Labuan Bajo', name: 'Komodo Airport', country: 'Indonesia' },
                 { code: 'PDG', city: 'Padang', name: 'Minangkabau Int', country: 'Indonesia' },
                 { code: 'SIN', city: 'Singapore', name: 'Changi Airport', country: 'Singapura' },
                 { code: 'KUL', city: 'Kuala Lumpur', name: 'KLIA', country: 'Malaysia' },
                 { code: 'BKK', city: 'Bangkok', name: 'Suvarnabhumi', country: 'Thailand' },
                 { code: 'NRT', city: 'Tokyo', name: 'Narita Int', country: 'Jepang' },
                 { code: 'ICN', city: 'Seoul', name: 'Incheon Int', country: 'Korea Selatan' }
             ],
             
             get filteredAirports() {
                 if (!this.airportSearch) return this.airports;
                 const q = this.airportSearch.toLowerCase();
                 return this.airports.filter(a => 
                     a.city.toLowerCase().includes(q) || 
                     a.code.toLowerCase().includes(q) || 
                     a.name.toLowerCase().includes(q)
                 );
             },
             
             openAirportPicker(target) {
                 this.airportTarget = target;
                 this.airportSearch = '';
                 this.airportModalOpen = true;
             },
             
             selectAirport(item) {
                 if (this.airportTarget === 'origin') {
                     this.originCity = `${item.city} (${item.code})`;
                     this.originCode = item.code;
                 } else {
                     this.destinationCity = `${item.city} (${item.code})`;
                     this.destinationCode = item.code;
                 }
                 this.airportModalOpen = false;
             },
             
             swapAirports() {
                 if (!this.destinationCode) return;
                 const tmpCity = this.originCity;
                 const tmpCode = this.originCode;
                 this.originCity = this.destinationCity;
                 this.originCode = this.destinationCode;
                 this.destinationCity = tmpCity;
                 this.destinationCode = tmpCode;
             },
             
             // Calendar state
             calendarOpen: false,
             activeDateField: 'depart',
             departDate: '04 Okt 2026',
             departDateObj: new Date(2026, 9, 4),
             returnDate: '+ Tambah Pulang',
             returnDateObj: null,
             
             openCalendar(field) {
                 this.activeDateField = field;
                 if (field === 'return' && this.tripType === 'one-way') {
                     this.tripType = 'round-trip';
                 }
                 this.calendarOpen = true;
             },
             
             // Passenger state
             passengerModalOpen: false,
             adults: 1,
             children: 0,
             infants: 0,
             
             get passengerSummary() {
                 const total = this.adults + this.children + this.infants;
                 if (this.children === 0 && this.infants === 0) {
                     return `${this.adults} Dewasa · ${this.cabinClass}`;
                 }
                 return `${total} Penumpang · ${this.cabinClass}`;
             },
             
             // Promo code
             promoCodeModalOpen: false,
             promoCode: '',
             appliedPromo: '',
             
             applyPromo() {
                 this.appliedPromo = this.promoCode;
                 this.promoCodeModalOpen = false;
             },
             
             // Search submit
             submitSearch() {
                 const dest = this.destinationCode || 'DPS';
                 const orig = this.originCode || 'CGK';
                 const url = `/flights?origin=${orig}&destination=${dest}&trip=${this.tripType}&class=${this.cabinClass.toLowerCase()}&adults=${this.adults}`;
                 window.location.href = url;
             }
         }">

    {{-- Hero Background Stack: Deep Navy Gradient, Subtle Bali Temple Photo (Temple on Right), Top-Right Warm Glow & Curved Divider --}}
    <div class="box-border w-full h-full absolute inset-0 overflow-hidden pointer-events-none z-0">
        
        {{-- 1. High-Res Bali Temple Photography (Mirrored so pagoda sits gracefully on the right side, calm on the left) --}}
        <div class="box-border w-full h-full absolute inset-0 z-0 bg-cover bg-no-repeat"
             style="background-image: url('https://images.unsplash.com/photo-1544959068-7c75914bf21e?auto=format&fit=crop&w=2400&q=85'); background-position: left 25%; transform: scaleX(-1); transform-origin: center;"></div>

        {{-- 2. Deep Navy Tint Gradient Overlay --}}
        <div class="box-border w-full h-full absolute inset-0 z-[1]"
             style="background: linear-gradient(180deg, rgba(8,24,56,0.92) 0%, rgba(12,38,84,0.78) 45%, rgba(20,50,100,0.55) 100%);"></div>

        {{-- 3. Warm Orange Corner Glow (Top-Right, over navy tint for warmth) --}}
        <div class="box-border w-[540px] h-[540px] absolute right-[-5%] -top-[10%] rounded-full pointer-events-none z-[2]"
             style="background: radial-gradient(circle, rgba(255,106,0,0.45) 0%, rgba(254,106,0,0.18) 45%, transparent 70%); filter: blur(35px);"></div>

        {{-- 4. Faint Continuous Flight Trajectory (Smooth flight arc across the hero, low opacity 0.15) --}}
        <svg viewBox="0 0 1440 280" fill="none" class="box-border w-full h-[280px] absolute left-0 top-[10px] pointer-events-none opacity-15 overflow-visible z-[2]">
            <path d="M-60,180 C360,20 960,15 1500,130" stroke="#FFFFFF" stroke-width="2" stroke-dasharray="8 8" fill="none" vector-effect="non-scaling-stroke"/>
        </svg>

        {{-- 5. Short, Crisp Curved Divider (Replaces foggy gradient; matches exact #F8FAFC page background) --}}
        <div class="hero-divider box-border w-full absolute -bottom-[1px] left-0 pointer-events-none z-10" style="line-height: 0;">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none" class="block w-full h-[60px] sm:h-[80px]">
                <path d="M0,40 C360,90 1080,0 1440,50 L1440,80 L0,80 Z" fill="#F8FAFC"/>
            </svg>
        </div>
    </div>

    {{-- 1. Service Category Tabs --}}
    <div class="box-border w-fit h-fit shrink-0 flex flex-row gap-[16px] sm:gap-[28px] justify-center items-end relative z-20 pt-2">
        
        {{-- Tab 1: Tiket Pesawat (Active) --}}
        <a href="{{ route('flights.index') }}"
           class="box-border w-fit shrink-0 h-fit flex flex-col gap-[8px] justify-start items-center group cursor-pointer"
           @click.prevent="activeTab = 'flight'">
            <div class="box-border w-[56px] h-[72px] shrink-0 flex flex-row justify-center items-center rounded-[28px] transition-all duration-200"
                 :class="activeTab === 'flight' ? 'bg-[#FE6A00] ring-4 ring-[#FE6A00]/30 shadow-lg shadow-orange-500/25 scale-105' : 'bg-white/12 backdrop-blur-md border border-white/20 hover:bg-white/20 hover:border-white/35'">
                <svg viewBox="0 0 24 24" class="w-[28px] h-[28px] transition-colors"
                     :class="activeTab === 'flight' ? 'fill-white' : 'fill-white/80 group-hover:fill-white'">
                    <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
            </div>
            <span class="text-[13px] transition-colors"
                  :class="activeTab === 'flight' ? 'text-white font-bold' : 'text-white/80 group-hover:text-white font-medium'">Tiket Pesawat</span>
        </a>

        {{-- Tab 2: Tiket Ferry --}}
        <a href="{{ route('ferry.index') }}"
           class="box-border w-fit shrink-0 h-fit flex flex-col gap-[8px] justify-start items-center group cursor-pointer"
           @click.prevent="activeTab = 'ferry'">
            <div class="box-border w-[56px] h-[72px] shrink-0 flex flex-row justify-center items-center rounded-[28px] transition-all duration-200"
                 :class="activeTab === 'ferry' ? 'bg-[#FE6A00] ring-4 ring-[#FE6A00]/30 shadow-lg shadow-orange-500/25 scale-105' : 'bg-white/12 backdrop-blur-md border border-white/20 hover:bg-white/20 hover:border-white/35'">
                <svg class="w-[26px] h-[26px] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     :class="activeTab === 'ferry' ? 'text-white' : 'text-white/80 group-hover:text-white'">
                    <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                    <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/>
                    <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/>
                    <path d="M12 10v4"/>
                    <path d="M12 2v3"/>
                </svg>
            </div>
            <span class="text-[13px] transition-colors"
                  :class="activeTab === 'ferry' ? 'text-white font-bold' : 'text-white/80 group-hover:text-white font-medium'">Tiket Ferry</span>
        </a>

        {{-- Tab 3: Layanan Visa --}}
        <a href="{{ route('visa.index') }}"
           class="box-border w-fit shrink-0 h-fit flex flex-col gap-[8px] justify-start items-center group cursor-pointer"
           @click.prevent="activeTab = 'visa'">
            <div class="box-border w-[56px] h-[72px] shrink-0 flex flex-row justify-center items-center rounded-[28px] transition-all duration-200"
                 :class="activeTab === 'visa' ? 'bg-[#FE6A00] ring-4 ring-[#FE6A00]/30 shadow-lg shadow-orange-500/25 scale-105' : 'bg-white/12 backdrop-blur-md border border-white/20 hover:bg-white/20 hover:border-white/35'">
                <svg class="w-[26px] h-[26px] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     :class="activeTab === 'visa' ? 'text-white' : 'text-white/80 group-hover:text-white'">
                    <rect width="18" height="20" x="3" y="2" rx="2"/>
                    <line x1="7" x2="17" y1="7" y2="7"/>
                    <line x1="7" x2="17" y1="11" y2="11"/>
                    <circle cx="12" cy="16" r="1.5" fill="currentColor"/>
                </svg>
            </div>
            <span class="text-[13px] transition-colors"
                  :class="activeTab === 'visa' ? 'text-white font-bold' : 'text-white/80 group-hover:text-white font-medium'">Layanan Visa</span>
        </a>

        {{-- Tab 4: Hotel (Populer badge) --}}
        <div class="box-border w-fit shrink-0 h-fit flex flex-col gap-[8px] justify-start items-center group cursor-pointer"
             @click="activeTab = 'hotel'">
            <div class="box-border w-fit h-fit shrink-0 flex flex-col gap-[4px] justify-start items-center">
                <div class="box-border w-[40px] h-[18px] shrink-0 flex flex-row justify-center items-center bg-[#10B981] rounded-[9px] shadow-sm">
                    <span class="text-[10px] text-white font-bold whitespace-nowrap">Populer</span>
                </div>
                <div class="box-border w-[56px] h-[72px] shrink-0 flex flex-row justify-center items-center rounded-[28px] transition-all duration-200"
                     :class="activeTab === 'hotel' ? 'bg-[#FE6A00] ring-4 ring-[#FE6A00]/30 shadow-lg shadow-orange-500/25 scale-105' : 'bg-white/12 backdrop-blur-md border border-white/20 hover:bg-white/20 hover:border-white/35'">
                    <svg class="w-[26px] h-[26px] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         :class="activeTab === 'hotel' ? 'text-white' : 'text-white/80 group-hover:text-white'">
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
                </div>
            </div>
            <span class="text-[13px] transition-colors"
                  :class="activeTab === 'hotel' ? 'text-white font-bold' : 'text-white/80 group-hover:text-white font-medium'">Hotel</span>
        </div>

        {{-- Tab 5: Paket Tour (Hemat badge) --}}
        <a href="#packages"
           class="box-border w-fit shrink-0 h-fit flex flex-col gap-[8px] justify-start items-center group cursor-pointer"
           @click.prevent="activeTab = 'tour'">
            <div class="box-border w-fit h-fit shrink-0 flex flex-col gap-[4px] justify-start items-center">
                <div class="box-border w-[40px] h-[18px] shrink-0 flex flex-row justify-center items-center bg-[#F59E0B] rounded-[9px] shadow-sm">
                    <span class="text-[10px] text-white font-bold whitespace-nowrap">Hemat</span>
                </div>
                <div class="box-border w-[56px] h-[72px] shrink-0 flex flex-row justify-center items-center rounded-[28px] transition-all duration-200"
                     :class="activeTab === 'tour' ? 'bg-[#FE6A00] ring-4 ring-[#FE6A00]/30 shadow-lg shadow-orange-500/25 scale-105' : 'bg-white/12 backdrop-blur-md border border-white/20 hover:bg-white/20 hover:border-white/35'">
                    <svg class="w-[26px] h-[26px] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         :class="activeTab === 'tour' ? 'text-white' : 'text-white/80 group-hover:text-white'">
                        <circle cx="12" cy="12" r="10"/>
                        <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                    </svg>
                </div>
            </div>
            <span class="text-[13px] transition-colors"
                  :class="activeTab === 'tour' ? 'text-white font-bold' : 'text-white/80 group-hover:text-white font-medium'">Paket Tour</span>
        </a>
    </div>

    {{-- 2. Main Flight Search Card --}}
    <div class="box-border w-full max-w-[1120px] h-fit shrink-0 shadow-[0px_20px_50px_rgba(8,24,56,0.25)] flex flex-col gap-[16px] p-[20px_16px] sm:p-[22px_28px] justify-start items-start bg-white rounded-[32px] relative z-20 border border-slate-100">
        
        {{-- Trip Options Row (Radio Buttons + Class Dropdown) --}}
        <div class="box-border w-full h-fit flex flex-wrap items-center gap-[18px]">
            {{-- Radio: Sekali Jalan --}}
            <label class="flex items-center gap-[8px] cursor-pointer" @click="tripType = 'one-way'">
                <div class="w-[18px] h-[18px] flex items-center justify-center rounded-full border-2 transition-colors"
                     :class="tripType === 'one-way' ? 'border-[#FE6A00]' : 'border-slate-400'">
                    <div class="w-[8px] h-[8px] rounded-full bg-[#FE6A00]" x-show="tripType === 'one-way'"></div>
                </div>
                <span class="text-[14px] font-semibold whitespace-nowrap"
                      :class="tripType === 'one-way' ? 'text-[#0F172A]' : 'text-slate-500'">
                    Sekali Jalan
                </span>
            </label>

            {{-- Radio: Pulang pergi --}}
            <label class="flex items-center gap-[8px] cursor-pointer" @click="tripType = 'round-trip'; if (returnDate === '+ Tambah Pulang') openCalendar('return');">
                <div class="w-[18px] h-[18px] flex items-center justify-center rounded-full border-2 transition-colors"
                     :class="tripType === 'round-trip' ? 'border-[#FE6A00]' : 'border-slate-400'">
                    <div class="w-[8px] h-[8px] rounded-full bg-[#FE6A00]" x-show="tripType === 'round-trip'"></div>
                </div>
                <span class="text-[14px] font-semibold whitespace-nowrap"
                      :class="tripType === 'round-trip' ? 'text-[#0F172A]' : 'text-slate-500'">
                    Pulang pergi
                </span>
            </label>

            <span class="text-[16px] text-slate-300 select-none">•</span>

            {{-- Class Dropdown Menu --}}
            <div class="relative" @click.away="classOpen = false">
                <button type="button" 
                        @click="classOpen = !classOpen"
                        class="flex items-center gap-[6px] text-[14px] font-semibold text-[#0F172A] hover:text-[#FE6A00] transition-colors focus:outline-none">
                    <span x-text="cabinClass"></span>
                    <svg class="w-[14px] h-[14px] text-slate-500 transition-transform duration-200" :class="classOpen ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>

                <div x-cloak x-show="classOpen" 
                     class="absolute left-0 mt-2 w-36 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 z-50">
                    <template x-for="c in ['Ekonomi', 'Bisnis', 'First Class']" :key="c">
                        <button type="button" 
                                @click="cabinClass = c; classOpen = false"
                                class="w-full text-left px-3 py-2 text-[13px] font-medium text-slate-700 hover:bg-orange-50 hover:text-[#FE6A00] transition-colors"
                                :class="cabinClass === c ? 'text-[#FE6A00] font-bold bg-orange-50/60' : ''"
                                x-text="c">
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Search Inputs Row (5 Fields Inline on Desktop with optimized flex spacing) --}}
        <div class="box-border w-full flex flex-col lg:flex-row gap-[8px] items-stretch lg:items-center">
            
            {{-- 1. Field Origin --}}
            <div class="flex-1 min-w-0 lg:min-w-[175px] h-[68px] flex flex-row items-center gap-[8px] px-[10px] sm:px-[12px] py-[10px] bg-[#F8FAFC] border border-slate-200 rounded-[16px] cursor-pointer hover:border-[#FE6A00] transition-colors"
                 @click="openAirportPicker('origin')">
                <div class="w-[36px] h-[36px] shrink-0 rounded-[18px] flex items-center justify-center border border-amber-200"
                     style="background: linear-gradient(180deg, #FEECCB 0%, #FFFFFF 100%);">
                    <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[16px] h-[16px] fill-[#FE6A00]">
                        <path d="M3.65381 2.56348q-0.12646 0.02734-0.2461 0.06152-0.11621 0.03418-0.46826 0.21191-0.34863 0.17432-0.40674 0.22901-0.0957 0.08545-0.1538 0.23242-0.05469 0.14697-0.04102 0.27002 0 0.05811 0.15381 0.37256 0.15381 0.31445 0.62891 1.25439l0.75878 1.53809-0.12646 0.0581q-0.08545 0.04102-0.12647 0.04786-0.04102 0.00684-0.14697 0.00683-0.10596 0-0.14697-0.00683-0.04102-0.00684-0.16748-0.07862-0.22559-0.10938-0.41358-0.15039-0.18799-0.04443-0.42724-0.04443-0.2666 0-0.45459 0.0581-0.18799 0.05469-0.6084 0.26319-0.35205 0.16748-0.45801 0.24609-0.10254 0.0752-0.16064 0.18799-0.08203 0.19824-0.05469 0.36572 0.02734 0.10938 0.64258 1.32959l0.47851 0.93652q0.14014 0.28027 0.22217 0.3794 0.04443 0.05469 0.15381 0.11279 0.07178 0.02734 0.77588 0.15381 0.70752 0.12646 0.90576 0.14014 0.30762 0.01367 0.61524-0.07178 0.11279-0.02734 0.92968-0.42725 0.82031-0.3999 3.95801-1.95166 2.99414-1.4834 3.12744-1.57568 0.1333-0.09229 0.28711-0.24609 0.15381-0.15381 0.23926-0.28028 0.0957-0.15381 0.26318-0.50927 0.1709-0.35889 0.20508-0.48194 0.03418-0.12646 0.03418-0.30761 0-0.18457-0.02734-0.31788-0.02734-0.1333-0.11963-0.31445-0.09229-0.18115-0.17432-0.28027-0.18115-0.22217-0.46142-0.36231-0.11279-0.05811-0.48536-0.16064-0.36914-0.10596-0.52294-0.1333-0.49219-0.08545-0.96729 0.06836-0.15381 0.05811-1.43555 0.68701-1.27832 0.62891-1.37744 0.65625-0.19482 0.07178-0.39306-0.02735-0.08203-0.05469-1.29541-0.95703-1.20996-0.90576-1.33643-0.97412-0.25293-0.15381-0.57422-0.20849-0.32129-0.05811-0.60156 0z"></path>
                    </svg>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[11px] text-slate-500 font-medium whitespace-nowrap">Berangkat dari</span>
                    <span class="text-[14px] sm:text-[15px] text-[#0A2540] font-bold truncate" x-text="originCity">Jakarta (CGK)</span>
                </div>
            </div>

            {{-- Swap Airport Button --}}
            <button type="button"
                    @click="swapAirports()"
                    class="w-[34px] h-[34px] shrink-0 mx-auto lg:mx-0 rounded-full bg-white border border-slate-300 shadow-xs flex items-center justify-center hover:bg-slate-50 hover:border-[#FE6A00] transition-colors cursor-pointer"
                    aria-label="Tukar Bandara">
                <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[15px] h-[15px] fill-[#00275A]">
                    <path d="M4.48096 1.20313q-0.07178 0.01367-0.15039 0.07177-0.0752 0.05469-1.292 1.27149-0.86816 0.88525-1.04589 1.0664-0.17432 0.18115-0.2085 0.27344-0.03418 0.08887-0.03418 0.22217 0 0.1333 0.04785 0.22558 0.05127 0.08887 1.28174 1.32276 0.82715 0.84082 1.04932 1.04931 0.22559 0.2085 0.29394 0.23926 0.23926 0.10938 0.46143 0.01367 0.22559-0.09912 0.32129-0.32128 0.09912-0.22559-0.01026-0.46485-0.03076-0.06836-0.16406-0.20849-0.12988-0.14014-0.57764-0.60157l-0.70068-0.68701 3.96143 0q3.94775 0 4.06054-0.02734 0.16748-0.02734 0.29395-0.15381 0.22217-0.22559 0.16748-0.51953-0.05469-0.29395-0.35205-0.43408l-0.08203-0.02735-8.06299-0.01367 0.78271-0.79639q0.40674-0.39307 0.50244-0.50586 0.15723-0.16748 0.18457-0.23584 0.02734-0.07178 0.02735-0.19824l0-0.02734q0-0.14014-0.04444-0.22901-0.04102-0.09229-0.1333-0.17431-0.08887-0.08545-0.18115-0.11963-0.08887-0.0376-0.20849-0.0376-0.11963 0-0.18799 0.02735z"></path>
                </svg>
            </button>

            {{-- 2. Field Destination --}}
            <div class="flex-1 min-w-0 lg:min-w-[175px] h-[68px] flex flex-row items-center gap-[8px] px-[10px] sm:px-[12px] py-[10px] bg-[#F8FAFC] border border-slate-200 rounded-[16px] cursor-pointer hover:border-[#FE6A00] transition-colors"
                 @click="openAirportPicker('destination')">
                <div class="w-[36px] h-[36px] shrink-0 rounded-[18px] flex items-center justify-center border border-amber-200"
                     style="background: linear-gradient(180deg, #FEECCB 0%, #FFFFFF 100%);">
                    <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[16px] h-[16px] fill-[#FE6A00]">
                        <path d="M6.23096 0.90918q-0.18457 0.05811-0.28028 0.19824-0.04102 0.06836-0.79297 1.56885-0.74854 1.49707-0.76904 1.49023-0.02051-0.00684-0.08545-0.04101-0.06152-0.0376-0.10254-0.06494-0.09912-0.09912-0.11279-0.30762-0.05469-0.65967-0.48877-1.06641-0.11279-0.10938-0.24609-0.18457-0.1333-0.07861-0.45459-0.24951-0.29395-0.13672-0.36573-0.16406-0.06836-0.03076-0.16748-0.04443-0.14014 0-0.24609 0.04443-0.10254 0.04102-0.18799 0.13672-0.08203 0.07178-0.17431 0.25976-0.09229 0.18799-0.52637 1.18262-0.61523 1.37402-0.62891 1.45606-0.05469 0.18115 0.04102 0.37939 0.04443 0.06836 0.16064 0.19482 0.11963 0.12646 0.5127 0.51954 0.61523 0.61523 0.74853 0.70068 0.1333 0.08203 4.02295 2.03027 3.89307 1.94482 4.03321 1.98584 0.31104 0.11279 0.61865 0.1128 0.12305 0 0.55029-0.05469 0.42725-0.05811 0.56738-0.08545 0.29394-0.07178 0.53321-0.27344 0.23926-0.20166 0.37939-0.46826 0.08203-0.18457 0.10938-0.30762 0.03076-0.12646 0.03076-0.32471 0-0.26318-0.06494-0.43066-0.06152-0.1709-0.30078-0.57764-0.18115-0.33496-0.27344-0.45117-0.08887-0.11963-0.18799-0.23242-0.16748-0.16748-0.42725-0.31445-0.25976-0.14697-1.35009-0.69385-1.3877-0.68701-1.45606-0.75537-0.12646-0.11279-0.16748-0.28027-0.01709-0.05811-0.22559-1.6543-0.2085-1.59619-0.23925-1.70557-0.15381-0.646-0.65625-1.02197-0.14014-0.09912-0.56055-0.30762-0.30762-0.15381-0.38623-0.18799-0.0752-0.0376-0.16064-0.03759-0.12646-0.01367-0.22217 0.02734z"></path>
                    </svg>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[11px] text-slate-500 font-medium whitespace-nowrap">Pergi ke</span>
                    <span class="text-[13px] sm:text-[14px] truncate"
                          :class="destinationCode ? 'text-[#0A2540] font-bold' : 'text-slate-400 font-medium'"
                          x-text="destinationCity">
                        Pilih kota / bandara
                    </span>
                </div>
            </div>

            {{-- 3. Field Departure Date --}}
            <div class="w-full lg:w-[158px] shrink-0 h-[68px] flex flex-row items-center gap-[8px] px-[10px] sm:px-[12px] py-[10px] bg-[#F8FAFC] border border-slate-200 rounded-[16px] cursor-pointer hover:border-[#FE6A00] transition-colors"
                 @click="openCalendar('depart')">
                <div class="w-[36px] h-[36px] shrink-0 rounded-[18px] flex items-center justify-center border border-amber-200"
                     style="background: linear-gradient(180deg, #FEECCB 0%, #FFFFFF 100%);">
                    <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[16px] h-[16px] fill-[#FE6A00]">
                        <path d="M4.57666 0.60156q-0.12305 0.01367-0.24268 0.09912-0.11963 0.08203-0.1914 0.19483-0.04102 0.08545-0.05469 0.20508-0.01367 0.11621-0.01367 0.35546l0 0.29395-1.32959 0.01367q-0.14014 0.01367-0.23926 0.02735-0.50244 0.14014-0.85449 0.50586-0.34863 0.3623-0.44776 0.88183-0.02734 0.0957-0.02734 0.81006l0 7.99463 0.05469 0.15381q0.15723 0.51953 0.56055 0.86133 0.40674 0.3418 0.93994 0.3999 0.15381 0.02734 4.26904 0.02734 4.11523 0 4.26904-0.02734 0.30762-0.02734 0.58789-0.16748 0.32471-0.15381 0.56055-0.44092 0.23926-0.28711 0.35205-0.65283l0.05469-0.15381 0-7.99463q0-0.71436-0.02735-0.81006-0.09912-0.51953-0.45117-0.88183-0.34863-0.36572-0.85107-0.50586-0.09912-0.01367-0.23926-0.02735l-1.32959-0.01367 0-0.29395q0-0.32129-0.03418-0.45459-0.03418-0.1333-0.14697-0.24609-0.14014-0.14014-0.35889-0.16064-0.21533-0.02051-0.37597 0.09228-0.16064 0.10938-0.21875 0.29395-0.02734 0.05469-0.03418 0.1333-0.00684 0.0752-0.00684 0.30078l0 0.33496-3.5 0 0-0.34863q0-0.23926-0.00684-0.30762-0.00684-0.07178-0.03418-0.12646-0.08545-0.21191-0.25293-0.30762-0.16748-0.09912-0.37939-0.05811z"></path>
                    </svg>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[11px] text-slate-500 font-medium whitespace-nowrap">Tanggal Pergi</span>
                    <span class="text-[14px] text-[#0A2540] font-bold truncate" x-text="departDate">04 Okt 2026</span>
                </div>
            </div>

            {{-- 4. Field Return Date --}}
            <div class="w-full lg:w-[178px] shrink-0 h-[68px] flex flex-row items-center gap-[8px] px-[10px] sm:px-[12px] py-[10px] bg-[#F8FAFC] border border-slate-200 rounded-[16px] cursor-pointer hover:border-[#FE6A00] transition-colors"
                 @click="openCalendar('return')">
                <div class="w-[36px] h-[36px] shrink-0 rounded-[18px] flex items-center justify-center border border-amber-200"
                     style="background: linear-gradient(180deg, #FEECCB 0%, #FFFFFF 100%);">
                    <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[16px] h-[16px] fill-[#FE6A00]">
                        <path d="M4.57666 0.60156q-0.12305 0.01367-0.24268 0.09912-0.11963 0.08203-0.1914 0.19483-0.04102 0.08545-0.05469 0.20508-0.01367 0.11621-0.01367 0.35546l0 0.29395-1.32959 0.01367q-0.14014 0.01367-0.23926 0.02735-0.50244 0.14014-0.85449 0.50586-0.34863 0.3623-0.44776 0.88183-0.02734 0.0957-0.02734 0.81006l0 7.99463 0.05469 0.15381q0.11279 0.36572 0.34863 0.65283 0.23926 0.28711 0.56397 0.44092 0.28027 0.14014 0.60156 0.16748 0.16748 0.02734 2.71387 0.01367l2.5498 0 0.09912-0.04102q0.22217-0.11279 0.3042-0.32129 0.08545-0.21191 0.00684-0.4204-0.0752-0.21192-0.29737-0.32471l-0.09912-0.04102-5.25-0.02734-0.09912-0.04102q-0.28027-0.12646-0.32129-0.43408-0.02734-0.11279-0.02734-2.73096l0-2.60449 9.35156 0 0 0.54688q0 0.40674 0.00684 0.48535 0.00684 0.0752 0.04101 0.14697 0.0376 0.06836 0.11963 0.15381 0.08545 0.08203 0.16065 0.12305 0.07861 0.04102 0.24609 0.04101 0.11279 0 0.15381-0.01367 0.04101-0.01367 0.09912-0.04102 0.18115-0.09912 0.28027-0.2666l0.04102-0.08203 0-3.87939q0-0.36572-0.02734-0.46143-0.09912-0.51953-0.45118-0.88183-0.34863-0.36572-0.85107-0.50586-0.09912-0.01367-0.23926-0.02735l-1.32959-0.01367 0-0.29395q0-0.32129-0.03418-0.45459-0.03418-0.1333-0.14697-0.24609-0.14014-0.14014-0.35889-0.16064-0.21533-0.02051-0.37597 0.09228-0.16064 0.10938-0.21875 0.29395-0.02734 0.05469-0.03418 0.1333-0.00684 0.0752-0.00684 0.30078l0 0.33496-3.5 0 0-0.34863q0-0.23926-0.00684-0.30762-0.00684-0.07178-0.03418-0.12646-0.08545-0.21191-0.25293-0.30762-0.16748-0.09912-0.37939-0.05811z"></path>
                    </svg>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[11px] text-slate-500 font-medium whitespace-nowrap">Tanggal Pulang</span>
                    <span class="text-[13px] font-semibold truncate"
                          :class="returnDate === '+ Tambah Pulang' ? 'text-[#FE6A00]' : 'text-[#0A2540] font-bold'"
                          x-text="returnDate">
                        + Tambah Pulang
                    </span>
                </div>
            </div>

            {{-- 5. Field Passengers --}}
            <div class="w-full lg:w-[220px] shrink-0 h-[68px] flex flex-row items-center gap-[8px] px-[10px] sm:px-[12px] py-[10px] bg-[#F8FAFC] border border-slate-200 rounded-[16px] cursor-pointer hover:border-[#FE6A00] transition-colors"
                 @click="passengerModalOpen = true">
                <div class="w-[36px] h-[36px] shrink-0 rounded-[18px] flex items-center justify-center border border-amber-200"
                     style="background: linear-gradient(180deg, #FEECCB 0%, #FFFFFF 100%);">
                    <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[16px] h-[16px] fill-[#FE6A00]">
                        <path d="M4.84326 1.20313q-0.81006 0.11279-1.42871 0.61865-0.61523 0.50244-0.89551 1.24414-0.18115 0.48877-0.18115 1.02197 0 0.28027 0.04102 0.50586 0.04443 0.22217 0.14013 0.48877 0.15381 0.46143 0.46826 0.84766 0.31787 0.38281 0.7212 0.63574 0.36572 0.22559 0.73486 0.32471 0.37256 0.0957 0.80664 0.0957 0.22559 0 0.3623-0.01367 0.14014-0.01367 0.35206-0.05811 0.60156-0.15381 1.104-0.55713 0.50586-0.40674 0.7998-0.98096 0.19482-0.39307 0.26661-0.88183 0.02734-0.14014 0.02734-0.40674 0-0.2666-0.02734-0.40674-0.09912-0.65625-0.46485-1.20312-0.3623-0.54688-0.93652-0.89551-0.43408-0.2666-0.98096-0.36572-0.15381-0.02734-0.45459-0.03418-0.30078-0.00684-0.45459 0.02051z"></path>
                    </svg>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[11px] text-slate-500 font-medium whitespace-nowrap">Penumpang &amp; Kelas</span>
                    <span class="text-[13px] sm:text-[14px] text-[#0A2540] font-bold truncate" x-text="passengerSummary">1 Dewasa · Ekonomi</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Bottom Action Bar (Glassmorphic Pills & Solid CTA outside Search Card) --}}
    <div class="box-border w-full max-w-[1120px] h-fit shrink-0 flex flex-col sm:flex-row gap-4 justify-between items-center relative z-20 px-2 sm:px-0">
        
        {{-- Quick Action Pills (Glassmorphic Style) --}}
        <div class="box-border w-fit shrink-0 h-fit flex flex-wrap gap-[10px] sm:gap-[12px] justify-start items-center">
            {{-- Pill Tiket Ferry --}}
            <a href="{{ route('ferry.index') }}"
               class="box-border w-fit shrink-0 h-[44px] shadow-sm flex flex-row gap-[8px] px-[18px] justify-start items-center rounded-[22px] bg-white/12 backdrop-blur-md border border-white/25 text-white hover:bg-white/20 hover:border-white/40 transition-all duration-200 group">
                <svg class="w-5 h-5 text-orange-400 group-hover:text-orange-300 transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                    <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/>
                    <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/>
                    <path d="M12 10v4"/>
                    <path d="M12 2v3"/>
                </svg>
                <span class="text-[13px] text-white font-semibold whitespace-nowrap">Tiket Ferry</span>
            </a>

            {{-- Pill Asistensi Visa --}}
            <a href="{{ route('visa.index') }}"
               class="box-border w-fit shrink-0 h-[44px] shadow-sm flex flex-row gap-[8px] px-[18px] justify-start items-center rounded-[22px] bg-white/12 backdrop-blur-md border border-white/25 text-white hover:bg-white/20 hover:border-white/40 transition-all duration-200 group">
                <svg class="w-5 h-5 text-orange-400 group-hover:text-orange-300 transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                    <line x1="2" x2="22" y1="10" y2="10"/>
                    <line x1="6" x2="10" y1="15" y2="15"/>
                </svg>
                <span class="text-[13px] text-white font-semibold whitespace-nowrap">Asistensi Visa</span>
            </a>

            {{-- Pill Cek Jadwal & Rute --}}
            <a href="{{ route('flights.index') }}"
               class="box-border w-fit shrink-0 h-[44px] shadow-sm flex flex-row gap-[8px] px-[18px] justify-start items-center rounded-[22px] bg-white/12 backdrop-blur-md border border-white/25 text-white hover:bg-white/20 hover:border-white/40 transition-all duration-200 group">
                <svg class="w-5 h-5 text-orange-400 group-hover:text-orange-300 transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                    <line x1="16" x2="16" y1="2" y2="6"/>
                    <line x1="8" x2="8" y1="2" y2="6"/>
                    <line x1="3" x2="21" y1="10" y2="10"/>
                    <path d="m9 16 2 2 4-4"/>
                </svg>
                <span class="text-[13px] text-white font-semibold whitespace-nowrap">Cek Jadwal &amp; Rute</span>
            </a>

            {{-- Discount Icon Pill (%) --}}
            <div class="box-border w-[44px] shrink-0 h-[44px] shadow-sm flex flex-row justify-center items-center rounded-[22px] bg-white/12 backdrop-blur-md border border-white/25 text-orange-400 hover:bg-white/20 hover:border-white/40 transition-all duration-200">
                <svg class="w-5 h-5 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" x2="5" y1="5" y2="19"/>
                    <circle cx="6.5" cy="6.5" r="2.5"/>
                    <circle cx="17.5" cy="17.5" r="2.5"/>
                </svg>
            </div>
        </div>

        {{-- Right CTA Group (Kode Promo Glass + Solid Orange Cari Tiket Button) --}}
        <div class="box-border w-fit shrink-0 h-fit flex flex-row gap-[14px] justify-start items-center">
            {{-- Button Kode Promo --}}
            <button type="button"
                    @click="promoCodeModalOpen = true"
                    class="box-border w-fit shrink-0 h-[46px] shadow-sm flex flex-row gap-[8px] px-[20px] justify-start items-center bg-white/12 backdrop-blur-md border border-white/25 rounded-[23px] text-white hover:bg-white/20 hover:border-white/40 transition-all duration-200 cursor-pointer">
                <svg class="w-5 h-5 text-white/80 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/>
                    <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>
                </svg>
                <span class="text-[14px] text-white font-semibold whitespace-nowrap"
                      x-text="appliedPromo ? 'Promo: ' + appliedPromo : 'Kode Promo'">
                    Kode Promo
                </span>
            </button>

            {{-- Button Cari Tiket (Solid Orange #FE6A00 with 4px 16px shadow) --}}
            <button type="button"
                    @click="submitSearch()"
                    class="box-border w-fit shrink-0 h-[46px] shadow-[0px_4px_20px_rgba(254,106,0,0.45)] flex flex-row gap-[8px] px-[28px] justify-start items-center bg-[#FE6A00] hover:bg-[#E05D00] text-white rounded-[23px] transition-all duration-200 hover:scale-102 active:scale-98 cursor-pointer">
                <svg class="w-5 h-5 text-white shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" x2="16.65" y1="21" y2="16.65"/>
                </svg>
                <span class="text-[15px] font-bold whitespace-nowrap">Cari Tiket</span>
            </button>
        </div>
    </div>

    {{-- Reusable Custom Non-Native Calendar Modal (Zero Native Inputs) --}}
    <x-home.calendar-modal />

    {{-- Airport Picker Modal --}}
    <div x-cloak x-show="airportModalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="airportModalOpen = false"
         class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-[#001D44]/50 backdrop-blur-sm"
         @click.self="airportModalOpen = false">
        <div class="w-full max-w-md bg-white rounded-[24px] shadow-2xl border border-slate-100 p-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h4 class="text-[16px] font-bold text-[#0F172A]"
                    x-text="airportTarget === 'origin' ? 'Pilih Kota Asal' : 'Pilih Kota Tujuan'">
                </h4>
                <button type="button" @click="airportModalOpen = false" class="text-slate-400 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="mt-3">
                <input type="text"
                       x-model="airportSearch"
                       placeholder="Cari kota, negara, atau kode bandara..."
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#FE6A00] focus:bg-white transition-colors" />
            </div>

            <div class="mt-3 max-h-64 overflow-y-auto space-y-1">
                <template x-for="item in filteredAirports" :key="item.code">
                    <div @click="selectAirport(item)"
                         class="flex items-center justify-between p-2.5 rounded-xl hover:bg-orange-50 cursor-pointer transition-colors group">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-[#0F172A] group-hover:text-[#FE6A00]" x-text="`${item.city}, ${item.country}`"></span>
                            <span class="text-xs text-slate-400" x-text="item.name"></span>
                        </div>
                        <span class="px-2 py-1 bg-slate-100 text-slate-700 font-bold rounded-lg text-xs group-hover:bg-[#FE6A00] group-hover:text-white transition-colors" x-text="item.code"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Passenger Counter Modal --}}
    <div x-cloak x-show="passengerModalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="passengerModalOpen = false"
         class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-[#001D44]/50 backdrop-blur-sm"
         @click.self="passengerModalOpen = false">
        <div class="w-full max-w-sm bg-white rounded-[24px] shadow-2xl border border-slate-100 p-5">
            <h4 class="text-[16px] font-bold text-[#0F172A] pb-3 border-b border-slate-100">Jumlah Penumpang</h4>
            
            <div class="space-y-4 py-4">
                {{-- Dewasa --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-[#0F172A]">Dewasa</p>
                        <p class="text-xs text-slate-400">Usia 12 tahun ke atas</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="if (adults > 1) adults--" class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100">-</button>
                        <span class="w-4 text-center font-bold text-sm" x-text="adults"></span>
                        <button type="button" @click="adults++" class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100">+</button>
                    </div>
                </div>

                {{-- Anak --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-[#0F172A]">Anak-anak</p>
                        <p class="text-xs text-slate-400">Usia 2 - 11 tahun</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="if (children > 0) children--" class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100">-</button>
                        <span class="w-4 text-center font-bold text-sm" x-text="children"></span>
                        <button type="button" @click="children++" class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100">+</button>
                    </div>
                </div>

                {{-- Bayi --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-[#0F172A]">Bayi</p>
                        <p class="text-xs text-slate-400">Di bawah 2 tahun</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="if (infants > 0) infants--" class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100">-</button>
                        <span class="w-4 text-center font-bold text-sm" x-text="infants"></span>
                        <button type="button" @click="infants++" class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100">+</button>
                    </div>
                </div>
            </div>

            <button type="button" @click="passengerModalOpen = false" class="w-full py-2.5 bg-[#FE6A00] text-white font-bold rounded-xl text-sm hover:bg-[#E05D00] transition-colors">
                Selesai
            </button>
        </div>
    </div>

    {{-- Promo Code Modal --}}
    <div x-cloak x-show="promoCodeModalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="promoCodeModalOpen = false"
         class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-[#001D44]/50 backdrop-blur-sm"
         @click.self="promoCodeModalOpen = false">
        <div class="w-full max-w-sm bg-white rounded-[24px] shadow-2xl border border-slate-100 p-5">
            <h4 class="text-[16px] font-bold text-[#0F172A] pb-3 border-b border-slate-100">Gunakan Kode Promo</h4>
            <div class="py-4">
                <input type="text"
                       x-model="promoCode"
                       placeholder="Masukkan kode promo (cth: ALLIAGOHEMAT)"
                       class="w-full px-3.5 py-2.5 uppercase font-bold text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#FE6A00] focus:bg-white transition-colors" />
            </div>
            <div class="flex gap-2">
                <button type="button" @click="promoCodeModalOpen = false" class="flex-1 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl text-sm hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="button" @click="applyPromo()" class="flex-1 py-2.5 bg-[#FE6A00] text-white font-bold rounded-xl text-sm hover:bg-[#E05D00] transition-colors">
                    Terapkan
                </button>
            </div>
        </div>
    </div>
</section>
