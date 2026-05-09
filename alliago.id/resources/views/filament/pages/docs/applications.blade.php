@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; $badge = 'inline-block rounded-md px-2 py-0.5 text-xs font-semibold'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">📋 Aplikasi</h2>
        <p class="{{ $p }} text-gray-500">Segala hal yang berkaitan dengan pemrosesan aplikasi visa klien.</p>
    </div>

    {{-- What is an Application --}}
    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Apa itu Aplikasi?</h3>
        <p class="{{ $p }}">
            Sebuah <strong>Aplikasi</strong> dibuat saat klien membeli produk visa melalui portal klien.
            Ini berisi informasi pribadi klien, dokumen yang diunggah, status pembayaran, dan utas komunikasi antara klien dan admin.
        </p>
    </div>

    {{-- Application Statuses --}}
    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Status Aplikasi</h3>
        <p class="{{ $p }} mb-3">Setiap aplikasi bergerak melalui tahapan berikut:</p>
        <div class="space-y-2">
            @foreach([
                ['Draf',             'gray',    'Klien belum menyelesaikan pembayaran.'],
                ['Menunggu Pembayaran',   'yellow',  'Pesanan dilakukan, menunggu konfirmasi pembayaran.'],
                ['Menunggu Dokumen', 'orange',  'Pembayaran dikonfirmasi, menunggu klien mengunggah dokumen yang diperlukan.'],
                ['Sedang Ditinjau',      'blue',    'Admin sedang aktif meninjau dokumen yang dikirimkan.'],
                ['Perlu Revisi',    'red',     'Admin telah menandai satu atau lebih dokumen untuk diunggah ulang.'],
                ['Siap',             'emerald', 'Semua dokumen disetujui — aplikasi visa siap diserahkan ke kedutaan.'],
                ['Selesai',         'green',   'Visa telah diperoleh dan aplikasi ditutup.'],
            ] as [$status, $color, $desc])
            <div class="flex items-start gap-3">
                <span class="{{ $badge }} bg-{{ $color }}-100 text-{{ $color }}-700 shrink-0 mt-0.5">{{ $status }}</span>
                <p class="text-sm text-gray-500">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Processing an Application --}}
    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Cara Memproses Aplikasi</h3>
        <ol class="space-y-3 list-none">
            @foreach([
                'Buka <strong>Operasi → Aplikasi</strong> dan klik tombol <strong>Proses</strong> di baris mana pun.',
                'Tinjau setiap dokumen dengan mengklik tautan <strong>Lihat Dokumen</strong> di sebelahnya.',
                'Ubah status setiap dokumen menjadi <strong>Disetujui</strong>, <strong>Perlu Revisi</strong>, atau biarkan tertunda.',
                'Jika revisi diperlukan, tambahkan catatan di bidang <em>Umpan Balik</em> — ini secara otomatis dikirim ke klien sebagai pesan.',
                'Status Aplikasi secara keseluruhan diperbarui otomatis berdasarkan status dokumen (mis. semua disetujui → <strong>Siap</strong>).',
                'Anda juga dapat mengganti status secara manual dari dropdown status di bagian atas formulir.',
                'Simpan menggunakan tombol <strong>Simpan perubahan</strong> di bagian bawah.',
            ] as $i => $step)
            <li class="flex items-start gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-100 text-xs font-bold text-amber-700">{{ $i + 1 }}</span>
                <p class="text-sm text-gray-600 leading-relaxed">{!! $step !!}</p>
            </li>
            @endforeach
        </ol>
    </div>

    {{-- Messaging --}}
    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Perpesanan Klien</h3>
        <p class="{{ $p }}">
            Setiap aplikasi memiliki tab <strong>Pesan</strong> di bagian bawah halaman edit (Manajer Relasi).
            Admin dapat mengirim pesan langsung ke klien di sini. Jumlah pesan yang belum dibaca muncul sebagai lencana dalam tabel daftar Aplikasi.
            Saat Anda membuka aplikasi, semua pesan klien yang belum dibaca secara otomatis ditandai sudah dibaca.
        </p>
        <div class="rounded-lg bg-blue-50 border border-blue-100 p-3 text-sm text-blue-800">
            💡 Saat Anda mengatur dokumen ke <strong>Perlu Revisi</strong> dan menyimpannya, sebuah pesan secara otomatis dikirim ke klien dengan nama dokumen dan catatan umpan balik Anda.
        </div>
    </div>

    {{-- Deleting --}}
    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Menghapus Aplikasi</h3>
        <p class="{{ $p }}">
            Aplikasi dapat dihapus dari daftar tabel menggunakan tombol aksi <strong>Hapus</strong>,
            atau dari dalam halaman edit menggunakan tombol <strong>Hapus</strong> di header halaman.
            Penghapusan massal juga tersedia dengan memilih beberapa baris dalam tabel.
            Hanya admin dengan izin <code class="bg-gray-100 px-1 rounded text-xs">applications.delete</code> yang dapat menghapus.
        </p>
    </div>

</div>
