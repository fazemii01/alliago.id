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
            <div class="no-print mb-6 flex justify-between items-center">
                <a href="{{ route('client.applications.show', $application) }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Detail Aplikasi
                </a>
                <button onclick="window.print()" class="inline-flex items-center rounded-full bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Print / PDF
                </button>
            </div>

            <div class="invoice-card bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8 sm:p-12">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-8">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-2xl bg-[#0361fc] flex items-center justify-center text-white font-bold text-2xl shadow-md">
                                <img src="{{ asset('images/alliago-logo.jpeg') }}" alt="Alliago">
                            </div>
                            <div>
                                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Alliago.id</h1>
                                <p class="text-sm text-slate-500 font-medium">Your trusted visa partner</p>
                            </div>
                        </div>
                        <div class="text-left sm:text-right">
                            <h2 class="text-3xl font-light tracking-widest text-slate-400 uppercase">Tagihan</h2>
                            <p class="mt-2 text-sm font-bold text-slate-700">{{ $application->reference_number }}</p>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Ditagihkan Kepada</p>
                            <h3 class="text-lg font-bold text-slate-900">{{ $application->user->name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $application->user->email }}</p>
                            
                            <div class="mt-6">
                                <p class="text-xs font-medium text-slate-500">Traveler:</p>
                                <ul class="mt-1 list-disc list-inside text-sm font-medium text-slate-800">
                                    <li>{{ $application->traveler_name }}</li>
                                </ul>
                            </div>
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
                                @php
                                    $paymentStatus = $application->metadata['payment_status'] ?? 'unpaid';
                                @endphp
                                @if($paymentStatus === 'paid')
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 uppercase tracking-wider">Paid</span>
                                @elseif($paymentStatus === 'pending_verification')
                                    <span class="inline-flex items-center rounded-md bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20 uppercase tracking-wider">Pending Verification</span>
                                @elseif($paymentStatus === 'declined')
                                    <span class="inline-flex items-center rounded-md bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20 uppercase tracking-wider">Declined / Failed</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20 uppercase tracking-wider">Unpaid</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Items -->
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
                                        @if ($application->visaProduct)
                                            Layanan Pembuatan Visa {{ $application->visaProduct->country->name }}<br>
                                            <span class="text-slate-500 font-normal">{{ $application->visaProduct->name }}</span>
                                        @else
                                            Tiket Pesawat: {{ $application->metadata['flight_details']['airline_name'] ?? 'Penerbangan' }} ({{ $application->metadata['flight_details']['flight_numbers'] ?? '' }})<br>
                                            <span class="text-slate-500 font-normal">
                                                Rute: {{ $application->metadata['flight_details']['origin'] ?? '' }} &rarr; {{ $application->metadata['flight_details']['destination'] ?? '' }}
                                                | Kelas: {{ $application->metadata['flight_details']['cabin_class'] ?? '' }}
                                                @if (!empty($application->metadata['flight_details']['depart_date']))
                                                    | Pergi: {{ $application->metadata['flight_details']['depart_date'] }} {{ $application->metadata['flight_details']['depart_time'] ?? '' }}
                                                @endif
                                                @if (!empty($application->metadata['flight_details']['return_date']))
                                                    | Pulang: {{ $application->metadata['flight_details']['return_date'] }} {{ $application->metadata['flight_details']['return_time'] ?? '' }}
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                     <td class="py-6 text-sm font-bold text-slate-900 text-right whitespace-nowrap">
                                        @if($invoiceAmount)
                                            @if(!$application->visaProduct && isset($application->metadata['flight_details']['extra_baggage_price']) && $application->metadata['flight_details']['extra_baggage_price'] > 0)
                                                Rp {{ number_format($invoiceAmount - $application->metadata['flight_details']['extra_baggage_price'], 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($invoiceAmount, 0, ',', '.') }}
                                            @endif
                                        @else
                                            <span class="text-slate-400 text-xs">Menghubungi sistem...</span>
                                        @endif
                                     </td>
                                </tr>

                                @if(!$application->visaProduct && isset($application->metadata['flight_details']['extra_baggage_weight']) && $application->metadata['flight_details']['extra_baggage_weight'] > 0)
                                <tr class="border-b border-slate-100">
                                    <td class="py-6 text-sm font-medium text-slate-800">
                                        Bagasi Tambahan (Extra Baggage) +{{ $application->metadata['flight_details']['extra_baggage_weight'] }} kg<br>
                                        <span class="text-slate-500 font-normal">Layanan penambahan berat check-in untuk bagasi pesawat</span>
                                    </td>
                                    <td class="py-6 text-sm font-bold text-slate-900 text-right whitespace-nowrap">
                                        Rp {{ number_format($application->metadata['flight_details']['extra_baggage_price'], 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="mt-8 flex justify-end">
                        <div class="w-full max-w-sm space-y-4 bg-slate-50 p-6 rounded-2xl">
                            @if(!$application->visaProduct && isset($application->metadata['flight_details']['extra_baggage_weight']) && $application->metadata['flight_details']['extra_baggage_weight'] > 0)
                                <div class="flex justify-between"><span class="text-sm text-slate-600">Harga Tiket</span><span class="text-sm font-bold text-slate-900">Rp {{ number_format($invoiceAmount - $application->metadata['flight_details']['extra_baggage_price'], 0, ',', '.') }}</span></div>
                                <div class="flex justify-between"><span class="text-sm text-slate-600">Bagasi Tambahan (+{{ $application->metadata['flight_details']['extra_baggage_weight'] }} kg)</span><span class="text-sm font-bold text-slate-900">Rp {{ number_format($application->metadata['flight_details']['extra_baggage_price'], 0, ',', '.') }}</span></div>
                            @endif
                            <div class="flex justify-between"><span class="text-sm text-slate-600">Subtotal</span><span class="text-sm font-bold text-slate-900">{{ $invoiceAmount ? 'Rp '.number_format($invoiceAmount, 0, ',', '.') : '-' }}</span></div>
                            <div class="flex justify-between border-b border-slate-200 pb-4"><span class="text-sm text-slate-600">Pajak (0%)</span><span class="text-sm font-bold text-slate-900">Rp 0</span></div>
                            <div class="flex justify-between pt-2"><span class="text-base font-bold text-slate-900">Total Keseluruhan</span><span class="text-lg font-black text-[#0361fc]">{{ $invoiceAmount ? 'Rp '.number_format($invoiceAmount, 0, ',', '.') : 'Menghubungi sistem...' }}</span></div>
                        </div>
                    </div>

                    @if(in_array($application->status, ['pending_payment', 'payment_failed']))
                        <div class="mt-10 flex flex-col items-center gap-3 print:hidden">
                            @if($application->status === 'payment_failed')
                                <p class="text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-2xl px-5 py-3 text-center">
                                    ⚠️ Pembayaran sebelumnya tidak berhasil atau jumlah kurang. Silakan coba bayar kembali.
                                </p>
                            @endif
                            <a href="{{ route('client.applications.checkout', $application) }}" class="rounded-full bg-[#0361fc] px-8 py-3 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition">
                                Lanjutkan Pembayaran
                            </a>
                        </div>
                    @endif
                </div>
                
                <!-- Footer -->
                <div class="invoice-footer bg-slate-900 px-8 py-6 text-center">
                    <p class="text-xs text-slate-400">Terima kasih telah menggunakan layanan Alliago.id. Untuk pertanyaan terkait tagihan ini, silakan hubungi support@alliago.id.</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
