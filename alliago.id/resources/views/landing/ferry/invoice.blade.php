<x-layouts.app title="Invoice {{ $application->reference_number }}">

@push('meta')
<style>
@media print {
    body { background: white !important; padding: 0 !important; margin: 0 !important; }
    .no-print, nav, footer { display: none !important; }
    .invoice-card { box-shadow: none !important; border: none !important; border-radius: 0 !important; }
    .invoice-wrap { background: white !important; padding: 0 !important; min-height: auto !important; }
    .invoice-footer { background: #1e293b !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    @page { margin: 1cm; size: A4; }
}
</style>
@endpush

    <div class="invoice-wrap min-h-screen bg-slate-50 text-slate-900 py-12 font-sans">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="no-print mb-6 rounded-xl bg-emerald-50 p-4 border border-emerald-100">
                    <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="no-print mb-6 rounded-xl bg-red-50 p-4 border border-red-100">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="no-print mb-6 flex justify-between items-center">
                <a href="{{ route('ferry.index') }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Tiket Ferry
                </a>
                <button onclick="window.print()" class="inline-flex items-center rounded-full bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Print / PDF
                </button>
            </div>

            <div class="invoice-card bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8 sm:p-12">
                    {{-- Header --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-8">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-2xl bg-[#0361fc] flex items-center justify-center text-white font-bold text-2xl shadow-md overflow-hidden">
                                <img src="{{ asset('images/alliago-logo.jpeg') }}" alt="Alliago">
                            </div>
                            <div>
                                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Alliago.id</h1>
                                <p class="text-sm text-slate-500 font-medium">Your trusted travel partner</p>
                            </div>
                        </div>
                        <div class="text-left sm:text-right">
                            <h2 class="text-3xl font-light tracking-widest text-slate-400 uppercase">Tagihan</h2>
                            <p class="mt-2 text-sm font-bold text-slate-700">{{ $application->reference_number }}</p>
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Ditagihkan Kepada</p>
                            <h3 class="text-lg font-bold text-slate-900">{{ $application->traveler_name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $application->traveler_email }}</p>
                            @if($application->traveler_phone)
                                <p class="text-sm text-slate-500">{{ $application->traveler_phone }}</p>
                            @endif
                        </div>

                        <div class="md:text-right space-y-4">
                            <div class="flex justify-between md:justify-end gap-8">
                                <span class="text-sm text-slate-500">Tanggal Diterbitkan:</span>
                                <span class="text-sm font-bold text-slate-900">{{ $application->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between md:justify-end gap-8">
                                <span class="text-sm text-slate-500">Jatuh Tempo:</span>
                                <span class="text-sm font-bold text-slate-900">{{ $application->created_at->addDays(7)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between md:justify-end items-center gap-8 pt-2">
                                <span class="text-sm text-slate-500">Status:</span>
                                @php $paymentStatus = $application->metadata['payment_status'] ?? 'unpaid'; @endphp
                                @if($paymentStatus === 'paid')
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 uppercase tracking-wider">Paid</span>
                                @elseif($paymentStatus === 'pending_verification')
                                    <span class="inline-flex items-center rounded-md bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20 uppercase tracking-wider">Menunggu Verifikasi</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20 uppercase tracking-wider">Belum Dibayar</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Items --}}
                    @php $ferry = $application->metadata['ferry_details'] ?? []; @endphp
                    <div class="mt-12">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="pb-3 text-xs font-bold uppercase tracking-widest text-slate-400">Deskripsi</th>
                                    <th class="pb-3 text-xs font-bold uppercase tracking-widest text-slate-400 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-slate-100">
                                    <td class="py-6 text-sm font-medium text-slate-800">
                                        Tiket Kapal Ferry<br>
                                        <span class="text-slate-500 font-normal">
                                            Rute: {{ $ferry['origin'] ?? '-' }} &rarr; {{ $ferry['destination'] ?? '-' }}
                                            @if(!empty($ferry['travel_date']))
                                                &nbsp;|&nbsp; Tanggal: {{ \Carbon\Carbon::parse($ferry['travel_date'])->translatedFormat('d F Y') }}
                                            @endif
                                            &nbsp;|&nbsp; {{ $ferry['passenger_count'] ?? 1 }} Penumpang
                                        </span>
                                    </td>
                                    <td class="py-6 text-sm font-bold text-slate-900 text-right whitespace-nowrap">
                                        @if($invoiceAmount)
                                            RM {{ number_format($invoiceAmount, 0, ',', '.') }}
                                        @else
                                            <span class="text-slate-400 text-xs">&mdash;</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary --}}
                    <div class="mt-8 flex justify-end">
                        <div class="w-full max-w-sm space-y-4 bg-slate-50 p-6 rounded-2xl">
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-600">Subtotal</span>
                                <span class="text-sm font-bold text-slate-900">{{ $invoiceAmount ? 'RM '.number_format($invoiceAmount, 0, ',', '.') : '&mdash;' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-200 pb-4">
                                <span class="text-sm text-slate-600">Pajak (0%)</span>
                                <span class="text-sm font-bold text-slate-900">RM 0</span>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span class="text-base font-bold text-slate-900">Total Keseluruhan</span>
                                <span class="text-lg font-black text-[#0361fc]">{{ $invoiceAmount ? 'RM '.number_format($invoiceAmount, 0, ',', '.') : '&mdash;' }}</span>
                            </div>
                        </div>
                    </div>

                    @if(in_array($application->status, ['pending_payment', 'payment_failed']))
                        <div class="mt-10 flex flex-col items-center gap-3 print:hidden">
                            @if($application->status === 'payment_failed')
                                <p class="text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-2xl px-5 py-3 text-center">
                                    ⚠️ Pembayaran sebelumnya tidak berhasil. Silakan coba bayar kembali.
                                </p>
                            @endif
                            <a href="{{ route('ferry.checkout', $application) }}" class="rounded-full bg-[#0361fc] px-8 py-3 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition">
                                Lanjutkan Pembayaran
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="invoice-footer bg-slate-900 px-8 py-6 text-center">
                    <p class="text-xs text-slate-400">Terima kasih telah menggunakan layanan Alliago.id. Untuk pertanyaan, silakan hubungi support@alliago.id.</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
