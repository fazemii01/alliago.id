<x-layouts.app title="{{ $application->reference_number }} | Client Area">
    <x-home.header />

    <div class="min-h-screen bg-slate-50 text-slate-900 pt-24 pb-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#0361fc]">Order detail</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl text-slate-900">{{ $application->reference_number }}</h1>
                    <p class="mt-3 max-w-3xl text-lg text-slate-600">
                        @if ($application->visaProduct)
                            <span class="font-bold text-slate-900">{{ $application->visaProduct->name }}</span> for {{ $application->visaProduct->country->name }}.
                        @else
                            <span class="font-bold text-slate-900">Pemesanan Tiket Pesawat ({{ $application->metadata['flight_details']['airline_name'] ?? 'Penerbangan' }})</span>
                            untuk rute {{ $application->metadata['flight_details']['origin'] ?? '' }} &rarr; {{ $application->metadata['flight_details']['destination'] ?? '' }}.
                        @endif
                        Use this page to track status, upload files, and keep the admin conversation focused on this order only.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:border-slate-300">&larr; Back to dashboard</a>
                    <span class="inline-flex items-center justify-center rounded-full bg-slate-100 px-5 py-2.5 text-sm font-bold uppercase tracking-wider text-slate-700 ring-1 ring-inset ring-slate-500/10 shadow-sm">{{ str_replace('_', ' ', $application->status) }}</span>
                </div>
            </div>

            <div class="mt-10 grid gap-8 xl:grid-cols-[1.1fr_0.9fr] grid-cols-1">
                @if ($application->visa_product_id)
                    <section class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Document checklist</h2>
                            <p class="mt-1 text-sm text-slate-500">Upload or replace the requested files here. If admin asks for a revision, their feedback will appear directly under the related document.</p>
                            
                            @if (($application->metadata['delivery_method'] ?? 'soft_file') === 'hard_file')
                                <div class="mt-4 flex items-start gap-3 bg-blue-50 p-4 rounded-xl border border-blue-100">
                                    <svg class="w-5 h-5 text-[#0361fc] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <div>
                                        <h4 class="text-sm font-bold text-blue-900">Proses Pra-Verifikasi Dokumen</h4>
                                        <p class="text-sm text-blue-800 mt-1">Meskipun Anda memilih penyerahan dokumen fisik (Hard File), Anda tetap <strong>diwajibkan mengunggah versi digital (Soft File)</strong> dari seluruh persyaratan dokumen di bawah ini terlebih dahulu.</p>
                                        <p class="text-sm text-blue-800 mt-1">Tim Alliago akan memverifikasi kesesuaian dokumen digital Anda. Dokumen fisik hanya akan diambil/diserahkan setelah semua dokumen digital dinyatakan <strong>valid</strong> guna meminimalisir kesalahan logistik.</p>
                                    </div>
                                </div>
                             @endif
                        </div>

                        <div class="space-y-4 mt-6">
                            @foreach ($application->documents as $document)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                        <div>
                                            <p class="text-lg font-bold text-slate-900">{{ $document->label }}</p>
                                            <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-[#0361fc]">{{ str_replace('_', ' ', $document->status) }}</p>
                                            @if ($document->reviewed_at)
                                                <p class="mt-2 text-xs font-medium text-slate-500">Reviewed {{ $document->reviewed_at->diffForHumans() }}</p>
                                            @endif
                                            @if ($document->admin_feedback)
                                                <p class="mt-3 text-sm font-medium text-rose-600 bg-rose-50 p-3 rounded-xl border border-rose-100">Admin note: {{ $document->admin_feedback }}</p>
                                            @endif
                                        </div>

                                        <form method="POST" action="{{ route('client.documents.store', [$application, $document]) }}" enctype="multipart/form-data" class="document-upload-form flex flex-col gap-2 md:items-end w-full md:w-auto mt-4 md:mt-0">
                                            @csrf
                                            <input type="file" name="document" required class="block w-full max-w-[250px] text-xs text-slate-500 file:mr-2 file:rounded-full file:border-0 file:bg-slate-200 file:px-3 file:py-1.5 file:font-bold file:text-slate-700 hover:file:bg-slate-300">
                                            <div class="flex items-center gap-2">
                                                <span class="upload-status hidden text-xs font-bold text-emerald-600"></span>
                                                <button type="submit" class="upload-btn mt-1 w-full md:w-auto inline-flex justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:border-slate-300">
                                                    {{ $document->file_path ? 'Replace file' : 'Upload file' }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @else
                    <!-- Flight Details & Payment Status Card for Flights -->
                    <section class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Payment & Flight details</h2>
                                <p class="mt-1 text-sm text-slate-500">View transaction status and booked itinerary.</p>
                            </div>
                            @php
                                $paymentStatus = $application->metadata['payment_status'] ?? 'unpaid';
                                $amount = $application->metadata['invoice_amount'] ?? $application->metadata['price_breakdown']['total'] ?? 0;
                            @endphp
                            @if ($paymentStatus === 'paid')
                                <span class="inline-flex shrink-0 items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-700 ring-1 ring-inset ring-emerald-600/10">
                                    Lunas / Paid
                                </span>
                            @elseif ($paymentStatus === 'pending_verification')
                                <span class="inline-flex shrink-0 items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-amber-700 ring-1 ring-inset ring-amber-600/10">
                                    Menunggu Verifikasi
                                </span>
                            @elseif ($paymentStatus === 'declined')
                                <span class="inline-flex shrink-0 items-center rounded-full bg-rose-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-rose-700 ring-1 ring-inset ring-rose-600/10">
                                    Ditolak / Gagal
                                </span>
                            @else
                                <span class="inline-flex shrink-0 items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-700 ring-1 ring-inset ring-slate-500/10">
                                    Belum Bayar
                                </span>
                            @endif
                        </div>

                        <div class="mt-6 space-y-6">
                            <!-- Payment Status Summary -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-slate-50 p-5 rounded-2xl border border-slate-100 gap-4">
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tagihan</p>
                                    <p class="text-2xl font-extrabold text-slate-900 mt-1">Rp {{ number_format($amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex gap-3">
                                    @if ($paymentStatus !== 'paid' && $paymentStatus !== 'pending_verification')
                                        <a href="{{ route('client.applications.checkout', $application) }}" class="inline-flex items-center justify-center rounded-full bg-[#0361fc] px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition">
                                            Bayar Sekarang &rarr;
                                        </a>
                                    @elseif ($paymentStatus === 'pending_verification')
                                        <span class="text-xs font-bold text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-full flex items-center justify-center whitespace-nowrap">
                                            Sedang Diverifikasi
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full flex items-center justify-center whitespace-nowrap">
                                            Terverifikasi
                                        </span>
                                    @endif
                                    <a href="{{ route('client.applications.invoice', $application) }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition">
                                        Lihat Invoice
                                    </a>
                                </div>
                            </div>

                            <!-- Flight Details Grid -->
                            @if (isset($application->metadata['flight_details']))
                                <div class="border border-slate-200 rounded-3xl overflow-hidden">
                                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">Detail Penerbangan</h3>
                                    </div>
                                    <div class="p-6 grid gap-6 sm:grid-cols-2 text-sm">
                                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                            <p class="text-xs font-bold text-slate-400 uppercase">Maskapai</p>
                                            <p class="mt-1 font-bold text-slate-900 text-base">{{ $application->metadata['flight_details']['airline_name'] ?? '-' }}</p>
                                        </div>
                                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                            <p class="text-xs font-bold text-slate-400 uppercase">Nomor Penerbangan</p>
                                            <p class="mt-1 font-mono font-bold text-slate-900 text-base">{{ $application->metadata['flight_details']['flight_numbers'] ?? '-' }}</p>
                                        </div>
                                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                            <p class="text-xs font-bold text-slate-400 uppercase">Rute Perjalanan</p>
                                            <p class="mt-1 font-bold text-slate-900 text-base">{{ strtoupper($application->metadata['flight_details']['origin'] ?? '') }} &rarr; {{ strtoupper($application->metadata['flight_details']['destination'] ?? '') }}</p>
                                        </div>
                                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                            <p class="text-xs font-bold text-slate-400 uppercase">Keberangkatan</p>
                                            <p class="mt-1 font-bold text-slate-900 text-base">{{ $application->metadata['flight_details']['depart_date'] ?? '-' }} {{ $application->metadata['flight_details']['depart_time'] ?? '' }}</p>
                                        </div>
                                        @if (isset($application->metadata['flight_details']['return_date']))
                                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 sm:col-span-2">
                                                <p class="text-xs font-bold text-slate-400 uppercase">Kepulangan (Round Trip)</p>
                                                <p class="mt-1 font-bold text-slate-900 text-base">{{ $application->metadata['flight_details']['return_date'] }} {{ $application->metadata['flight_details']['return_time'] ?? '' }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </section>
                @endif

                <div class="space-y-8">
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-slate-900">Traveler summary</h2>
                        <div class="mt-6 space-y-4">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Traveler name</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $application->traveler_name }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Traveler email</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $application->traveler_email }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Traveler phone</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $application->traveler_phone ?: 'Not provided yet' }}</p>
                            </div>
                        </div>
                    </section>

                    @php
                        $metadata = $application->metadata ?? [];
                        $deliveryMethod = $metadata['delivery_method'] ?? 'soft_file';
                        $pickupMethod = $metadata['hard_file_pickup'] ?? null;
                        $deliveryMethodLogistics = $metadata['hard_file_delivery'] ?? null;
                        $pickupAddress = $metadata['pickup_address'] ?? null;
                        $deliveryAddress = $metadata['delivery_address'] ?? null;
                    @endphp

                    @if ($application->visa_product_id)
                        <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                            <h2 class="text-2xl font-bold text-slate-900">Logistics status</h2>
                            <div class="mt-6 space-y-4">
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Metode Pengiriman Dokumen</p>
                                    <p class="mt-1 text-base font-bold text-slate-900">
                                        {{ $deliveryMethod === 'hard_file' ? 'Kirim Dokumen Fisik (Hard File)' : 'Kirim Digital (Soft File)' }}
                                    </p>
                                </div>
                                
                                @if($deliveryMethod === 'hard_file')
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Penyerahan Dokumen (Pickup)</p>
                                        <p class="mt-1 text-base font-bold text-slate-900">
                                            {{ $pickupMethod === 'kurir' ? 'Penjemputan oleh Kurir Alliago' : 'Kirim Mandiri ke Kantor Alliago' }}
                                        </p>
                                        @if($pickupMethod === 'kurir' && $pickupAddress)
                                            <p class="mt-2 text-sm text-slate-600 bg-white p-3 rounded-xl border border-slate-200">{{ $pickupAddress }}</p>
                                        @endif
                                    </div>
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Pengembalian Dokumen (Delivery)</p>
                                        <p class="mt-1 text-base font-bold text-slate-900">
                                            {{ $deliveryMethodLogistics === 'kurir' ? 'Pengiriman oleh Kurir Alliago' : 'Ambil Sendiri di Kantor Alliago' }}
                                        </p>
                                        @if($deliveryMethodLogistics === 'kurir' && $deliveryAddress)
                                            <p class="mt-2 text-sm text-slate-600 bg-white p-3 rounded-xl border border-slate-200">{{ $deliveryAddress }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-slate-900">Status timeline</h2>
                        <div class="mt-6 relative space-y-4 before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                            @foreach ($application->statusLogs as $statusLog)
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                    <div class="flex items-center justify-center w-5 h-5 rounded-full border-2 border-white bg-slate-200 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2"></div>
                                    <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-sm">
                                        <p class="text-sm font-bold text-slate-900">{{ str_replace('_', ' ', $statusLog->to_status) }}</p>
                                        @if ($statusLog->message)
                                            <p class="mt-1 text-sm text-slate-600">{{ $statusLog->message }}</p>
                                        @endif
                                        <p class="mt-2 text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ $statusLog->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                            <h2 class="text-2xl font-bold text-slate-900">Order communication</h2>
                            <span class="text-xs text-slate-500">Keep replies specific to this order.</span>
                        </div>

                        <div class="mt-6 space-y-4">
                            @forelse ($application->messages as $message)
                                <div class="rounded-2xl border {{ $message->is_admin ? 'border-amber-100 bg-amber-50' : 'border-slate-200 bg-white shadow-sm' }} p-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-xs font-bold uppercase tracking-wider {{ $message->is_admin ? 'text-amber-600' : 'text-slate-900' }}">
                                            {{ $message->is_admin ? 'Admin' : 'You' }}
                                        </p>
                                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ $message->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-700">{{ $message->message }}</p>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">
                                    No messages yet for this order.
                                </div>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('client.messages.store', $application) }}" class="mt-6 space-y-3">
                            @csrf
                            <textarea name="message" rows="3" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition" placeholder="Reply to admin about this order..."></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#0361fc] px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20 transition">
                                    Send message
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.document-upload-form').forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    const btn = form.querySelector('.upload-btn');
                    const statusSpan = form.querySelector('.upload-status');
                    const originalText = btn.innerText;
                    
                    btn.innerText = 'Uploading...';
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    statusSpan.classList.add('hidden');
                    
                    const formData = new FormData(form);
                    
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        if (response.ok) {
                            btn.innerText = 'Replace file';
                            statusSpan.innerText = 'Uploaded ✅';
                            statusSpan.classList.remove('hidden', 'text-rose-600');
                            statusSpan.classList.add('text-emerald-600');
                        } else {
                            btn.innerText = originalText;
                            statusSpan.innerText = 'Failed ❌';
                            statusSpan.classList.remove('hidden', 'text-emerald-600');
                            statusSpan.classList.add('text-rose-600');
                        }
                    } catch (error) {
                        btn.innerText = originalText;
                        statusSpan.innerText = 'Error ❌';
                        statusSpan.classList.remove('hidden', 'text-emerald-600');
                        statusSpan.classList.add('text-rose-600');
                    } finally {
                        btn.disabled = false;
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                });
            });
        });
    </script>
</x-layouts.app>
