@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">🗺️ Katalog Visa</h2>
        <p class="{{ $p }} text-gray-500">Kelola negara dan produk visa yang dapat dipesan klien.</p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Negara</h3>
        <p class="{{ $p }}">
            Negara membentuk dasar katalog. Setiap Produk Visa harus dikaitkan dengan sebuah Negara.
            Setiap negara memiliki <strong>nama</strong>, <strong>slug</strong>, <strong>emoji bendera</strong>, <strong>kode ISO</strong>, dan tombol alih <strong>aktif</strong>.
        </p>
        <ul class="space-y-1.5 text-sm text-gray-600 list-disc list-inside">
            <li>Emoji bendera disemai secara otomatis berdasarkan nama negara — Anda dapat menggantinya di formulir edit.</li>
            <li>Negara yang tidak aktif disembunyikan dari situs web publik tetapi tetap ada dalam database.</li>
            <li>Menghapus negara juga akan memengaruhi semua produk visa yang tertaut dengannya — berhati-hatilah.</li>
        </ul>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Produk Visa</h3>
        <p class="{{ $p }}">
            Produk Visa adalah layanan individual yang dapat dibeli klien. Setiap produk milik satu Negara dan mencakup:
        </p>
        <div class="grid grid-cols-2 gap-2 text-sm">
            @foreach([
                ['Nama', 'Nama layanan lengkap yang ditampilkan kepada klien.'],
                ['Slug', 'Pengenal yang ramah URL (dibuat secara otomatis).'],
                ['Harga Dasar', 'Harga standar sebelum diskon apa pun.'],
                ['Harga Diskon', 'Harga diskon opsional yang ditampilkan dengan coretan pada harga dasar.'],
                ['Waktu Pemrosesan', 'mis. "3-5 hari kerja" — ditampilkan di halaman produk.'],
                ['Deskripsi Singkat', 'Ringkasan singkat yang ditampilkan di kartu katalog.'],
                ['Urutan Sortir', 'Mengontrol urutan tampilan dalam katalog.'],
                ['Aktif', 'Alihkan visibilitas di situs web publik.'],
            ] as [$field, $desc])
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-3">
                <p class="font-semibold text-gray-800">{{ $field }}</p>
                <p class="text-gray-500 mt-0.5 text-xs">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Kiat Penetapan Harga</h3>
        <ul class="space-y-2 text-sm text-gray-600 list-disc list-inside">
            <li>Jika <strong>Harga Diskon</strong> diatur dan lebih rendah dari Harga Dasar, ini akan ditampilkan sebagai harga aktif dengan harga aslinya dicoret.</li>
            <li>Saat klien membeli, jumlah faktur dikunci pada saat pembayaran — perubahan harga di masa mendatang tidak akan memengaruhi faktur yang ada.</li>
            <li>Semua harga dalam <strong>IDR (Rupiah Indonesia)</strong>.</li>
        </ul>
    </div>

</div>
