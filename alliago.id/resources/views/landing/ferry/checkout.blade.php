<x-layouts.app title="Pembayaran Ferry | Alliago.id">

<x-home.header />

<div class="min-h-screen bg-slate-50 text-slate-900 pt-24 pb-16">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('ferry.invoice', $application) }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 mb-4">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Invoice
            </a>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#0361fc]">Pembayaran</p>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Selesaikan Pembayaran</h1>
            <p class="mt-2 text-base text-slate-500">Order: <span class="font-bold text-slate-700">{{ $application->reference_number }}</span></p>
        </div>

        @if(session('error'))
            <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-100">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('status'))
            <div class="mb-6 rounded-xl bg-green-50 p-4 border border-green-100">
                <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
            </div>
        @endif

        @php
            $ferry = $application->metadata['ferry_details'] ?? [];
            $invoiceAmount = $application->metadata['invoice_amount'] ?? $application->metadata['price_breakdown']['total'] ?? 0;
        @endphp

        {{-- Order Summary --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm mb-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tiket Kapal Ferry</p>
                    <p class="font-extrabold text-slate-900 text-lg">{{ $ferry['origin'] ?? '-' }} &rarr; {{ $ferry['destination'] ?? '-' }}</p>
                    @if(!empty($ferry['travel_date']))
                        <p class="text-sm text-slate-500 mt-1">{{ \Carbon\Carbon::parse($ferry['travel_date'])->translatedFormat('d F Y') }} &middot; {{ $ferry['passenger_count'] ?? 1 }} penumpang</p>
                    @endif
                    <p class="text-xs text-slate-400 mt-1">Penumpang: <span class="font-medium text-slate-600">{{ $application->traveler_name }}</span></p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-2xl font-extrabold text-[#0361fc]">RM {{ number_format($invoiceAmount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Payment Method Selection --}}
        @if(!$selectedMethod)
        <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-5">Pilih Metode Pembayaran</h2>

            <form action="{{ route('ferry.checkout.store', $application) }}" method="POST">
                @csrf
                <div class="space-y-3 mb-6">
                    @forelse($paymentMethods as $method)
                    <label class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 cursor-pointer hover:border-[#0361fc]/40 hover:bg-slate-50 transition has-[:checked]:border-[#0361fc] has-[:checked]:bg-[#EDF4FF]">
                        <input type="radio" name="payment_method_id" value="{{ $method->id }}" class="accent-[#0361fc]" required>
                        @if($method->icon)
                            <img src="{{ \Storage::url($method->icon) }}" alt="{{ $method->name }}" class="h-8 object-contain flex-shrink-0">
                        @else
                            <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-bold text-slate-900">{{ $method->name }}</p>
                            @if($method->provider === 'manual' && $method->account_number)
                                <p class="text-sm text-slate-500">{{ $method->account_number }} a.n {{ $method->account_name }}</p>
                            @elseif($method->provider === 'xendit')
                                <p class="text-sm text-slate-500">Pembayaran online via Xendit</p>
                            @endif
                        </div>
                    </label>
                    @empty
                        <p class="text-sm text-slate-500 text-center py-4">Tidak ada metode pembayaran tersedia. Silakan hubungi admin.</p>
                    @endforelse
                </div>

                @if($paymentMethods->isNotEmpty())
                <button type="submit" class="w-full rounded-full bg-[#0361fc] px-6 py-3.5 text-sm font-bold text-white hover:bg-blue-700 transition">
                    Lanjutkan Pembayaran
                </button>
                @endif
            </form>
        </div>

        {{-- Manual Payment Upload --}}
        @elseif($selectedMethod->provider === 'manual')
        <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-slate-100">
                @if($selectedMethod->icon)
                    <img src="{{ \Storage::url($selectedMethod->icon) }}" alt="{{ $selectedMethod->name }}" class="h-8 object-contain">
                @endif
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $selectedMethod->name }}</h2>
                    @if($selectedMethod->account_number)
                        <p class="text-sm text-slate-500">{{ $selectedMethod->account_number }} a.n {{ $selectedMethod->account_name }}</p>
                    @endif
                </div>
            </div>

            @if($selectedMethod->description)
                <div class="mb-6 text-sm text-slate-600 prose prose-slate">
                    {!! $selectedMethod->description !!}
                </div>
            @endif

            <form action="{{ route('ferry.checkout.store', $application) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-900 mb-2">Upload Bukti Pembayaran</label>
                    <input type="file" name="payment_proof" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-[#0361fc] hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-2" required accept="image/jpeg,image/png,image/jpg,application/pdf">
                    <p class="text-xs text-slate-500 mt-2">Format: JPG, PNG, PDF. Maksimal 10MB.</p>
                    @error('payment_proof')
                        <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full flex items-center justify-center rounded-full bg-[#0361fc] px-6 py-3.5 text-sm font-bold text-white hover:bg-blue-700 transition">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

</x-layouts.app>
