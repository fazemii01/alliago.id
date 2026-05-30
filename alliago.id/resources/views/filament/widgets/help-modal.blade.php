<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between"
             x-data="{
                 key: 'alliago_help_v3_{{ auth()->id() }}',
                 init() {
                     setTimeout(() => {
                         if (!sessionStorage.getItem(this.key)) {
                             $dispatch('open-modal', { id: 'help-modal' });
                             sessionStorage.setItem(this.key, '1');
                         }
                     }, 500);
                 }
             }">
             
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Bantuan & Panduan Admin</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Baru di sini? Cek dokumentasi untuk memulai.</p>
                </div>
            </div>
            
            <x-filament::button @click="$dispatch('open-modal', { id: 'help-modal' })" color="warning">
                Buka Panduan
            </x-filament::button>
            <x-filament::button @click="$dispatch('open-modal', { id: 'landing-modal' })" color="warning">
                Buka Landing Page
            </x-filament::button>

        </div>

        <x-filament::modal id="help-modal" width="2xl" slide-over>
            <x-slot name="heading">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-8 h-8 bg-amber-100 border border-amber-300 rounded-lg text-lg">👋</span>
                    <div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white" style="line-height:1.2;">Selamat datang di Alliago.id</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-normal mt-1">Berikut ringkasan singkat untuk Anda memulai.</div>
                    </div>
                </div>
            </x-slot>

            <div x-data="{ tab: 'started' }" class="flex flex-col">
                {{-- TAB BAR --}}
                <div class="flex gap-2 border-b border-gray-200 dark:border-white/10 mb-4 px-2">
                    <button
                        @click="tab = 'started'"
                        :class="tab === 'started' ? 'border-amber-500 text-amber-600 dark:text-amber-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="flex items-center gap-2 border-b-2 px-3 py-2 text-sm font-medium transition-colors"
                    >
                        Mulai
                    </button>
                    <button
                        @click="tab = 'changelog'"
                        :class="tab === 'changelog' ? 'border-amber-500 text-amber-600 dark:text-amber-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="flex items-center gap-2 border-b-2 px-3 py-2 text-sm font-medium transition-colors"
                    >
                        Apa yang Baru
                    </button>
                    <button
                        @click="tab = 'docs'"
                        :class="tab === 'docs' ? 'border-amber-500 text-amber-600 dark:text-amber-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="flex items-center gap-2 border-b-2 px-3 py-2 text-sm font-medium transition-colors"
                    >
                        Dokumentasi
                    </button>
                </div>

                {{-- TAB PANELS --}}
                <div class="overflow-y-auto pr-2 max-h-[60vh]">
                    {{-- Getting Started --}}
                    <div x-show="tab === 'started'">
                        <div class="bg-amber-50 border border-amber-200 dark:bg-amber-500/10 dark:border-amber-500/20 rounded-xl p-4 mb-5">
                            <p class="text-sm font-bold text-amber-800 dark:text-amber-400 mb-1">👋 Selamat datang di Panel Admin</p>
                            <p class="text-sm text-amber-700 dark:text-amber-500/80 leading-relaxed m-0">Dasbor ini memungkinkan Anda untuk mengawasi semua operasi visa, aplikasi klien, dan metrik keuangan di satu tempat.</p>
                        </div>
                        
                        <p class="text-sm font-bold text-gray-900 dark:text-white mb-3">Panduan Mulai Cepat</p>
                        
                        @foreach([
                            ['🌐','bg-blue-50 dark:bg-blue-500/10','border-blue-200 dark:border-blue-500/20','Atur Katalog Visa Anda',   'Mulai dengan menambahkan Negara dan Produk Visa di bagian Katalog Visa. Setiap produk memerlukan negara dan harga sebelum dapat ditayangkan.'],
                            ['📋','bg-amber-50 dark:bg-amber-500/10','border-amber-200 dark:border-amber-500/20','Proses Aplikasi Klien', 'Setelah klien mengirimkan pesanan, aplikasi akan muncul di Operasi → Aplikasi. Tinjau dokumen, perbarui status, dan kirim masukan.'],
                            ['💰','bg-emerald-50 dark:bg-emerald-500/10','border-emerald-200 dark:border-emerald-500/20','Lacak Faktur & Pembayaran',   'Pesanan yang dibayar menghasilkan faktur secara otomatis. Kunjungi Keuangan → Faktur. Konfigurasikan opsi pembayaran di bawah Keuangan → Metode Pembayaran.'],
                            ['🔐','bg-purple-50 dark:bg-purple-500/10','border-purple-200 dark:border-purple-500/20','Konfigurasi Akses Tim',       'Tambahkan staf di bawah Operasi → Pengguna. Kemudian atur bagian dasbor mana yang dapat diakses oleh setiap peran di Pengaturan → Peran & Izin.'],
                        ] as [$emoji, $bg, $border, $title, $desc])
                        <div class="flex items-start gap-4 p-4 bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-xl shadow-sm mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $bg }} border {{ $border }} text-lg flex-shrink-0">{{ $emoji }}</div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">{{ $title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 m-0 leading-relaxed">{{ $desc }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Changelog --}}
                    <div x-show="tab === 'changelog'">
                        <p class="text-sm font-bold text-gray-900 dark:text-white mb-5">Pembaruan Terbaru</p>
                        
                        @foreach([
                            ['v1.5.0','Mei 9, 2026',  'Terbaru', 'bg-emerald-100 dark:bg-emerald-500/20','text-emerald-800 dark:text-emerald-400',['🔐 Peran & Izin dikelompokkan berdasarkan kategori (Operasi, Keuangan, Katalog, Pengaturan).','🗑️ Tindakan Hapus pada Aplikasi — hapus baris, header, dan hapus massal.','🏳️ Memperbaiki emoji bendera yang hilang pada marquee negara yang dianimasikan.','📖 Modal selamat datang + Bantuan & Panduan + Pusat Dokumentasi.']],
                            ['v1.4.0','Mei 5, 2026',  'Fitur','bg-purple-100 dark:bg-purple-500/20','text-purple-800 dark:text-purple-400',['📊 Widget statistik dasbor dengan pelacakan tren.','💳 Gateway pembayaran Xendit dengan penanganan webhook otomatis.','🔔 Pesan revisi otomatis dikirim ke klien saat dokumen ditandai.']],
                            ['v1.3.0','Apr 28, 2026', 'Fitur','bg-blue-100 dark:bg-blue-500/20','text-blue-800 dark:text-blue-400',['💬 Perpesanan waktu nyata antara admin dan klien.','🌟 Halaman manajemen Testimoni dan FAQ Situs.','🗺️ Strip marquee negara yang dianimasikan di halaman arahan.']],
                        ] as [$ver, $date, $tag, $tagBg, $tagClr, $changes])
                        <div class="relative pl-5 border-l-2 border-gray-200 dark:border-gray-700 mb-6">
                            <div class="absolute -left-[5px] top-1.5 w-2 h-2 rounded-full border-2 border-amber-500 bg-white dark:bg-gray-900"></div>
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $ver }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $tagBg }} {{ $tagClr }}">{{ $tag }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $date }}</span>
                            </div>
                            @foreach($changes as $c)
                            <div class="flex items-start gap-2 mb-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                <span class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $c }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>

                    {{-- Documentation --}}
                    <div x-show="tab === 'docs'">
                        <div class="bg-gray-900 dark:bg-gray-800 rounded-xl p-5 mb-5">
                            <p class="text-base font-bold text-white mb-1">📖 Basis Pengetahuan</p>
                            <p class="text-sm text-gray-400 mb-4">Panduan terperinci untuk setiap fitur admin.</p>
                            <a href="{{ route('filament.admin.pages.documentation-page') }}" @click="$dispatch('close-modal', { id: 'help-modal' })" class="flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold py-2 px-4 rounded-lg transition-colors">
                                ↗ Buka Pusat Dokumentasi
                            </a>
                        </div>
                        
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">Tautan Cepat</p>
                        @foreach([
                            ['📋','Aplikasi & Tinjauan Dokumen','Proses pesanan, tinjau dokumen, pesan klien.'],
                            ['🗺️','Manajemen Katalog Visa',        'Negara, produk visa, harga.'],
                            ['💰','Keuangan & Pembayaran',             'Faktur, gateway pembayaran, laporan.'],
                            ['🔐','Peran & Izin',            'Kontrol akses tim berdasarkan kategori.'],
                            ['⚙️','Pengaturan & Konten Situs',        'FAQ, testimoni, manajemen pengguna.'],
                        ] as [$e, $t, $d])
                        <a href="{{ route('filament.admin.pages.documentation-page') }}" @click="$dispatch('close-modal', { id: 'help-modal' })" class="flex items-center gap-3 bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 hover:border-amber-300 dark:hover:border-amber-500/50 rounded-xl p-3 mb-2 transition-colors">
                            <span class="text-xl flex-shrink-0">{{ $e }}</span>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900 dark:text-white mb-0.5">{{ $t }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 m-0">{{ $d }}</p>
                            </div>
                            <span class="text-gray-300 dark:text-gray-600 font-bold">›</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex items-center justify-between w-full">
                    <p class="text-sm text-gray-500 dark:text-gray-400 m-0">
                        Butuh bantuan lebih lanjut? 
                        <a href="{{ route('filament.admin.pages.help-page') }}" @click="$dispatch('close-modal', { id: 'help-modal' })" class="text-amber-600 dark:text-amber-500 font-semibold hover:underline">Lihat panduan lengkap</a>
                    </p>
                    <x-filament::button @click="$dispatch('close-modal', { id: 'help-modal' })" color="warning">
                        Mulai &rarr;
                    </x-filament::button>
                </div>
            </x-slot>
        </x-filament::modal>
    </x-filament::section>
</x-filament-widgets::widget>
