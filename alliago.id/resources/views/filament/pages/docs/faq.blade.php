@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">❓ Pertanyaan yang Sering Diajukan</h2>
        <p class="{{ $p }} text-gray-500">Pertanyaan umum dari anggota tim admin.</p>
    </div>

    @foreach([
        [
            'q' => 'Mengapa anggota staf saya tidak dapat melihat bagian tertentu?',
            'a' => 'Anggota staf memerlukan izin eksplisit untuk setiap bagian dasbor. Pergi ke <strong>Pengaturan → Peran & Izin</strong>, edit peran "staff", dan aktifkan tab grup izin yang sesuai. Ingat: izin hanya memengaruhi dasbor admin, bukan situs web publik.',
        ],
        [
            'q' => 'Bisakah saya menghapus faktur?',
            'a' => 'Tidak — faktur adalah catatan keuangan permanen dan tidak dapat dihapus dari UI admin. Ini disengaja untuk menjaga integritas audit keuangan. Jika Anda perlu membatalkan transaksi, hubungi gateway pembayaran Anda (Xendit) secara langsung.',
        ],
        [
            'q' => 'Bagaimana cara mengubah harga produk visa tanpa memengaruhi pesanan berbayar yang sudah ada?',
            'a' => 'Cukup perbarui harga di <strong>Katalog Visa → Produk Visa</strong>. Jumlah faktur dikunci pada saat pembayaran, jadi faktur yang sudah ada tidak terpengaruh oleh perubahan harga yang Anda buat.',
        ],
        [
            'q' => 'Bagaimana cara memberi tahu klien bahwa aplikasi mereka perlu perubahan?',
            'a' => 'Buka aplikasi, ubah status dokumen yang relevan menjadi <strong>Perlu Revisi</strong>, dan tambahkan catatan Anda di kolom Umpan Balik. Saat Anda menyimpan, pesan otomatis dikirim ke klien dengan umpan balik Anda. Anda juga dapat mengirim pesan bentuk bebas melalui tab Pesan di bagian bawah aplikasi.',
        ],
        [
            'q' => 'Apa yang terjadi ketika semua dokumen disetujui?',
            'a' => 'Status aplikasi otomatis berubah menjadi <strong>Siap</strong>. Klien akan melihat ini di portal mereka. Anda kemudian dapat mengaturnya secara manual ke <strong>Selesai</strong> setelah visa diperoleh.',
        ],
        [
            'q' => 'Bisakah saya menambahkan grup izin atau izin baru?',
            'a' => 'Tidak dari UI — struktur izin ditentukan dalam kode (<code>RolesAndPermissionsSeeder</code>). Hubungi pengembang Anda untuk menambahkan izin sumber daya baru jika Anda menambahkan bagian admin baru.',
        ],
        [
            'q' => 'Bagaimana cara menambahkan anggota staf baru?',
            'a' => 'Pergi ke <strong>Operasional → Pengguna → Pengguna Baru</strong>. Isi nama, email, dan kata sandi sementara mereka. Tetapkan peran <strong>staff</strong> kepada mereka. Kemudian pergi ke <strong>Pengaturan → Peran</strong> dan konfigurasikan bagian mana yang dapat mereka akses.',
        ],
    ] as $faq)
    <div x-data="{ open: false }" class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <button
            @click="open = !open"
            class="flex w-full items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors"
        >
            <span class="text-sm font-semibold text-gray-900">{{ $faq['q'] }}</span>
            <x-heroicon-o-chevron-down
                class="h-4 w-4 text-gray-400 transition-transform duration-200 shrink-0 ml-3"
                ::class="open ? 'rotate-180' : ''"
            />
        </button>
        <div x-show="open" x-collapse class="border-t border-gray-100 px-5 py-4 text-sm text-gray-600 leading-relaxed">
            {!! $faq['a'] !!}
        </div>
    </div>
    @endforeach

</div>
