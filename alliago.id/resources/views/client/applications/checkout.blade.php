<x-layouts.app title="Checkout | Client Area">
    <x-home.header />

    <div class="min-h-screen bg-slate-50 text-slate-900 pt-24 pb-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#0361fc]">Pembayaran</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl text-slate-900">Selesaikan Pembayaran Anda</h1>
                <p class="mt-3 text-lg text-slate-600">Order ID: <span class="font-bold text-slate-900">{{ $application->reference_number }}</span></p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-100">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Total Tagihan</h2>
                        <p class="text-sm text-slate-500">Silakan lakukan pembayaran sesuai dengan nominal berikut</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-extrabold text-[#0361fc]">Rp {{ number_format($application->metadata['price_breakdown']['total'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="font-bold text-slate-900 mb-2">Metode Pembayaran Terpilih</h3>
                    <div class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-100 rounded-xl">
                        @if($paymentMethod->icon)
                            <img src="{{ Storage::url($paymentMethod->icon) }}" alt="{{ $paymentMethod->name }}" class="h-8 object-contain">
                        @else
                            <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-slate-900">{{ $paymentMethod->name }}</p>
                            @if($paymentMethod->provider === 'manual')
                                <p class="text-sm text-slate-600">{{ $paymentMethod->account_number }} a.n {{ $paymentMethod->account_name }}</p>
                            @endif
                        </div>
                    </div>
                    @if($paymentMethod->description)
                        <div class="mt-4 text-sm text-slate-600 prose prose-slate">
                            {!! $paymentMethod->description !!}
                        </div>
                    @endif
                </div>

                <form action="{{ route('client.applications.checkout.store', $application) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @if($paymentMethod->provider === 'manual')
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-900 mb-2">Upload Bukti Pembayaran</label>
                            <input type="file" name="payment_proof" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-[#0361fc] hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-2" required accept="image/jpeg,image/png,image/jpg,application/pdf">
                            <p class="text-xs text-slate-500 mt-2">Format yang didukung: JPG, PNG, PDF. Maksimal 5MB.</p>
                            @error('payment_proof')
                                <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" class="w-full flex items-center justify-center rounded-full bg-[#0361fc] px-6 py-3.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition">
                            Konfirmasi Pembayaran
                        </button>
                    @elseif($paymentMethod->provider === 'xendit')
                        <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl mb-6">
                            <p class="text-sm text-blue-800">Anda akan diarahkan ke halaman pembayaran aman Xendit untuk menyelesaikan transaksi ini. Tagihan akan dikonfirmasi secara otomatis.</p>
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center rounded-full bg-[#0361fc] px-6 py-3.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition">
                            Bayar Sekarang dengan Xendit
                        </button>
                    @endif
                </form>

            </div>
        </div>
    </div>
</x-layouts.app>
