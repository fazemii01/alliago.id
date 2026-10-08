{{-- 
    Contact & Consultation Banner Component
    Transformed from Mobile App Download into dedicated Customer Contact & Consultation Hub
    Tokens: Outfit font, #00275A -> #0B488F -> #1D6BC4 gradient, #FE6A00 brand accent, Lucide inline SVGs
--}}
<section id="contact-consultation" class="box-border w-full shrink-0 flex flex-col gap-0 p-[20px_16px_48px_16px] lg:p-[20px_80px_48px_80px] justify-start items-center relative z-10 font-['Outfit',sans-serif]">
    
    {{-- Contact Banner Card (Max 1280px) --}}
    <div class="box-border w-full max-w-[1280px] min-h-[220px] shrink-0 flex flex-col lg:flex-row gap-8 lg:gap-10 p-[32px_24px] sm:p-[36px_40px] lg:p-[40px_48px] justify-between items-center rounded-[28px] overflow-hidden relative shadow-2xl border border-white/10"
         style="background: linear-gradient(135deg, #001D44 0%, #00275A 35%, #0B488F 70%, #1D6BC4 100%); background-repeat: no-repeat; background-size: 100% 100%;">
        
        {{-- Left: Headline, Description & Direct Action CTAs --}}
        <div class="box-border w-full lg:max-w-[650px] shrink-0 flex flex-col gap-[14px] justify-start items-start relative z-10 text-left">
            
            {{-- Official Badge with Active Indicator --}}
            <div class="box-border w-fit h-[28px] shrink-0 flex items-center gap-2 px-[14px] bg-white/15 backdrop-blur-md rounded-full border border-white/20 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[11px] sm:text-[12px] text-white font-semibold tracking-wide whitespace-nowrap">
                    Layanan Konsultasi &amp; Bantuan Perjalanan
                </span>
            </div>

            {{-- Headline --}}
            <h2 class="text-[22px] sm:text-[28px] lg:text-[30px] text-white font-extrabold leading-[1.25] tracking-tight">
                Butuh Bantuan atau Konsultasi Perjalanan?
            </h2>

            {{-- Description --}}
            <p class="text-[13px] sm:text-[14px] text-[#BFDBFE] font-normal leading-relaxed max-w-xl">
                Rencanakan perjalanan Anda tanpa repot. Tim konsultan AlliaGo siap melayani asistensi visa resmi, pemesanan tiket penerbangan domestik &amp; internasional, serta tiket ferry Batam - Singapura - Malaysia.
            </p>

            {{-- Direct Action Buttons Row (Zero App Store references) --}}
            <div class="box-border w-full sm:w-fit shrink-0 flex flex-wrap gap-[12px] pt-[6px] justify-start items-center">
                {{-- Primary WhatsApp CTA --}}
                <a href="https://wa.me/6281334455616?text=Halo%20AlliaGo,%20saya%20ingin%20konsultasi%20pemesanan%20tiket%20dan%20visa"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="box-border w-full sm:w-fit shrink-0 h-[44px] flex flex-row gap-[10px] px-[20px] items-center justify-center bg-[#25D366] hover:bg-[#20ba59] active:bg-[#1caa4f] text-white rounded-full font-bold text-[13px] sm:text-[14px] shadow-lg shadow-emerald-950/30 transition-all duration-200 hover:scale-[1.02] group">
                    {{-- WhatsApp SVG Icon --}}
                    <svg viewBox="0 0 24 24" class="w-[20px] h-[20px] fill-current shrink-0">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <span>Chat WhatsApp Resmi</span>
                </a>

                {{-- Secondary FAQ CTA --}}
                <a href="{{ route('pages.faq') }}"
                   class="box-border w-full sm:w-fit shrink-0 h-[44px] flex flex-row gap-[8px] px-[18px] items-center justify-center bg-white/10 hover:bg-white/20 active:bg-white/25 text-white border border-white/20 rounded-full font-semibold text-[13px] sm:text-[14px] backdrop-blur-md transition-all duration-200">
                    {{-- Lucide HelpCircle Icon --}}
                    <svg viewBox="0 0 24 24" class="w-[18px] h-[18px] stroke-current fill-none stroke-[2]" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span>Pusat Bantuan &amp; FAQ</span>
                </a>
            </div>
        </div>

        {{-- Right: Contact Information Card (Replaces the phone mockup) --}}
        <div class="box-border w-full lg:w-[380px] shrink-0 flex flex-col gap-3.5 p-5 sm:p-6 bg-[#001D44]/75 backdrop-blur-xl border border-white/20 rounded-[22px] relative z-10 shadow-2xl">
            
            {{-- Contact Card Header --}}
            <div class="flex items-center justify-between pb-2 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-[38px] h-[38px] rounded-[12px] bg-gradient-to-tr from-[#FE6A00] to-[#FF8C38] flex items-center justify-center text-white shadow-md shadow-orange-950/30">
                        {{-- Lucide Headset / Phone SVG --}}
                        <svg viewBox="0 0 24 24" class="w-[20px] h-[20px] stroke-current fill-none stroke-[2]" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                            <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[14px] font-bold text-white leading-tight">Kontak &amp; Dukungan</div>
                        <div class="text-[11px] text-[#93C5FD]">Customer Care AlliaGo</div>
                    </div>
                </div>

                {{-- Online Badge --}}
                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[11px] font-medium text-emerald-300">Online</span>
                </div>
            </div>

            {{-- Contact Info Items List --}}
            <div class="flex flex-col gap-2.5">
                {{-- Item 1: WhatsApp Support Number --}}
                <a href="https://wa.me/6281334455616"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex items-center justify-between p-2.5 rounded-[14px] bg-white/5 hover:bg-white/10 border border-white/10 transition-colors group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-[#25D366]/20 flex items-center justify-center text-[#25D366] shrink-0">
                            {{-- Phone SVG --}}
                            <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none stroke-[2]" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[10px] text-[#94A3B8] uppercase tracking-wider font-semibold">WhatsApp Resmi</div>
                            <div class="text-[13px] font-bold text-white group-hover:text-[#FE6A00] transition-colors">0813-3445-5616</div>
                        </div>
                    </div>
                    {{-- External Arrow --}}
                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-white/40 group-hover:text-white group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all stroke-current fill-none stroke-[2]" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 17l9.2-9.2M17 17V7H7"/>
                    </svg>
                </a>

                {{-- Item 2: Jam Operasional --}}
                <div class="flex items-center gap-2.5 p-2.5 rounded-[14px] bg-white/5 border border-white/10">
                    <div class="w-8 h-8 rounded-full bg-[#FE6A00]/20 flex items-center justify-center text-[#FE6A00] shrink-0">
                        {{-- Clock SVG --}}
                        <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none stroke-[2]" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] text-[#94A3B8] uppercase tracking-wider font-semibold">Jam Operasional</div>
                        <div class="text-[12px] font-medium text-white">09.00 - 16.00 WIB</div>
                    </div>
                </div>

                {{-- Item 3: Alamat Kantor --}}
                <a href="https://maps.google.com/maps?q=Jl.+Adara+Park+No.2%2C+Karanganyar%2C+Kabuaran%2C+Kec.+Kunir%2C+Kabupaten+Lumajang%2C+Jawa+Timur+67383"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex items-start gap-2.5 p-2.5 rounded-[14px] bg-white/5 hover:bg-white/10 border border-white/10 transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-[#60A5FA] shrink-0 mt-0.5">
                        {{-- MapPin SVG --}}
                        <svg viewBox="0 0 24 24" class="w-4 h-4 stroke-current fill-none stroke-[2]" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-[#94A3B8] uppercase tracking-wider font-semibold">Alamat Kantor</span>
                            {{-- External Arrow --}}
                            <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-white/40 group-hover:text-white group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all stroke-current fill-none stroke-[2]" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 17l9.2-9.2M17 17V7H7"/>
                            </svg>
                        </div>
                        <div class="text-[11px] sm:text-[12px] font-medium text-white group-hover:text-[#93C5FD] transition-colors leading-snug pt-0.5">
                            Jl. Adara Park No.2, Karanganyar, Kabuaran, Kec. Kunir, Kabupaten Lumajang, Jawa Timur 67383
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Constellation Dot Grid Pattern from alliago.pen --}}
        <div class="box-border w-full h-[220px] absolute left-0 top-0 overflow-hidden pointer-events-none z-0">
            @for ($cx = 40; $cx <= 1200; $cx += 60)
                @for ($cy = 20; $cy <= 200; $cy += 40)
                    <div class="w-[3px] h-[3px] absolute bg-white/15 rounded-full" style="left: {{ $cx }}px; top: {{ $cy }}px;"></div>
                @endfor
            @endfor
        </div>
    </div>
</section>
