@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">🔐 Peran & Izin</h2>
        <p class="{{ $p }} text-gray-500">Kontrol apa yang dapat dilihat dan dilakukan oleh setiap anggota staf di dalam dasbor admin.</p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Penting: Cakupan Izin</h3>
        <div class="rounded-lg bg-blue-50 border border-blue-100 p-3 text-sm text-blue-800">
            🔒 Izin <strong>hanya mengontrol dasbor admin</strong>. Izin tidak berpengaruh sama sekali pada situs web yang dilihat publik
            (halaman arahan, katalog visa, checkout klien, dll.). Pengguna tanpa peran admin sama sekali tidak dapat mengakses dasbor.
        </div>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Peran Bawaan</h3>
        <div class="space-y-3">
            @foreach([
                ['admin', 'danger', 'Akses penuh ke semua fitur dasbor. Tidak dapat dihapus. Secara otomatis menerima semua izin.'],
                ['staff', 'warning', 'Dimulai dengan nol izin. Berikan akses ke bagian tertentu menggunakan editor peran.'],
            ] as [$role, $color, $desc])
            <div class="flex items-start gap-3">
                <span class="inline-block rounded-md px-2.5 py-1 text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-700 shrink-0 font-mono">{{ $role }}</span>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
        <p class="{{ $p }} text-gray-400 text-xs">Anda dapat membuat peran kustom tambahan (misalnya <code>manajer-keuangan</code>, <code>agen-dukungan</code>) dari daftar Peran.</p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Grup Izin</h3>
        <p class="{{ $p }} mb-3">Izin diatur ke dalam 4 kategori yang mencerminkan navigasi admin:</p>
        <div class="grid grid-cols-2 gap-3">
            @foreach([
                ['Operasional', 'amber', 'heroicon-o-clipboard-document-list', 'Pemrosesan aplikasi, Manajemen pengguna'],
                ['Keuangan',    'emerald','heroicon-o-banknotes',              'Melihat faktur, Manajemen metode pembayaran'],
                ['Katalog',    'blue',   'heroicon-o-globe-alt',              'Produk visa, Manajemen negara'],
                ['Pengaturan',   'purple', 'heroicon-o-cog-6-tooth',           'FAQ, Testimoni, Manajemen peran'],
            ] as [$group, $color, $icon, $covers])
            <div class="rounded-lg border border-{{ $color }}-100 bg-{{ $color }}-50 p-3">
                <div class="flex items-center gap-2 mb-1">
                    <x-dynamic-component :component="$icon" class="h-4 w-4 text-{{ $color }}-600" />
                    <span class="text-sm font-bold text-{{ $color }}-800">{{ $group }}</span>
                </div>
                <p class="text-xs text-{{ $color }}-700">{{ $covers }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Mengedit Izin Peran</h3>
        <ol class="space-y-2 text-sm text-gray-600 list-none">
            @foreach([
                'Pergi ke <strong>Pengaturan → Peran & Izin</strong>.',
                'Klik <strong>Edit</strong> pada peran yang ingin Anda konfigurasikan.',
                'Gunakan bilah tab untuk beralih antara grup izin (Operasional / Keuangan / Katalog / Pengaturan).',
                'Centang izin individual, atau klik <strong>Pilih semua</strong> untuk memberikan/mencabut seluruh grup sekaligus.',
                'Simpan perubahan.',
            ] as $i => $step)
            <li class="flex items-start gap-3">
                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-purple-100 text-xs font-bold text-purple-700">{{ $i + 1 }}</span>
                <span>{!! $step !!}</span>
            </li>
            @endforeach
        </ol>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Lencana Cakupan Tabel</h3>
        <p class="{{ $p }}">
            Tabel daftar Peran menampilkan <strong>lencana cakupan X/Y</strong> untuk setiap grup izin.
            Misalnya, <span class="rounded px-1.5 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold">5/5</span> berarti akses penuh,
            <span class="rounded px-1.5 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-bold">2/5</span> berarti sebagian,
            dan <span class="rounded px-1.5 py-0.5 bg-gray-100 text-gray-600 text-xs font-bold">0/5</span> berarti tidak ada akses untuk bagian tersebut.
            Gunakan filter <strong>"Memiliki akses ke kategori"</strong> untuk menemukan peran berdasarkan bagian dasbor mana yang dapat mereka akses.
        </p>
    </div>

</div>
