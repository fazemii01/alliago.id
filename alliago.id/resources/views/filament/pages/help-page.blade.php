<x-filament-panels::page>
    {{-- 
        Help & Guides Page
        Three tabs: Getting Started · What's New · Documentation
        Uses Alpine.js (bundled with Filament) for tab switching.
    --}}
    <div
        x-data="{ activeTab: 'started' }"
        class="max-w-3xl mx-auto"
    >
        {{-- ── Page Hero ─────────────────────────────────────────────────── --}}
        <div class="mb-8 flex items-center gap-4 rounded-2xl border border-amber-100 bg-amber-50 p-6">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white text-3xl shadow-sm ring-1 ring-amber-100">
                👋
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Selamat datang di Admin Alliago.id</h2>
                <p class="mt-1 text-sm text-amber-800/70">
                    Halaman ini memiliki semua yang Anda butuhkan — panduan mulai cepat, pembaruan terbaru, dan dokumentasi lengkap.
                </p>
            </div>
        </div>

        {{-- ── Tab Bar ────────────────────────────────────────────────────── --}}
        <div class="mb-6 flex gap-1 rounded-xl border border-gray-200 bg-gray-100 p-1">
            <button
                @click="activeTab = 'started'"
                :class="activeTab === 'started'
                    ? 'bg-white text-amber-600 shadow-sm ring-1 ring-gray-200'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all"
            >
                <x-heroicon-o-play-circle class="h-4 w-4" />
                Mulai
            </button>
            <button
                @click="activeTab = 'changelog'"
                :class="activeTab === 'changelog'
                    ? 'bg-white text-amber-600 shadow-sm ring-1 ring-gray-200'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all"
            >
                <x-heroicon-o-clock class="h-4 w-4" />
                Apa yang Baru
            </button>
            <button
                @click="activeTab = 'docs'"
                :class="activeTab === 'docs'
                    ? 'bg-white text-amber-600 shadow-sm ring-1 ring-gray-200'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all"
            >
                <x-heroicon-o-book-open class="h-4 w-4" />
                Dokumentasi
            </button>
        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- TAB 1 · GETTING STARTED                                        --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'started'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            <h3 class="mb-4 text-base font-bold text-gray-900">Panduan Mulai Cepat</h3>

            <div class="relative space-y-4 before:absolute before:inset-y-0 before:left-5 before:w-px before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">

                @foreach([
                    [
                        'step' => '1',
                        'color' => 'blue',
                        'icon' => 'heroicon-o-globe-alt',
                        'title' => 'Atur Katalog Visa Anda',
                        'desc' => 'Mulai dengan menambahkan Negara dan Produk Visa di bawah bagian Katalog Visa. Setiap produk memerlukan negara, harga, dan waktu pemrosesan sebelum muncul di situs publik.',
                        'link' => '/admin/visa-products',
                        'linkLabel' => 'Buka Produk Visa →',
                    ],
                    [
                        'step' => '2',
                        'color' => 'amber',
                        'icon' => 'heroicon-o-clipboard-document-list',
                        'title' => 'Proses Aplikasi Klien',
                        'desc' => 'Setelah klien mengirimkan pesanan, aplikasi akan muncul di Operasi → Aplikasi. Tinjau dokumen, perbarui status, dan berikan masukan langsung di halaman edit aplikasi.',
                        'link' => '/admin/applications',
                        'linkLabel' => 'Buka Aplikasi →',
                    ],
                    [
                        'step' => '3',
                        'color' => 'emerald',
                        'icon' => 'heroicon-o-banknotes',
                        'title' => 'Lacak Faktur & Pembayaran',
                        'desc' => 'Semua pesanan yang dibayar menghasilkan faktur secara otomatis. Buka Keuangan → Faktur untuk melihat status pembayaran. Konfigurasikan gateway pembayaran di bawah Keuangan → Metode Pembayaran.',
                        'link' => '/admin/invoices',
                        'linkLabel' => 'Buka Faktur →',
                    ],
                    [
                        'step' => '4',
                        'color' => 'purple',
                        'icon' => 'heroicon-o-users',
                        'title' => 'Kelola Akses Tim',
                        'desc' => 'Tambahkan akun staf di bawah Operasi → Pengguna. Tetapkan mereka ke peran "staff", kemudian konfigurasikan bagian dasbor mana yang dapat mereka akses di bawah Pengaturan → Peran & Izin.',
                        'link' => '/admin/roles',
                        'linkLabel' => 'Buka Peran →',
                    ],
                    [
                        'step' => '5',
                        'color' => 'rose',
                        'icon' => 'heroicon-o-star',
                        'title' => 'Sesuaikan Konten Situs',
                        'desc' => 'Kelola konten halaman arahan publik — FAQ dan Testimoni — dari Pengaturan. Ini langsung masuk ke beranda Alliago.id tanpa perubahan kode.',
                        'link' => '/admin/site-faqs',
                        'linkLabel' => 'Buka FAQ Situs →',
                    ],
                ] as $item)
                <div class="relative flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                    {{-- Step circle --}}
                    <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-{{ $item['color'] }}-200 bg-{{ $item['color'] }}-50 text-sm font-bold text-{{ $item['color'] }}-600">
                        {{ $item['step'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900">{{ $item['title'] }}</h4>
                        <p class="mt-1 text-sm leading-relaxed text-gray-500">{{ $item['desc'] }}</p>
                        <a href="{{ $item['link'] }}" class="mt-2 inline-flex items-center text-xs font-semibold text-amber-600 hover:underline">
                            {{ $item['linkLabel'] }}
                        </a>
                    </div>
                </div>
                @endforeach

            </div>

            {{-- Tips box --}}
            <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
                <strong>💡 Kiat Pro:</strong> Gunakan statistik <strong>Dasbor</strong> di bagian atas untuk menemukan aplikasi yang membutuhkan perhatian segera — jumlah oranye "Perlu Perhatian" berarti klien sedang menunggu.
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- TAB 2 · CHANGELOG / WHAT'S NEW                                 --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'changelog'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            <h3 class="mb-4 text-base font-bold text-gray-900">Pembaruan Terbaru</h3>

            <div class="space-y-8">
                @foreach([
                    [
                        'version' => 'v1.5.0',
                        'date' => 'Mei 9, 2026',
                        'tag' => 'Terbaru',
                        'tagClass' => 'bg-emerald-100 text-emerald-700',
                        'dotClass' => 'border-amber-500',
                        'changes' => [
                            ['icon' => '🔐', 'text' => 'Membangun ulang Peran & Izin dengan kategori yang dikelompokkan (Operasi, Keuangan, Katalog, Pengaturan) — lebih mudah dikelola.'],
                            ['icon' => '🗑️', 'text' => 'Menambahkan tindakan Hapus pada Aplikasi — hapus tunggal pada baris tabel dan hapus massal.'],
                            ['icon' => '🏳️', 'text' => 'Memperbaiki emoji bendera negara yang hilang pada marquee negara yang dianimasikan di halaman arahan.'],
                            ['icon' => '📖', 'text' => 'Menambahkan halaman Bantuan & Panduan ini dengan catatan perubahan, mulai cepat, dan Pusat Dokumentasi lengkap.'],
                        ],
                    ],
                    [
                        'version' => 'v1.4.0',
                        'date' => 'Mei 5, 2026',
                        'tag' => 'Fitur',
                        'tagClass' => 'bg-purple-100 text-purple-700',
                        'dotClass' => 'border-gray-300',
                        'changes' => [
                            ['icon' => '📊', 'text' => 'Menambahkan widget statistik analitis ke dasbor Filament (Total Aplikasi, Perlu Perhatian, Total Klien).'],
                            ['icon' => '💳', 'text' => 'Mengintegrasikan gateway pembayaran Xendit dengan penanganan webhook otomatis dan penguncian jumlah faktur.'],
                            ['icon' => '🔔', 'text' => 'Pesan permintaan revisi otomatis dikirim ke klien saat admin menandai dokumen sebagai "perlu revisi".'],
                        ],
                    ],
                    [
                        'version' => 'v1.3.0',
                        'date' => 'April 15, 2026',
                        'tag' => 'Fitur',
                        'tagClass' => 'bg-blue-100 text-blue-700',
                        'dotClass' => 'border-gray-300',
                        'changes' => [
                            ['icon' => '💬', 'text' => 'Meluncurkan perpesanan waktu nyata antara admin dan klien di dalam setiap aplikasi.'],
                            ['icon' => '🌟', 'text' => 'Manajemen Testimoni dan FAQ Situs ditambahkan ke bagian Pengaturan admin.'],
                            ['icon' => '🗺️', 'text' => 'Strip marquee negara yang dianimasikan ditambahkan ke halaman arahan dengan emoji bendera.'],
                        ],
                    ],
                    [
                        'version' => 'v1.2.0',
                        'date' => 'April 1, 2026',
                        'tag' => 'Pembaruan',
                        'tagClass' => 'bg-slate-100 text-slate-600',
                        'dotClass' => 'border-gray-300',
                        'changes' => [
                            ['icon' => '🎨', 'text' => 'Desain ulang tema terang penuh untuk portal klien dan halaman arahan.'],
                            ['icon' => '🚀', 'text' => 'Dikerahkan ke produksi di Nginx dengan konfigurasi perutean yang tepat.'],
                            ['icon' => '🔑', 'text' => 'Mode gelap panel admin dinonaktifkan — dipaksa menggunakan tema terang untuk konsistensi.'],
                        ],
                    ],
                ] as $entry)
                <div class="relative pl-6 before:absolute before:inset-y-0 before:left-[7px] before:w-px before:bg-gray-200">
                    {{-- Timeline dot --}}
                    <div class="absolute left-0 top-1.5 h-3.5 w-3.5 rounded-full border-2 bg-white {{ $entry['dotClass'] }}"></div>

                    <div class="mb-2 flex items-center gap-3">
                        <span class="text-base font-bold text-gray-900">{{ $entry['version'] }}</span>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $entry['tagClass'] }}">
                            {{ $entry['tag'] }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $entry['date'] }}</span>
                    </div>

                    <ul class="space-y-2">
                        @foreach($entry['changes'] as $change)
                        <li class="flex items-start gap-2 text-sm text-gray-600">
                            <span class="shrink-0 text-base leading-5">{{ $change['icon'] }}</span>
                            <span class="leading-relaxed">{{ $change['text'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- TAB 3 · DOCUMENTATION                                          --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'docs'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Hero banner --}}
            <div class="mb-6 rounded-2xl bg-gray-900 p-6 text-white">
                <div class="flex items-center gap-3 mb-3">
                    <x-heroicon-o-book-open class="h-8 w-8 text-amber-400" />
                    <div>
                        <h3 class="text-lg font-bold">Pusat Dokumentasi</h3>
                        <p class="text-sm text-gray-400">Panduan terperinci untuk setiap fitur di dasbor admin.</p>
                    </div>
                </div>
                <a
                    href="{{ route('filament.admin.pages.documentation-page') }}"
                    class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-amber-600"
                >
                    <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                    Buka Pusat Dokumentasi Lengkap
                </a>
            </div>

            {{-- Quick links --}}
            <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-500">Tautan Cepat</h4>

            <div class="space-y-3">
                @foreach([
                    ['icon' => '📋', 'color' => 'amber', 'title' => 'Aplikasi & Tinjauan Dokumen', 'desc' => 'Cara memproses aplikasi, meninjau dokumen, dan berkomunikasi dengan klien.', 'anchor' => '#applications'],
                    ['icon' => '🗺️', 'color' => 'blue', 'title' => 'Manajemen Katalog Visa', 'desc' => 'Membuat negara, produk visa, harga, dan menetapkan waktu pemrosesan.', 'anchor' => '#catalog'],
                    ['icon' => '💰', 'color' => 'emerald', 'title' => 'Keuangan & Pembayaran', 'desc' => 'Memahami faktur, pengaturan gateway pembayaran, dan pelaporan keuangan.', 'anchor' => '#finance'],
                    ['icon' => '🔐', 'color' => 'purple', 'title' => 'Peran & Izin', 'desc' => 'Mengelola pengguna admin, peran, dan grup izin granular.', 'anchor' => '#roles'],
                    ['icon' => '⚙️', 'color' => 'rose', 'title' => 'Konten & Pengaturan Situs', 'desc' => 'Mengelola FAQ publik, testimoni, dan pengaturan seluruh situs.', 'anchor' => '#settings'],
                ] as $doc)
                <a
                    href="{{ route('filament.admin.pages.documentation-page') }}{{ $doc['anchor'] }}"
                    class="group flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-4 shadow-sm transition-all hover:border-amber-200 hover:shadow-md"
                >
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-{{ $doc['color'] }}-50 text-xl ring-1 ring-{{ $doc['color'] }}-100">
                        {{ $doc['icon'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h5 class="text-sm font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">
                                {{ $doc['title'] }}
                            </h5>
                            <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-300 group-hover:text-amber-500 transition-colors" />
                        </div>
                        <p class="mt-0.5 text-xs leading-relaxed text-gray-500">{{ $doc['desc'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-8 flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 px-5 py-4 text-sm text-gray-500">
            <span>Butuh bantuan lebih lanjut? Hubungi administrator sistem Anda.</span>
            <a href="{{ route('filament.admin.pages.documentation-page') }}" class="font-semibold text-amber-600 hover:underline">
                Dokumentasi Lengkap →
            </a>
        </div>
    </div>
</x-filament-panels::page>
