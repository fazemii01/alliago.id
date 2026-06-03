@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">💰 Keuangan</h2>
        <p class="{{ $p }} text-gray-500">Faktur, pelacakan pembayaran, dan konfigurasi metode pembayaran.</p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Faktur</h3>
        <p class="{{ $p }}">
            Faktur dibuat secara otomatis saat klien menyelesaikan pembayaran. Halaman <strong>Keuangan → Faktur</strong>
            adalah tampilan hanya baca — Anda tidak dapat mengedit atau menghapus faktur untuk menjaga integritas keuangan.
        </p>
        <p class="{{ $p }}">
            Setiap faktur menampilkan: <strong>ID Faktur</strong> (= referensi aplikasi), <strong>Pelanggan</strong>,
            <strong>Deskripsi</strong> (produk visa), <strong>Jumlah</strong>, <strong>Tanggal</strong>, dan <strong>Status</strong> (Dibayar / Belum Dibayar).
        </p>
        <div class="rounded-lg bg-amber-50 border border-amber-100 p-3 text-sm text-amber-800">
            ⚠️ Jumlah faktur <strong>dikunci pada saat pembayaran</strong>. Mengubah harga produk visa di kemudian hari tidak akan memengaruhi faktur riwayat.
        </div>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Status Pembayaran</h3>
        <div class="space-y-2">
            @foreach([
                ['Belum Dibayar (Menunggu Pembayaran)', 'yellow', 'Klien telah melakukan pemesanan tetapi pembayaran belum diterima.'],
                ['Dibayar', 'emerald', 'Pembayaran dikonfirmasi melalui webhook gateway pembayaran. Pemrosesan aplikasi dimulai.'],
            ] as [$status, $color, $desc])
            <div class="flex items-start gap-3">
                <span class="inline-block rounded-md px-2 py-0.5 text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-700 shrink-0 mt-0.5">{{ $status }}</span>
                <p class="text-sm text-gray-500">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Menyaring Faktur</h3>
        <p class="{{ $p }}">
            Gunakan filter <strong>Rentang Tanggal</strong> (Dari / Sampai) pada tabel Faktur untuk mempersempit catatan ke periode waktu tertentu —
            berguna untuk rekonsiliasi bulanan atau membuat laporan.
        </p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Metode Pembayaran</h3>
        <p class="{{ $p }}">
            Buka <strong>Keuangan → Metode Pembayaran</strong> untuk mengonfigurasi opsi pembayaran yang tersedia.
            Alliago.id menggunakan <strong>Xendit</strong> sebagai gateway pembayaran. Webhook ditangani secara otomatis —
            saat Xendit mengonfirmasi pembayaran, status aplikasi diperbarui dan jumlah faktur dikunci.
        </p>
        <ul class="space-y-1.5 text-sm text-gray-600 list-disc list-inside">
            <li>Setiap metode pembayaran memiliki nama, logo, dan tombol alih untuk mengaktifkan/menonaktifkannya.</li>
            <li>Metode pembayaran yang dinonaktifkan disembunyikan dari halaman checkout klien.</li>
            <li>Kredensial gateway (kunci API) dikonfigurasi melalui file <code class="bg-gray-100 px-1 rounded text-xs">.env</code> — hubungi pengembang Anda untuk mengubahnya.</li>
        </ul>
    </div>

</div>
