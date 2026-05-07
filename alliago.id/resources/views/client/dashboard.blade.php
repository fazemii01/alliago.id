<x-layouts.app title="Client Area | Alliago.id">
    <x-home.header />

    <div class="min-h-screen bg-slate-50 text-slate-900 pt-24 pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#0361fc]">Area Klien</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl text-slate-900">Kelola Aplikasi & Dokumen Anda</h1>
                    <p class="mt-3 max-w-3xl text-lg text-slate-600">Selamat datang kembali, {{ $client->name }}. Area klien ini adalah pusat kontrol Anda untuk melacak aplikasi visa, memperbarui informasi pribadi, dan berkomunikasi dengan tim kami.</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('visa.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#0361fc] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20">Jelajahi Visa</a>
                    <form method="POST" action="{{ route('client.logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-50 transition-transform duration-500 group-hover:scale-150"></div>
                    <div class="relative">
                        <p class="text-sm font-bold text-slate-500">Aplikasi Aktif</p>
                        <p class="mt-2 text-4xl font-extrabold text-slate-900">{{ $orderStats['activeOrders'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">Total aplikasi visa Anda yang sedang dalam proses.</p>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-50 transition-transform duration-500 group-hover:scale-150"></div>
                    <div class="relative">
                        <p class="text-sm font-bold text-slate-500">Dokumen Perlu Perhatian</p>
                        <p class="mt-2 text-4xl font-extrabold text-slate-900">{{ $orderStats['documentsNeedAttention'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">Dokumen yang memerlukan revisi atau belum diunggah.</p>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-rose-50 transition-transform duration-500 group-hover:scale-150"></div>
                    <div class="relative">
                        <p class="text-sm font-bold text-slate-500">Pesan Admin Baru</p>
                        <p class="mt-2 text-4xl font-extrabold text-slate-900">{{ $orderStats['unreadAdminMessages'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">Pesan dari tim ahli kami terkait aplikasi Anda.</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-8 xl:grid-cols-[1.35fr_0.95fr]">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Aplikasi Visa Saya</h2>
                            <p class="mt-1 text-sm text-slate-500">Pantau proses, kelengkapan dokumen, dan status aplikasi visa Anda di sini.</p>
                        </div>
                        <a href="{{ route('visa.index') }}" class="text-sm font-bold text-[#0361fc] hover:text-blue-700 whitespace-nowrap">Lihat Layanan Visa &rarr;</a>
                    </div>

                    <div class="mt-8 grid gap-6">
                        @forelse ($applications as $application)
                            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-black/5 transition hover:shadow-md">
                                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <p class="text-xs font-bold tracking-wider text-slate-500">ID: {{ $application->reference_number }}</p>
                                        <h3 class="mt-1 text-xl font-bold text-slate-900">{{ $application->visaProduct->name }}</h3>
                                        <p class="mt-1 text-sm text-slate-600">{{ $application->visaProduct->country->name }} · Pemohon: <span class="font-medium text-slate-900">{{ $application->traveler_name }}</span></p>
                                        <div class="mt-4 flex gap-3">
                                            <a href="{{ route('client.applications.show', $application) }}" class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-200 transition">
                                                Detail Aplikasi
                                            </a>
                                            <a href="{{ route('client.applications.invoice', $application) }}" class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                                                Lihat Invoice
                                            </a>
                                        </div>
                                    </div>
                                    <span class="inline-flex shrink-0 items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-700 ring-1 ring-inset ring-slate-500/10">
                                        {{ str_replace('_', ' ', $application->status) }}
                                    </span>
                                </div>

                                <div class="mt-6 grid gap-3 sm:grid-cols-3 text-sm">
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-slate-600">
                                        <span class="font-bold text-slate-900">{{ $application->documents->whereIn('status', ['pending_upload', 'needs_revision'])->count() }}</span> dokumen perlu diunggah
                                    </div>
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-slate-600">
                                        <span class="font-bold text-slate-900">{{ $application->messages->where('is_admin', true)->count() }}</span> pesan admin
                                    </div>
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-slate-600">
                                        Terakhir diupdate <span class="font-bold text-slate-900">{{ $application->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                                    <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                                        <div class="flex items-center justify-between gap-4">
                                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Checklist Dokumen</h4>
                                            <span class="text-[10px] uppercase font-bold text-slate-400">PDF/JPG/PNG max 5MB</span>
                                        </div>

                                        <div class="mt-4 space-y-3">
                                            @if (in_array($application->status, ['pending_payment', 'payment_failed']))
                                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-center text-sm text-slate-500">
                                                    Silakan selesaikan pembayaran untuk mengunggah dokumen.
                                                    <div class="mt-2">
                                                        <a href="{{ route('client.applications.checkout', $application) }}" class="inline-flex items-center justify-center rounded-full bg-[#0361fc] px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-blue-700 transition">Bayar Sekarang</a>
                                                    </div>
                                                </div>
                                            @else
                                                @foreach ($application->documents as $document)
                                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                                            <div>
                                                                <p class="font-bold text-slate-900">{{ $document->label }}</p>
                                                                <p class="mt-0.5 text-[10px] font-bold uppercase tracking-wider text-[#0361fc]">
                                                                    {{ str_replace('_', ' ', $document->status) }}
                                                                </p>
                                                                @if ($document->admin_feedback)
                                                                    <p class="mt-2 text-xs font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">Catatan Admin: {{ $document->admin_feedback }}</p>
                                                                @endif
                                                            </div>

                                                            <form method="POST" action="{{ route('client.documents.store', [$application, $document]) }}" enctype="multipart/form-data" class="flex flex-col gap-2 md:items-end">
                                                                @csrf
                                                                <input type="file" name="document" class="block w-full max-w-[200px] text-xs text-slate-500 file:mr-2 file:rounded-full file:border-0 file:bg-slate-200 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-slate-700 hover:file:bg-slate-300">
                                                                <button type="submit" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:border-slate-300">
                                                                    {{ $document->file_path ? 'Ubah' : 'Unggah' }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Timeline Status</h4>
                                        <div class="mt-4 relative space-y-4 before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                                            @forelse ($application->statusLogs->take(4) as $statusLog)
                                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                                    <div class="flex items-center justify-center w-5 h-5 rounded-full border-2 border-white bg-slate-200 text-slate-500 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2"></div>
                                                    <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] rounded-2xl border border-slate-100 bg-slate-50 p-3 shadow-sm">
                                                        <p class="text-xs font-bold text-slate-900">{{ str_replace('_', ' ', $statusLog->to_status) }}</p>
                                                        @if ($statusLog->message)
                                                            <p class="mt-1 text-xs text-slate-600">{{ $statusLog->message }}</p>
                                                        @endif
                                                        <p class="mt-1 text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ $statusLog->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-center text-sm text-slate-500">
                                                    Belum ada pembaruan status.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 rounded-3xl border border-slate-100 bg-slate-50 p-5 shadow-sm">
                                    <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Pesan dari Admin</h4>
                                        <span class="text-xs text-slate-500">Gunakan fitur ini jika ada pertanyaan terkait dokumen.</span>
                                    </div>

                                    <div class="mt-4 space-y-3">
                                        @forelse ($application->messages->take(5) as $message)
                                            <div class="rounded-2xl border {{ $message->is_admin ? 'border-amber-100 bg-amber-50' : 'border-slate-200 bg-white' }} p-4 shadow-sm">
                                                <div class="flex items-center justify-between gap-3">
                                                    <p class="text-xs font-bold uppercase tracking-wider {{ $message->is_admin ? 'text-amber-600' : 'text-slate-900' }}">
                                                        {{ $message->is_admin ? 'Admin Alliago' : 'Anda' }}
                                                    </p>
                                                    <p class="text-xs text-slate-400">{{ $message->created_at->diffForHumans() }}</p>
                                                </div>
                                                <p class="mt-2 text-sm text-slate-700">{{ $message->message }}</p>
                                            </div>
                                        @empty
                                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-4 text-center text-sm text-slate-500">
                                                Belum ada pesan. Komunikasi dengan tim kami akan muncul di sini.
                                            </div>
                                        @endforelse
                                    </div>

                                    <form method="POST" action="{{ route('client.messages.store', $application) }}" class="mt-4 space-y-3">
                                        @csrf
                                        <textarea name="message" rows="3" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none" placeholder="Tulis pesan ke admin..."></textarea>
                                        <div class="flex justify-end">
                                            <button type="submit" class="rounded-full bg-[#0361fc] px-4 py-2 text-sm font-bold text-white hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20 transition">
                                                Kirim Pesan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <p class="mt-4 text-lg font-bold text-slate-900">Belum ada aplikasi visa</p>
                                <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">Mulai perjalanan Anda dengan membuat aplikasi visa baru dari katalog layanan kami. Semua proses akan dipantau di halaman ini.</p>
                                <div class="mt-6">
                                    <a href="{{ route('visa.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#0361fc] px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20">Jelajahi Layanan Visa</a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </section>

                <div class="grid gap-8">
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-slate-900">Informasi Pribadi</h2>
                        <p class="mt-1 text-sm text-slate-500">Pastikan informasi Anda selalu akurat agar proses aplikasi berjalan lancar.</p>

                        <div class="mt-6 space-y-4">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Nama Akun</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $client->name }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Alamat Email</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $client->email }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Nomor Telepon</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $client->phone ?: 'Belum diisi' }}</p>
                            </div>
                            <div class="mt-2 flex justify-end">
                                <a href="{{ route('client.profile.edit') }}" class="text-sm font-bold text-[#0361fc] hover:text-blue-700">Ubah Profil &rarr;</a>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-slate-900">Pemberitahuan</h2>
                        <p class="mt-1 text-sm text-slate-500">Notifikasi penting terkait aplikasi Anda.</p>

                        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-600">
                            @if ($orderStats['unreadAdminMessages'] > 0)
                                <span class="font-bold text-rose-600">Anda memiliki {{ $orderStats['unreadAdminMessages'] }} pesan admin yang belum dibaca.</span>
                            @else
                                Belum ada pemberitahuan penting. Semua berjalan sesuai rencana.
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <section class="mt-12 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Rekomendasi Layanan Visa</h2>
                        <p class="mt-1 text-sm text-slate-500">Mulai petualangan baru Anda. Ajukan visa untuk berbagai destinasi di seluruh dunia.</p>
                    </div>
                    <div class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-600">
                        {{ $availableCountries->count() }} negara tersedia
                    </div>
                </div>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($recommendedVisaProducts as $visaProduct)
                        <a href="{{ route('visa.show', $visaProduct->slug) }}" class="group flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#0361fc]/30 hover:shadow-xl hover:shadow-[#0361fc]/5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $visaProduct->country->name }}</p>
                                    <h3 class="mt-1 text-xl font-bold text-slate-900 group-hover:text-[#0361fc] transition-colors">{{ $visaProduct->name }}</h3>
                                </div>
                                <span class="inline-flex shrink-0 items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#0361fc] ring-1 ring-inset ring-[#0361fc]/10">{{ $visaProduct->type }}</span>
                            </div>
                            <p class="mt-4 text-sm text-slate-600 line-clamp-2">{{ $visaProduct->short_description ?: 'Layanan pengurusan visa profesional untuk memudahkan perjalanan Anda.' }}</p>
                            <div class="mt-6 flex items-end justify-between text-sm">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Mulai dari</span>
                                <span class="text-lg font-bold text-slate-900">IDR {{ number_format((float) $visaProduct->discount_price ?: (float) $visaProduct->base_price, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center sm:col-span-2 lg:col-span-3">
                            <p class="text-sm text-slate-500">Belum ada layanan visa yang tersedia saat ini. Silakan kembali lagi nanti untuk melihat penawaran terbaru kami.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-layouts.app>
