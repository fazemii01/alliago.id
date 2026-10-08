{{-- 
    Comprehensive Footer Component
    1:1 Implementation from alliago.pen node b1PhN
    Contains:
    - 4-Column Layout (Col Brand, Col Tentang AlliaGo, Col Pusat Bantuan, Col Informasi & Kontak)
    - Partners Container (5 Airlines Rekanan, 8 Metode Pembayaran)
    - Footer Bottom Row (Copyright & 4 Lucide Social SVGs)
    - Global Route Watermark Arcs
--}}
<footer style="background-color: #001D44;" class="text-white pt-12 pb-9 font-['Outfit',sans-serif] relative overflow-hidden">
    {{-- Global Route Watermark Arcs (Background decoration from alliago.pen) --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden z-0">
        {{-- Route Arc 1 --}}
        <svg viewBox="0 0 800 200" preserveAspectRatio="none" class="w-[800px] h-[200px] absolute left-[100px] top-[80px] overflow-visible opacity-50">
            <path d="M0 160q400-150 800-40" fill="none" stroke="#ffffff08" stroke-width="2" vector-effect="non-scaling-stroke"></path>
        </svg>
        {{-- Route Arc 2 --}}
        <svg viewBox="0 0 740 240" preserveAspectRatio="none" class="w-[740px] h-[240px] absolute left-[600px] top-[120px] overflow-visible opacity-50">
            <path d="M0 180q370-160 740-20" fill="none" stroke="#fe6a000a" stroke-width="2" vector-effect="non-scaling-stroke"></path>
        </svg>
    </div>

    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 relative z-[1] flex flex-col gap-8">
        {{-- Footer Columns Row --}}
        <div class="flex flex-col lg:flex-row justify-between items-start gap-8 lg:gap-12 pb-2">
            {{-- Col Brand --}}
            <div class="w-full lg:w-[320px] shrink-0 flex flex-col gap-3">
                <a href="{{ url('/') }}" class="inline-block">
                    <span class="text-[22px] font-extrabold tracking-[2px] text-white">ALLIAGO<span class="text-[#FE6A00]">.ID</span></span>
                </a>
                <p class="text-[13px] text-[#94A3B8] leading-relaxed">
                    Platform travel terpercaya untuk tiket pesawat, tiket ferry internasional, hotel, dan asistensi visa resmi.
                </p>
            </div>

            {{-- 3 Navigation Columns Wrapper --}}
            <div class="w-full lg:flex-1 grid grid-cols-1 sm:grid-cols-3 gap-8">
                {{-- Col Tentang AlliaGo --}}
                <div class="flex flex-col gap-2.5">
                    <h4 class="text-[15px] font-bold text-white">Tentang AlliaGo</h4>
                    <ul class="flex flex-col gap-2 text-[13px] text-[#94A3B8]">
                        <li><a href="{{ route('pages.about') }}" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#promo" class="hover:text-white transition-colors">Penawaran Khusus</a></li>
                        <li><a href="{{ route('pages.about') }}" class="hover:text-white transition-colors">Blog Perjalanan</a></li>
                        <li><a href="{{ route('visa.index') }}" class="hover:text-white transition-colors">Layanan Visa Resmi</a></li>
                        <li><a href="{{ route('pages.about') }}" class="hover:text-white transition-colors">Karir</a></li>
                    </ul>
                </div>

                {{-- Col Pusat Bantuan --}}
                <div class="flex flex-col gap-2.5">
                    <h4 class="text-[15px] font-bold text-white">Pusat Bantuan</h4>
                    <ul class="flex flex-col gap-2 text-[13px] text-[#94A3B8]">
                        <li><a href="{{ route('pages.faq') }}" class="hover:text-white transition-colors">Pertanyaan Umum (FAQ)</a></li>
                        <li><a href="{{ route('pages.faq') }}" class="hover:text-white transition-colors">Opsi Pembayaran</a></li>
                        <li><a href="{{ route('ferry.index') }}" class="hover:text-white transition-colors">Tiket Ferry Internasional</a></li>
                        <li><a href="{{ route('pages.currency_rates') }}" class="hover:text-white transition-colors">Info Kurs Valas (IDR/RM)</a></li>
                        <li><a href="{{ route('flights.index') }}" class="hover:text-white transition-colors">Check-In Online</a></li>
                    </ul>
                </div>

                {{-- Col Informasi & Kontak --}}
                <div class="flex flex-col gap-2.5">
                    <h4 class="text-[15px] font-bold text-white">Informasi & Kontak</h4>
                    <ul class="flex flex-col gap-2 text-[13px] text-[#94A3B8]">
                        <li><a href="{{ route('pages.privacy_policy') }}" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('pages.refund_policy') }}" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                        <li>
                            <a href="https://wa.me/6281334455616" target="_blank" rel="noopener noreferrer" class="hover:text-[#FE6A00] transition-colors inline-flex items-center gap-1.5 text-[#CBD5E1]">
                                <span>WhatsApp: 0813-3445-5616</span>
                            </a>
                        </li>
                        <li class="text-[12px] text-[#94A3B8] leading-tight pt-1">
                            <span class="text-white font-medium block">Jam Operasional:</span>
                            09.00 - 16.00 WIB
                        </li>
                        <li class="text-[12px] text-[#94A3B8] leading-relaxed">
                            <span class="text-white font-medium block">Alamat:</span>
                            Jl. Adara Park No.2, Karanganyar, Kabuaran, Kec. Kunir, Kabupaten Lumajang, Jawa Timur 67383
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Partners Container (alliago.pen node VVqG5) --}}
        <div class="w-full flex flex-col gap-4 p-5 sm:p-[20px_24px] bg-[#ffffff0a] ring-1 ring-white/10 rounded-[16px] relative z-[1]">
            {{-- Airlines Row --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                <span class="text-[12px] font-semibold text-[#CBD5E1] shrink-0">Maskapai Rekanan Resmi:</span>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                    <span class="inline-flex items-center h-[28px] px-3 bg-white text-[#00275A] text-[11px] font-bold rounded-[14px] shadow-sm select-none">
                        Lion Air
                    </span>
                    <span class="inline-flex items-center h-[28px] px-3 bg-white text-[#00275A] text-[11px] font-bold rounded-[14px] shadow-sm select-none">
                        Batik Air
                    </span>
                    <span class="inline-flex items-center h-[28px] px-3 bg-white text-[#00275A] text-[11px] font-bold rounded-[14px] shadow-sm select-none">
                        Wings Air
                    </span>
                    <span class="inline-flex items-center h-[28px] px-3 bg-white text-[#00275A] text-[11px] font-bold rounded-[14px] shadow-sm select-none">
                        Super Air Jet
                    </span>
                    <span class="inline-flex items-center h-[28px] px-3 bg-white text-[#00275A] text-[11px] font-bold rounded-[14px] shadow-sm select-none">
                        Thai Lion Air
                    </span>
                </div>
            </div>

            {{-- Payments Row --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-3.5 pt-1">
                <span class="text-[12px] font-semibold text-[#CBD5E1] shrink-0">Metode Pembayaran:</span>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center h-[26px] px-2.5 bg-white text-[#00275A] text-[11px] font-bold rounded-[8px] shadow-sm select-none">
                        BCA
                    </span>
                    <span class="inline-flex items-center h-[26px] px-2.5 bg-white text-[#00275A] text-[11px] font-bold rounded-[8px] shadow-sm select-none">
                        Mandiri
                    </span>
                    <span class="inline-flex items-center h-[26px] px-2.5 bg-white text-[#00275A] text-[11px] font-bold rounded-[8px] shadow-sm select-none">
                        BNI
                    </span>
                    <span class="inline-flex items-center h-[26px] px-2.5 bg-white text-[#00275A] text-[11px] font-bold rounded-[8px] shadow-sm select-none">
                        BRI
                    </span>
                    <span class="inline-flex items-center h-[26px] px-2.5 bg-white text-[#00275A] text-[11px] font-bold rounded-[8px] shadow-sm select-none">
                        QRIS
                    </span>
                    <span class="inline-flex items-center h-[26px] px-2.5 bg-white text-[#00275A] text-[11px] font-bold rounded-[8px] shadow-sm select-none">
                        Visa
                    </span>
                    <span class="inline-flex items-center h-[26px] px-2.5 bg-white text-[#00275A] text-[11px] font-bold rounded-[8px] shadow-sm select-none">
                        Mastercard
                    </span>
                    <span class="inline-flex items-center h-[26px] px-2.5 bg-white text-[#00275A] text-[11px] font-bold rounded-[8px] shadow-sm select-none">
                        JCB
                    </span>
                </div>
            </div>
        </div>

        {{-- Footer Bottom Row --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-1 relative z-[2]">
            <p class="text-[12px] text-[#64748B]">
                © 2026 AlliaGo.id. All Rights Reserved.
            </p>

            {{-- Social Links (Lucide SVGs 18x18 from alliago.pen) --}}
            <div class="flex items-center gap-4 text-[#94A3B8]">
                {{-- Instagram --}}
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" aria-label="Instagram">
                    <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[18px] h-[18px] fill-current">
                        <path d="M3.83496 0.60156q-0.51611 0.02734-1.03564 0.23242-0.51611 0.20166-0.92286 0.53663-0.56055 0.44775-0.88867 1.07324-0.32813 0.62207-0.38623 1.3501-0.02734 0.19482-0.02734 3.20605 0 3.01123 0.02734 3.20605 0.11279 1.28857 1.0083 2.18409 0.89551 0.89551 2.18409 1.0083 0.19482 0.02734 3.20605 0.02734 3.01123 0 3.20605-0.02734 1.28857-0.11279 2.18409-1.0083 0.89551-0.89551 1.0083-2.18409 0.02734-0.19482 0.02734-3.20605 0-3.01123-0.02734-3.20605-0.11279-1.28857-1.0083-2.18409-0.7998-0.79639-1.9585-0.99463-0.12647-0.01367-0.63232-0.02734l-2.64551 0q-3.10693 0-3.31885 0.01367z m6.33008 1.16211q0.81006 0.09912 1.39111 0.68018 0.58105 0.58105 0.68018 1.39111 0.01367 0.18457 0.01367 3.16504 0 2.98047-0.01367 3.16504-0.08545 0.79639-0.646 1.37061-0.46143 0.46143-1.07666 0.64599l-0.01367 0q-0.15381 0.04101-0.30762 0.04102-0.22559 0.02734-0.88183 0.02734l-4.6211 0q-0.65625 0-0.88183-0.02734-0.15381 0-0.30762-0.04102l-0.01367 0q-0.56055-0.15723-0.98779-0.56055-0.42725-0.40674-0.62208-0.95361l-0.01367-0.05469q-0.05811-0.15381-0.07177-0.29394-0.02734-0.21191-0.02735-0.78614l0-5.05175q0-0.58789 0.02735-0.78614 0.01367-0.15381 0.07177-0.30761l0.01367-0.05469q0.16748-0.50586 0.56055-0.89551 0.60156-0.60156 1.44238-0.67334 0.14014-0.01367 3.15479-0.01367 3.01807 0 3.13086 0.01367z m-0.07178 1.45606q-0.18115 0.02734-0.31445 0.18115-0.1333 0.15381-0.14697 0.35205-0.01367 0.24951 0.1538 0.43408 0.16748 0.18115 0.42041 0.18115 0.2085 0 0.36231-0.11962 0.15723-0.11963 0.21191-0.30079 0.05469-0.18115-0.03076-0.36914-0.08203-0.19141-0.25635-0.28711-0.17432-0.09912-0.3999-0.07177z m-3.48633 0.86816q-0.61523 0.08545-1.16894 0.44434-0.55029 0.35547-0.88867 0.89892-0.25293 0.40674-0.37598 0.92627-0.04443 0.15381-0.05127 0.23926-0.00684 0.08203-0.00684 0.34863 0 0.26318 0.00684 0.36231 0.00684 0.09912 0.0376 0.22558 0.0957 0.44775 0.27685 0.79981 0.18457 0.34863 0.49219 0.66992 0.82373 0.8374 2.0166 0.88184 0.43408 0.01367 0.78272-0.08545 0.62891-0.15381 1.13135-0.56739 0.50586-0.41357 0.78613-1.00146 0.16748-0.32129 0.25293-0.69727 0.02734-0.1709 0.02734-0.5332 0-0.2666-0.01367-0.39307-0.01367-0.12646-0.05469-0.34863-0.19824-0.76904-0.7793-1.3501-0.58105-0.58105-1.35009-0.76562-0.57422-0.14014-1.1211-0.05469z m0.77246 1.17578q0.51611 0.11279 0.87842 0.47852 0.36572 0.3623 0.47852 0.87842 0.02734 0.18457 0.02051 0.43066-0.00684 0.24268-0.06495 0.43408-0.05469 0.18799-0.18115 0.41016-0.06836 0.12646-0.22217 0.28027-0.15381 0.15381-0.2666 0.23926-0.25293 0.16748-0.5332 0.24951-0.4751 0.12646-0.94336-0.01367-0.46826-0.14014-0.80664-0.49561-0.33496-0.35889-0.43408-0.84765-0.02734-0.14014-0.02051-0.34864 0.00684-0.21191 0.02051-0.33837 0.11279-0.51611 0.48877-0.88526 0.37939-0.37256 0.89892-0.47168 0.12646-0.02734 0.33496-0.02734 0.21191 0 0.35205 0.02734z" />
                    </svg>
                </a>

                {{-- Facebook --}}
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" aria-label="Facebook">
                    <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[18px] h-[18px] fill-current">
                        <path d="M8.52441 0.58789q-0.7417 0.05469-1.39453 0.3999-0.64941 0.3418-1.11767 0.91602-0.46826 0.57422-0.64942 1.28857-0.07178 0.29395-0.08545 0.46143-0.01367 0.16748-0.02734 0.7998l0 0.79639-0.60156 0q-0.60156 0-0.68701 0.01367-0.15381 0.04102-0.27344 0.15381-0.11963 0.11279-0.16065 0.25293-0.02734 0.09912-0.02734 1.32959 0 1.23047 0.02734 1.32959 0.04102 0.14014 0.14698 0.24609 0.10596 0.10596 0.24609 0.14698 0.08203 0.02734 0.70068 0.02734l0.62891 0 0.01367 4.22803 0.04102 0.09912q0.04443 0.08203 0.11279 0.16064 0.07178 0.0752 0.15381 0.11963 0.08545 0.04102 0.30762 0.05469 0.22559 0.01367 1.12109 0.01367 0.89551 0 1.11768-0.01367 0.22559-0.01367 0.30761-0.05469 0.08545-0.04443 0.15381-0.11963 0.07178-0.07861 0.11621-0.16064l0.04102-0.09912 0.01367-4.22803 1.31592-0.01367 0.09912-0.04102q0.06836-0.04443 0.15039-0.11963 0.08545-0.07861 0.11963-0.14697 0.0376-0.07178 0.34521-1.28857 0.2085-0.85449 0.24952-1.05616 0.04443-0.20508 0.04443-0.2871-0.01367-0.33838-0.32129-0.49219l-0.09912-0.04102-1.90381-0.01367 0-1.17578 1.91748 0 0.08545-0.04102q0.06836-0.04443 0.15039-0.12646 0.08545-0.08545 0.12988-0.15381l0.04102-0.08545 0-2.46435q0-0.18115-0.0376-0.25635-0.03418-0.07861-0.11279-0.16748-0.0752-0.09229-0.15381-0.12647-0.0752-0.0376-0.18115-0.0581-0.10254-0.02051-0.96387-0.02051-0.86133 0-1.10059 0.01367z m1.40137 1.75l0 0.58789-0.65625 0q-0.56055 0-0.71435 0.01367-0.15381 0.01367-0.32471 0.09912-0.14014 0.06836-0.29395 0.22217-0.12305 0.12646-0.18798 0.23242-0.06152 0.10596-0.11963 0.25977-0.02734 0.0957-0.03418 0.23584-0.00684 0.14014-0.00684 0.86816-0.01367 0.96729 0 1.06641 0.04102 0.19482 0.16748 0.32129 0.12646 0.12646 0.30762 0.16748 0.09912 0.01367 0.88867 0.01367 0.79297 0 0.79297 0l-0.28027 1.14844-1.42872 0.01367-0.09912 0.04102q-0.27686 0.14014-0.33496 0.43408-0.02734 0.09912-0.02734 2.14306l0 2.04395-1.14844 0 0-2.04395q0-2.04395-0.02734-2.14306-0.05811-0.29395-0.33496-0.43408l-0.09912-0.04102-1.28858-0.01367 0-1.14844 0.57422 0q0.58789 0 0.68701-0.02734 0.32129-0.05811 0.44776-0.3794 0.02734-0.05469 0.04101-1.17578l0-0.01367q0-0.88184 0.01367-1.06982 0.01367-0.19141 0.09912-0.41358 0.22217-0.64258 0.75538-1.07666 0.5332-0.43408 1.20312-0.50586 0.11279-0.01367 0.77246-0.01367l0.65625 0 0 0.58789z" />
                    </svg>
                </a>

                {{-- Twitter --}}
                <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" aria-label="Twitter">
                    <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[18px] h-[18px] fill-current">
                        <path d="M8.90381 1.77734q-0.48877 0.07178-0.9502 0.30762-0.35205 0.18457-0.63232 0.40674-0.40674 0.36572-0.6665 0.89209-0.25635 0.52295-0.29737 1.0835l-0.01709 0.19482-0.08203-0.01367q-0.84082-0.09912-1.55517-0.36573-1.39795-0.52979-2.36524-1.59619-0.14014-0.15381-0.20508-0.20166-0.06152-0.04785-0.12988-0.09228-0.11279-0.05469-0.23926-0.05469-0.2085 0-0.3418 0.09912-0.1333 0.0957-0.25976 0.34863-0.44775 0.89551-0.54688 1.89014-0.0957 0.99463 0.18116 1.96192 0.28027 1.03564 0.90234 1.86962 0.62549 0.83057 1.53467 1.39112l0.21191 0.14013-0.19824 0.08203q-0.88184 0.3794-1.83203 0.3794-0.32471 0-0.40674 0.02734-0.2666 0.07178-0.37256 0.32471-0.10254 0.24951 0.00684 0.50244 0.05811 0.08203 0.1333 0.15381 0.07861 0.06836 0.25976 0.18115 1.41504 0.88184 3.06592 1.09375 0.25293 0.04102 0.84082 0.04102 0.4751 0 0.69043-0.02051 0.21875-0.02051 0.61182-0.09228 1.06299-0.19482 2.05078-0.69727 0.98779-0.50586 1.77051-1.26123 0.98096-0.95361 1.54834-2.12939 0.56738-1.17578 0.69043-2.51905 0.03076-0.25293 0.03076-0.7417 0-0.49219-0.03076-0.68701l-0.02735-0.15381 0.18116-0.23926q0.1709-0.22217 0.3247-0.46142 0.18115-0.30762 0.36914-0.72803 0.18799-0.42041 0.24268-0.62207 0.0581-0.20166-0.04102-0.38965-0.0957-0.19141-0.27685-0.27685-0.09912-0.04102-0.23926-0.04102-0.14014 0-0.19824 0.02051-0.05469 0.02051-0.29395 0.16064-0.23584 0.14014-0.6084 0.3042-0.36914 0.16064-0.44091 0.16065-0.04102 0-0.2085-0.1128-0.46143-0.32129-1.0083-0.46142-0.23926-0.05811-0.58105-0.07861-0.3418-0.02051-0.59473 0.0205z m0.64258 1.14844q0.56055 0.04102 1.12109 0.51953 0.12647 0.0957 0.21533 0.1333 0.09229 0.03418 0.2461 0.03418 0.21191 0 0.39306-0.0581 0.05469-0.01367-0.22558 0.29394-0.16748 0.19824-0.19483 0.31787-0.02734 0.11621 0.01367 0.41016 0.02734 0.2666 0.04102 0.73145 0.01709 0.46143-0.01367 0.71093-0.14014 1.55518-1.02198 2.86426-0.88184 1.30908-2.30712 2.06445-0.7998 0.42041-1.75 0.61524-0.51953 0.09912-1.1211 0.09912-0.5332 0-1.0083-0.08545-0.15381-0.01367-0.42724-0.08203-0.27344-0.07178-0.30079-0.08545-0.02734-0.01367 0.09913-0.05469 0.3623-0.11279 0.74511-0.31445 0.38623-0.20508 0.69727-0.44092 0.3623-0.2666 0.44775-0.42041 0.02734-0.07178 0.03418-0.11279 0.00684-0.04102 0.00684-0.16065 0-0.11963-0.02051-0.18798-0.02051-0.07178-0.09228-0.1709-0.04102-0.05469-0.14014-0.10938-0.0957-0.05811-0.19483-0.09912-1.16211-0.3623-1.97558-1.31592-0.81006-0.95361-1.0083-2.19775-0.02734-0.18115-0.03418-0.52295-0.00684-0.34521 0.00683-0.52637 0.04102-0.42041 0.14014-0.72802l0.02734-0.09913 0.12647 0.1128q0.65967 0.58789 1.52783 1.01513 0.86816 0.42725 1.77734 0.6084 0.5332 0.11279 1.14844 0.14014 0.42041 0.02734 0.58789-0.00684 0.16748-0.03418 0.28711-0.14697 0.11963-0.11279 0.16065-0.2666 0.04102-0.15381 0-0.33496-0.02734-0.09912-0.03418-0.32813-0.00684-0.23242 0.01367-0.35888 0.02051-0.12646 0.08203-0.30079 0.06494-0.17432 0.14697-0.30078 0.21191-0.34863 0.58789-0.58789 0.37939-0.23926 0.79981-0.2666 0.16748-0.01367 0.20849-0.01367l0.18116 0.01367z" />
                    </svg>
                </a>

                {{-- YouTube --}}
                <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" aria-label="YouTube">
                    <svg viewBox="0 0 14 14" preserveAspectRatio="xMidYMid meet" class="w-[18px] h-[18px] fill-current">
                        <path d="M6.20361 2.29688q-1.48682 0.05469-2.88476 0.23583-1.13477 0.14014-1.37403 0.22559-0.3623 0.14014-0.6289 0.43408-0.2666 0.29395-0.39307 0.67334-0.06836 0.22217-0.16064 0.76905-0.08887 0.54688-0.14698 1.104-0.15381 1.76367 0.14014 3.57178 0.07178 0.4751 0.12647 0.69385 0.05469 0.21533 0.14013 0.38281 0.15381 0.29395 0.40674 0.52636 0.25293 0.229 0.57422 0.3418 0.2085 0.07178 1.11768 0.19141 0.9126 0.11621 1.72265 0.18799 0.61523 0.04101 1.00147 0.05468 0.38623 0.01367 1.15527 0.01367 0.76904 0 1.15527-0.01367 0.38623-0.01367 1.00147-0.05468 0.85449-0.07178 1.75-0.18799 0.89551-0.11963 1.104-0.19141 0.39307-0.15381 0.66651-0.44092 0.27344-0.28711 0.3999-0.67675 0.06836-0.22559 0.15722-0.77247 0.09229-0.54688 0.1504-1.104 0.18115-2.00293-0.22559-4.07422-0.15381-0.78613-0.70068-1.18945-0.19482-0.15723-0.39991-0.22559-0.20166-0.06836-0.60498-0.14013-1.3877-0.2085-2.91211-0.29395-0.31104-0.01367-1.18603-0.02734-0.875-0.01367-1.15186 0l0-0.01367z m1.85938 1.18945q1.36035 0.05469 2.6626 0.22217 0.88184 0.11279 0.99121 0.18457 0.14014 0.08203 0.20849 0.229 0.07178 0.14697 0.19825 0.90234 0.28027 1.97559 0 3.93409-0.12646 0.77246-0.19483 0.91259-0.11279 0.23584-0.36572 0.29395-0.19482 0.04102-0.88867 0.1333-0.69385 0.08887-1.18262 0.1333-0.71436 0.05469-1.22705 0.0752-0.50928 0.02051-1.26465 0.0205-0.75537 0-1.26807-0.0205-0.50928-0.02051-1.22363-0.0752-0.48877-0.04443-1.18262-0.1333-0.69385-0.09229-0.88867-0.1333-0.25293-0.0581-0.36572-0.29395-0.05469-0.09912-0.14014-0.58105-0.08203-0.48535-0.12646-0.96045-0.0957-0.93652-0.05469-1.93799 0.04102-1.00146 0.22559-1.92431 0.04102-0.21191 0.07519-0.30079 0.03418-0.09229 0.10254-0.16748 0.07178-0.07861 0.16406-0.11963 0.09229-0.04443 0.30078-0.07177 1.55518-0.25293 3.24707-0.32129 0.37939-0.01367 1.10743-0.01367 0.72803 0 1.09033 0.01367z m-2.4336 1.2168q-0.09912 0.04443-0.1914 0.12988-0.08887 0.08203-0.1333 0.16406l-0.04102 0.09912 0 3.80762 0.04102 0.09912q0.09912 0.19482 0.30761 0.28027 0.21191 0.08203 0.40674 0.01367 0.05811-0.01367 1.56201-0.91601 1.50391-0.90576 1.56202-0.94678 0.18115-0.16748 0.18798-0.42041 0.00684-0.25293-0.17431-0.42041-0.04443-0.05469-1.54834-0.95703-1.50391-0.90576-1.58936-0.9331-0.08203-0.02734-0.19482-0.02735-0.11279 0-0.19483 0.02735z m1.9585 2.29687q0 0.01367-0.58789 0.3623l-0.57422 0.35206 0-1.42872 0.57422 0.35206q0.58789 0.34863 0.58789 0.3623z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>
