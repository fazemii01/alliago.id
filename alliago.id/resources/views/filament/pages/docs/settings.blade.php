@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">⚙️ Pengaturan</h2>
        <p class="{{ $p }} text-gray-500">Kelola konten situs yang menghadap publik — FAQ dan Testimoni.</p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">FAQ Situs</h3>
        <p class="{{ $p }}">
            FAQ muncul di halaman arahan publik di bagian akordeon FAQ.
            Anda dapat menambah, mengedit, mengurutkan ulang, dan menghapus entri FAQ dari <strong>Pengaturan → FAQ Situs</strong>.
        </p>
        <ul class="space-y-1.5 text-sm text-gray-600 list-disc list-inside">
            <li>Setiap FAQ memiliki <strong>Pertanyaan</strong> dan <strong>Jawaban</strong>.</li>
            <li>Gunakan kolom <strong>Urutan Sortir</strong> untuk mengontrol urutan tampilan di halaman.</li>
            <li>FAQ yang tidak aktif disembunyikan dari situs publik tetapi dipertahankan di database.</li>
        </ul>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Testimoni</h3>
        <p class="{{ $p }}">
            Testimoni adalah ulasan klien yang ditampilkan di halaman arahan.
            Pergi ke <strong>Pengaturan → Testimoni</strong> untuk mengelolanya.
        </p>
        <ul class="space-y-1.5 text-sm text-gray-600 list-disc list-inside">
            <li>Setiap testimoni memiliki <strong>Nama Klien</strong>, <strong>Konten</strong>, <strong>Peringkat</strong> opsional, dan <strong>URL Avatar</strong> opsional.</li>
            <li>Testimoni yang tidak aktif disembunyikan dari beranda secara otomatis.</li>
            <li>Urutan sortir mengontrol korsel/urutan tampilan.</li>
        </ul>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Pengguna</h3>
        <p class="{{ $p }}">
            <strong>Operasional → Pengguna</strong> memungkinkan Anda mengelola semua akun terdaftar:
        </p>
        <ul class="space-y-1.5 text-sm text-gray-600 list-disc list-inside">
            <li>Melihat semua pengguna (staf admin dan klien).</li>
            <li>Membuat pengguna admin/staf baru dan menetapkan peran mereka.</li>
            <li>Mengedit detail pengguna (nama, email, peran).</li>
            <li>Menghapus pengguna — dengan hati-hati, karena ini tidak dapat dibatalkan.</li>
        </ul>
        <div class="rounded-lg bg-amber-50 border border-amber-100 p-3 text-sm text-amber-800">
            ⚠️ Akun klien (pengguna yang mendaftar melalui portal publik) pada umumnya tidak boleh dihapus — riwayat aplikasi mereka akan hilang.
        </div>
    </div>

</div>
