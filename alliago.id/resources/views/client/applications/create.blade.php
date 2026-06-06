<x-layouts.app :title="'Create Order | ' . $visaProduct->name">
    <x-home.header />

    <div class="min-h-screen bg-slate-50 text-slate-900 pt-24 pb-16" x-data="visaOrderForm()">
        <div class="page-wrapper">
            <a href="{{ route('visa.show', $visaProduct->slug) }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#0361fc] hover:text-blue-700 mb-6 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke detail visa
            </a>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between border-b border-slate-200 pb-4 overflow-x-auto no-scrollbar gap-8">
                    <template x-for="(s, index) in ['Lengkapi detail', 'Add-ons', 'Pengiriman dokumen', 'Review & bayar']">
                        <div class="flex items-center gap-3 shrink-0 cursor-pointer" @click="if(step > index + 1) step = index + 1">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full border-2 text-sm font-bold transition" 
                                 :class="step > index + 1 ? 'border-emerald-500 bg-emerald-500 text-white' : (step === index + 1 ? 'border-[#0361fc] text-[#0361fc]' : 'border-slate-300 text-slate-400')">
                                <span x-show="step <= index + 1" x-text="index + 1"></span>
                                <svg x-show="step > index + 1" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </div>
                            <span class="text-sm font-bold transition" :class="step >= index + 1 ? 'text-slate-900' : 'text-slate-400'" x-text="s"></span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_380px]">
                <!-- Main Form Area -->
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 mb-1">{{ $visaProduct->name }}</h1>
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-2" x-text="stepTitles[step-1]"></h2>
                    <p class="text-slate-600 mb-8" x-text="stepDescriptions[step-1]"></p>

                    <form id="visa-application-form" method="POST" action="{{ route('client.applications.store', $visaProduct) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Hidden Inputs to submit Alpine data -->
                        <input type="hidden" name="processing_time_type" x-model="form.processing_time_type">
                        <input type="hidden" name="departure_date" x-model="form.departure_date">
                        <input type="hidden" name="delivery_method" x-model="form.delivery_method">
                        <input type="hidden" name="hard_file_pickup" x-model="form.hard_file_pickup" :disabled="form.delivery_method === 'soft_file'">
                        <input type="hidden" name="hard_file_delivery" x-model="form.hard_file_delivery" :disabled="form.delivery_method === 'soft_file'">
                        <input type="hidden" name="pickup_address" x-model="form.pickup_address" :disabled="form.delivery_method === 'soft_file' || form.hard_file_pickup !== 'kurir'">
                        <input type="hidden" name="pickup_lat" x-model="form.pickup_lat" :disabled="form.delivery_method === 'soft_file' || form.hard_file_pickup !== 'kurir'">
                        <input type="hidden" name="pickup_lng" x-model="form.pickup_lng" :disabled="form.delivery_method === 'soft_file' || form.hard_file_pickup !== 'kurir'">
                        <input type="hidden" name="delivery_address" x-model="form.delivery_address" :disabled="form.delivery_method === 'soft_file' || form.hard_file_delivery !== 'kurir'">
                        <input type="hidden" name="delivery_lat" x-model="form.delivery_lat" :disabled="form.delivery_method === 'soft_file' || form.hard_file_delivery !== 'kurir'">
                        <input type="hidden" name="delivery_lng" x-model="form.delivery_lng" :disabled="form.delivery_method === 'soft_file' || form.hard_file_delivery !== 'kurir'">
                        <template x-for="addonId in form.addons">
                            <input type="hidden" name="addons[]" :value="addonId">
                        </template>
                        <input type="hidden" name="subtotal" :value="subtotal">
                        <input type="hidden" name="tax" :value="tax">
                        <input type="hidden" name="total" :value="total">

                        <!-- Step 1: Lengkapi detail -->
                        <div x-show="step === 1" class="space-y-6" x-transition.opacity>
                            <!-- Processing Time Info -->
                            <div class="rounded-2xl border border-rose-100 bg-rose-50 p-5">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-bold text-slate-900">Selesai pada*</span>
                                    <span class="font-bold text-slate-900">~14 Hari Kerja</span>
                                </div>
                                <p class="text-sm text-slate-700">*Jika Anda bisa menyerahkan semua dokumen Anda hari ini. Kami memiliki tingkat persetujuan 99%, lihat kebijakan pengembalian dana kami <a href="#" class="text-[#0361fc] font-bold hover:underline">di sini</a>.</p>
                            </div>

                            <!-- Jenis Visa -->
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <h3 class="font-extrabold text-lg text-slate-900 mb-4">Jenis visa</h3>
                                <div class="relative">
                                    <select x-model="form.processing_time_type" class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition font-medium">
                                        <option value="Reguler">Reguler</option>
                                        <option value="VIP">VIP (Expedited)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Rencana Perjalanan -->
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <h3 class="font-extrabold text-lg text-slate-900 mb-4">Rencana perjalanan</h3>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal keberangkatan</label>
                                
                                <div class="relative mb-4">
                                    <input type="text" x-ref="datePicker" x-model="form.departure_date" placeholder="Pilih tanggal keberangkatan" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition cursor-pointer">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                </div>

                                <label class="flex items-center gap-3 cursor-pointer mt-2">
                                    <input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#0361fc] focus:ring-[#0361fc]">
                                    <span class="text-slate-700 font-medium">Saya belum tahu</span>
                                </label>
                            </div>

                            <!-- Detail Aplikan -->
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <h3 class="font-extrabold text-lg text-slate-900 mb-2">Detail aplikan</h3>
                                <p class="text-sm text-slate-600 mb-6">Undang aplikan yang terdaftar atau tambahkan yang baru ke aplikasi ini. Kami akan mengirimkan email kepada aplikan yang belum terdaftar, untuk mendaftar dan aktivasi akun mereka.</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700">Traveler name</label>
                                        <input name="traveler_name" x-model="form.traveler_name" type="text" required class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition">
                                        @error('traveler_name')<p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>@enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-slate-700">Traveler email</label>
                                        <input name="traveler_email" x-model="form.traveler_email" type="email" required class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition">
                                        @error('traveler_email')<p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>@enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-slate-700">Traveler phone (optional)</label>
                                        <input name="traveler_phone" x-model="form.traveler_phone" type="text" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-slate-700">Notes (optional)</label>
                                        <textarea name="notes" x-model="form.notes" rows="2" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Add-ons -->
                        <div x-show="step === 2" style="display: none;" class="space-y-6" x-transition.opacity>
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <h3 class="font-extrabold text-lg text-slate-900 mb-2">Layanan tambahan</h3>
                                <p class="text-sm text-slate-600 mb-6">Dukungan ekstra untuk kenyamanan perjalanan Anda</p>
                                
                                <div class="space-y-4">
                                    <template x-for="addon in availableAddons" :key="addon.id">
                                        <div class="flex items-center justify-between p-4 rounded-xl border-2 transition"
                                             :class="form.addons.includes(addon.id) ? 'border-[#0361fc] bg-blue-50/30' : 'border-slate-200 hover:border-slate-300'">
                                            <div class="pr-4">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h4 class="font-bold text-slate-900" x-text="addon.name"></h4>
                                                </div>
                                                <p class="text-sm text-slate-600" x-text="addon.description || 'Tidak ada deskripsi'"></p>
                                                <p class="text-sm font-bold text-slate-900 mt-2">Dari <span x-text="formatRupiah(addon.price)"></span>/applicant</p>
                                            </div>
                                            <button type="button" @click="toggleAddon(addon.id)" 
                                                    class="shrink-0 px-4 py-2 rounded-full font-bold text-sm transition"
                                                    :class="form.addons.includes(addon.id) ? 'bg-rose-100 text-rose-700 hover:bg-rose-200' : 'bg-[#0361fc] text-white hover:bg-[#024bc0]'">
                                                <span x-text="form.addons.includes(addon.id) ? 'Hapus -' : 'Tambah +'"></span>
                                            </button>
                                        </div>
                                    </template>
                                    <div x-show="availableAddons.length === 0" class="text-center py-6 text-slate-500 italic">
                                        Tidak ada layanan tambahan yang tersedia saat ini.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Pengiriman -->
                        <div x-show="step === 3" style="display: none;" class="space-y-6" x-transition.opacity>
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <h3 class="font-extrabold text-lg text-slate-900 mb-4">Metode Pengiriman Dokumen</h3>
                                
                                <div class="space-y-4">
                                    <!-- Soft File Option -->
                                    <label class="flex items-start p-5 rounded-xl border-2 cursor-pointer transition relative overflow-hidden"
                                           :class="form.delivery_method === 'soft_file' ? 'border-[#0361fc] bg-blue-50/10' : 'border-slate-200 hover:border-slate-300'">
                                        <div class="flex items-center h-5 mt-1">
                                            <input type="radio" x-model="form.delivery_method" value="soft_file" class="w-5 h-5 text-[#0361fc] focus:ring-[#0361fc] border-slate-300">
                                        </div>
                                        <div class="ml-4 w-full">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-bold text-slate-900">Kirim Dokumen Digital (Soft File)</h4>
                                                    <p class="text-sm text-slate-600 mt-1">Unggah dokumen secara digital melalui portal klien kami. Cepat, aman, dan gratis.</p>
                                                </div>
                                                <span class="inline-block px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded shrink-0">Gratis (Default)</span>
                                            </div>
                                            
                                            <!-- Document Uploads (Visible if Soft File is selected) -->
                                            <div x-show="form.delivery_method === 'soft_file'" class="mt-6 space-y-4 pt-4 border-t border-slate-200" x-transition>
                                                <p class="text-sm font-bold text-slate-900 mb-2">Unggah dokumen yang dibutuhkan:</p>
                                                @foreach($visaProduct->documents as $document)
                                                    <div class="flex items-center justify-between p-3 border border-slate-200 rounded-xl bg-white shadow-sm hover:border-[#0361fc]/50 transition">
                                                        <div class="flex flex-col">
                                                            <span class="text-sm font-bold text-slate-800">{{ $document->name }} @if($document->is_required)<span class="text-rose-500">*</span>@endif</span>
                                                            <span class="text-xs text-slate-500">{{ $document->description ?? 'Format: PDF, JPG, PNG (Max 5MB)' }}</span>
                                                        </div>
                                                        <input type="file" name="documents[{{ $document->id }}]" accept=".pdf,.jpg,.jpeg,.png" class="text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#0361fc] hover:file:bg-blue-100 cursor-pointer" @if($document->is_required) x-bind:required="form.delivery_method === 'soft_file'" @endif>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Hard File Option -->
                                    <label class="flex items-start p-5 rounded-xl border-2 cursor-pointer transition relative overflow-hidden"
                                           :class="form.delivery_method === 'hard_file' ? 'border-[#0361fc] bg-blue-50/10' : 'border-slate-200 hover:border-slate-300'">
                                        <div class="flex items-center h-5 mt-1">
                                            <input type="radio" x-model="form.delivery_method" value="hard_file" class="w-5 h-5 text-[#0361fc] focus:ring-[#0361fc] border-slate-300">
                                        </div>
                                        <div class="ml-4 w-full">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-bold text-slate-900">Kirim Dokumen Fisik (Hard File)</h4>
                                                    <p class="text-sm text-slate-600 mt-1">Kami akan mengurus penjemputan dan pengiriman kembali dokumen fisik Anda via kurir.</p>
                                                </div>
                                                <span class="inline-block px-2 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded shrink-0">Biaya Tambahan: + Rp 158.000</span>
                                            </div>

                                            <!-- Hard File Sub-options (Visible if Hard File selected) -->
                                            <div x-show="form.delivery_method === 'hard_file'" class="mt-6 space-y-6 pt-4 border-t border-slate-200" x-transition>
                                                
                                                <!-- Pickup Dokumen -->
                                                <div>
                                                    <h5 class="font-bold text-slate-900 mb-3 text-sm">Pickup dokumen</h5>
                                                    <div class="space-y-3">
                                                        <label class="flex items-start p-4 rounded-xl border-2 cursor-pointer transition relative overflow-hidden"
                                                               :class="form.hard_file_pickup === 'kurir' ? 'border-[#0361fc] bg-blue-50/10' : 'border-slate-200 hover:border-slate-300'">
                                                            <input type="radio" x-model="form.hard_file_pickup" value="kurir" class="mt-0.5 mr-3 w-4 h-4 text-[#0361fc] focus:ring-[#0361fc] border-slate-300">
                                                            <div>
                                                                <h6 class="font-bold text-slate-800 text-sm">Gunakan layanan kurir dari kami</h6>
                                                                <p class="text-xs text-slate-600 mt-1">Kami akan melakukan pengambilan dokumen Anda dari alamat Anda. Tanggal dan waktu pengambilan dapat dipilih setelah melakukan pembayaran.</p>
                                                            </div>
                                                        </label>
                                                        <label class="flex items-start p-4 rounded-xl border-2 cursor-pointer transition relative overflow-hidden"
                                                               :class="form.hard_file_pickup === 'sendiri' ? 'border-[#0361fc] bg-blue-50/10' : 'border-slate-200 hover:border-slate-300'">
                                                            <input type="radio" x-model="form.hard_file_pickup" value="sendiri" class="mt-0.5 mr-3 w-4 h-4 text-[#0361fc] focus:ring-[#0361fc] border-slate-300">
                                                            <div>
                                                                <h6 class="font-bold text-slate-800 text-sm">Atur pengiriman sendiri</h6>
                                                                <p class="text-xs text-slate-600 mt-1">Anda perlu mengirimkan dokumen fisik Anda ke kantor kami.</p>
                                                            </div>
                                                        </label>

                                                        <!-- Map Placeholder for Pickup -->
                                                        <div x-show="form.hard_file_pickup === 'kurir'" class="mt-3" x-transition>
                                                            <!-- Confirmed Address UI -->
                                                            <div x-show="form.pickup_address" class="p-4 rounded-xl border border-[#0361fc] bg-blue-50/10 flex justify-between items-start">
                                                                <div class="flex gap-3">
                                                                    <svg class="w-5 h-5 text-[#0361fc] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                                    <div>
                                                                        <p class="font-bold text-slate-900 text-sm">Alamat Penjemputan</p>
                                                                        <p class="text-sm text-slate-600 mt-1" x-text="form.pickup_address"></p>
                                                                    </div>
                                                                </div>
                                                                <button type="button" @click="form.pickup_address = ''" class="text-[#0361fc] text-sm font-bold hover:underline shrink-0">Ubah</button>
                                                            </div>

                                                            <!-- Interactive Map -->
                                                            <div x-show="!form.pickup_address" class="rounded-xl border border-slate-200 overflow-hidden relative">
                                                                <div class="absolute top-3 left-3 right-3 bg-white rounded-lg shadow-md px-3 py-2 flex items-center z-[1000] border border-slate-100">
                                                                    <svg class="w-4 h-4 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                                    <input type="text" x-model="pickupSearchQuery" @keydown.enter.prevent="searchLocation('pickup')" placeholder="Cari jalan, bangunan atau tempat umum" class="w-full text-sm outline-none text-slate-700 bg-transparent">
                                                                    <button type="button" @click="searchLocation('pickup')" class="text-xs font-bold text-[#0361fc] ml-2 shrink-0 px-2 py-1 bg-blue-50 rounded">Cari</button>
                                                                </div>
                                                                <div id="pickup-map" class="w-full h-64 z-0 relative"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Pengiriman Hasil -->
                                                <div>
                                                    <h5 class="font-bold text-slate-900 mb-3 text-sm">Pengiriman hasil</h5>
                                                    <div class="space-y-3">
                                                        <label class="flex items-start p-4 rounded-xl border-2 cursor-pointer transition relative overflow-hidden"
                                                               :class="form.hard_file_delivery === 'kurir' ? 'border-[#0361fc] bg-blue-50/10' : 'border-slate-200 hover:border-slate-300'">
                                                            <input type="radio" x-model="form.hard_file_delivery" value="kurir" class="mt-0.5 mr-3 w-4 h-4 text-[#0361fc] focus:ring-[#0361fc] border-slate-300">
                                                            <div>
                                                                <h6 class="font-bold text-slate-800 text-sm">Gunakan layanan kurir dari kami</h6>
                                                                <p class="text-xs text-slate-600 mt-1">Kami akan mengirimkan dokumen ke alamat Anda. Tanggal dan waktu pengiriman dapat dipilih setelah melakukan pembayaran.</p>
                                                            </div>
                                                        </label>
                                                        <label class="flex items-start p-4 rounded-xl border-2 cursor-pointer transition relative overflow-hidden"
                                                               :class="form.hard_file_delivery === 'sendiri' ? 'border-[#0361fc] bg-blue-50/10' : 'border-slate-200 hover:border-slate-300'">
                                                            <input type="radio" x-model="form.hard_file_delivery" value="sendiri" class="mt-0.5 mr-3 w-4 h-4 text-[#0361fc] focus:ring-[#0361fc] border-slate-300">
                                                            <div>
                                                                <h6 class="font-bold text-slate-800 text-sm">Atur pengambilan sendiri</h6>
                                                                <p class="text-xs text-slate-600 mt-1">Ambil dokumen Anda dari kantor kami.</p>
                                                            </div>
                                                        </label>

                                                        <!-- Map Placeholder for Delivery -->
                                                        <div x-show="form.hard_file_delivery === 'kurir'" class="mt-3" x-transition>
                                                            <!-- Confirmed Address UI -->
                                                            <div x-show="form.delivery_address" class="p-4 rounded-xl border border-[#0361fc] bg-blue-50/10 flex justify-between items-start">
                                                                <div class="flex gap-3">
                                                                    <svg class="w-5 h-5 text-[#0361fc] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                                    <div>
                                                                        <p class="font-bold text-slate-900 text-sm">Alamat Pengiriman</p>
                                                                        <p class="text-sm text-slate-600 mt-1" x-text="form.delivery_address"></p>
                                                                    </div>
                                                                </div>
                                                                <button type="button" @click="form.delivery_address = ''" class="text-[#0361fc] text-sm font-bold hover:underline shrink-0">Ubah</button>
                                                            </div>

                                                            <!-- Interactive Map -->
                                                            <div x-show="!form.delivery_address" class="rounded-xl border border-slate-200 overflow-hidden relative">
                                                                <div class="absolute top-3 left-3 right-3 bg-white rounded-lg shadow-md px-3 py-2 flex items-center z-[1000] border border-slate-100">
                                                                    <svg class="w-4 h-4 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                                    <input type="text" x-model="deliverySearchQuery" @keydown.enter.prevent="searchLocation('delivery')" placeholder="Cari jalan, bangunan atau tempat umum" class="w-full text-sm outline-none text-slate-700 bg-transparent">
                                                                    <button type="button" @click="searchLocation('delivery')" class="text-xs font-bold text-[#0361fc] ml-2 shrink-0 px-2 py-1 bg-blue-50 rounded">Cari</button>
                                                                </div>
                                                                <div id="delivery-map" class="w-full h-64 z-0 relative"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Review -->
                        <div x-show="step === 4" style="display: none;" class="space-y-6" x-transition.opacity>
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                                <div class="flex items-start gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100 mb-6">
                                    <svg class="w-5 h-5 text-slate-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <p class="text-sm text-slate-700 font-medium">Silakan review kembali semua informasi yang telah diisi sebelum Anda melanjutkan ke pembayaran.</p>
                                </div>

                                <div class="grid grid-cols-2 gap-6 mb-8">
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-500 mb-1">Tanggal keberangkatan</h4>
                                        <p class="font-bold text-slate-900" x-text="form.departure_date ? form.departure_date : 'Belum ditentukan'"></p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-500 mb-1">Waktu pemrosesan</h4>
                                        <p class="font-bold text-slate-900" x-text="form.processing_time_type"></p>
                                    </div>
                                </div>

                                <div class="border-t border-slate-200 pt-6 mb-6">
                                    <h4 class="text-sm font-bold text-slate-900 mb-4">Detail aplikan</h4>
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-lg">
                                            <span x-text="form.traveler_name ? form.traveler_name.charAt(0).toUpperCase() : '?'"></span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900" x-text="form.traveler_name || 'Nama belum diisi'"></p>
                                            <p class="text-sm text-slate-500" x-text="form.traveler_email || 'Email belum diisi'"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-slate-200 pt-6">
                                    <h4 class="text-sm font-bold text-slate-900 mb-4">Pengiriman dokumen</h4>
                                    <p class="font-bold text-slate-900" x-text="form.delivery_method === 'soft_file' ? 'Digital (Soft File)' : 'Fisik (Hard File)'"></p>
                                    
                                    <template x-if="form.delivery_method === 'soft_file'">
                                        <p class="text-sm text-slate-500 mt-1">Upload dokumen pada tahap sebelumnya.</p>
                                    </template>
                                    
                                    <template x-if="form.delivery_method === 'hard_file'">
                                        <div class="mt-4 space-y-4">
                                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                <p class="text-xs font-bold text-slate-500 mb-1">Penyerahan Dokumen (Pickup)</p>
                                                <p class="text-sm font-medium text-slate-900" x-text="form.hard_file_pickup === 'kurir' ? 'Penjemputan oleh Kurir Alliago' : 'Antar Sendiri ke Kantor Alliago'"></p>
                                                <p x-show="form.hard_file_pickup === 'kurir' && form.pickup_address" class="text-xs text-slate-600 mt-1" x-text="form.pickup_address"></p>
                                                <p x-show="form.hard_file_pickup === 'kurir' && !form.pickup_address" class="text-xs text-rose-500 mt-1">Alamat belum dipilih!</p>
                                            </div>
                                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                <p class="text-xs font-bold text-slate-500 mb-1">Pengembalian Dokumen (Delivery)</p>
                                                <p class="text-sm font-medium text-slate-900" x-text="form.hard_file_delivery === 'kurir' ? 'Pengiriman oleh Kurir Alliago' : 'Ambil Sendiri di Kantor Alliago'"></p>
                                                <p x-show="form.hard_file_delivery === 'kurir' && form.delivery_address" class="text-xs text-slate-600 mt-1" x-text="form.delivery_address"></p>
                                                <p x-show="form.hard_file_delivery === 'kurir' && !form.delivery_address" class="text-xs text-rose-500 mt-1">Alamat belum dipilih!</p>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="border-t border-slate-200 pt-6 mt-6">
                                    <h4 class="text-sm font-bold text-slate-900 mb-4">Metode Pembayaran</h4>
                                    <div class="space-y-3">
                                        @foreach($paymentMethods as $pm)
                                            <label class="flex items-start p-4 rounded-xl border-2 cursor-pointer transition relative overflow-hidden"
                                                   :class="form.payment_method_id === '{{ $pm->id }}' ? 'border-[#0361fc] bg-blue-50/10' : 'border-slate-200 hover:border-slate-300'">
                                                <input type="radio" name="payment_method_id" x-model="form.payment_method_id" value="{{ $pm->id }}" class="mt-0.5 mr-3 w-4 h-4 text-[#0361fc] focus:ring-[#0361fc] border-slate-300" required>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between">
                                                        <h6 class="font-bold text-slate-800 text-sm">{{ $pm->name }}</h6>
                                                        @if($pm->provider === 'xendit')
                                                            <span class="text-[10px] font-bold bg-blue-100 text-[#0361fc] px-2 py-0.5 rounded-full">Otomatis</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-xs text-slate-600 mt-1">{{ $pm->description ? strip_tags($pm->description) : 'Lanjutkan pembayaran dengan ' . $pm->name }}</p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p x-show="step === 4 && !form.payment_method_id" class="text-xs text-rose-500 mt-3 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        Silakan pilih metode pembayaran untuk melanjutkan
                                    </p>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- Right Sidebar: Rincian Harga -->
                <aside class="h-fit sticky top-28">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="font-extrabold text-xl text-slate-900 mb-4">Rincian harga</h3>
                        
                        <div x-show="discountPrice > 0" class="flex items-center gap-2 bg-pink-50 text-pink-700 p-3 rounded-xl mb-4 font-medium text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Anda menghemat <span class="font-bold" x-text="formatRupiah(basePrice - discountPrice)"></span>
                        </div>

                        <div class="space-y-2 text-sm text-slate-600 mb-6 pb-6 border-b border-slate-200">
                            <div class="flex justify-between">
                                <span>Jenis visa</span>
                                <span>Biaya visa • <span x-text="form.processing_time_type"></span></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Masa berlaku</span>
                                <span>{{ $visaProduct->validity ?: '1 bulan' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Periode tinggal</span>
                                <span>{{ $visaProduct->stay_duration ?: '1 bulan' }}</span>
                            </div>
                        </div>

                        <!-- Breakdown -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center cursor-pointer font-bold text-slate-900">
                                <span>Aplikan 1</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            </div>
                            
                            <div class="space-y-3 text-sm pl-2">
                                <div class="flex justify-between text-slate-600">
                                    <span>Biaya visa & dukungan</span>
                                    <span class="text-slate-900 font-medium" x-text="formatRupiah(basePrice)"></span>
                                </div>
                                <div x-show="discountPrice > 0" class="flex justify-between text-pink-600">
                                    <span>Diskon visa & dukungan</span>
                                    <span class="font-medium" x-text="'- ' + formatRupiah(basePrice - discountPrice)"></span>
                                </div>

                                <!-- Addons breakdown -->
                                <template x-for="addonId in form.addons" :key="'sidebar-'+addonId">
                                    <div class="flex justify-between text-slate-600">
                                        <span x-text="getAddonName(addonId)"></span>
                                        <span class="text-slate-900 font-medium" x-text="formatRupiah(getAddonPrice(addonId))"></span>
                                    </div>
                                </template>

                                <!-- Delivery breakdown -->
                                <div x-show="form.delivery_method === 'hard_file'" class="flex justify-between text-slate-600">
                                    <span>Pickup dokumen</span>
                                    <span class="text-slate-900 font-medium">Rp 79.000</span>
                                </div>
                                <div x-show="form.delivery_method === 'hard_file'" class="flex justify-between text-slate-600">
                                    <span>Pengiriman hasil</span>
                                    <span class="text-slate-900 font-medium">Rp 79.000</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between pt-3 border-t border-slate-100 text-sm">
                                <span class="font-medium text-slate-600">Subtotal Aplikan</span>
                                <span class="font-bold text-slate-900" x-text="formatRupiah(subtotal)"></span>
                            </div>
                        </div>

                        <!-- Taxes -->
                        <div class="space-y-3 mb-6">
                            <h4 class="font-bold text-slate-900 text-sm">Lainnya</h4>
                            <div class="flex justify-between text-sm text-slate-600">
                                <span>Pajak (1.1%)</span>
                                <span class="text-slate-900 font-medium" x-text="formatRupiah(tax)"></span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-center pt-4 border-t border-slate-200 mb-6">
                            <span class="font-extrabold text-slate-900">Total Pembayaran</span>
                            <span class="font-extrabold text-rose-600 text-lg" x-text="formatRupiah(total)"></span>
                        </div>

                        <!-- Action Buttons -->
                        <div>
                            <button x-show="step < 4" type="button" @click="nextStep()" class="w-full inline-flex items-center justify-center rounded-xl bg-[#0361fc] px-6 py-4 font-bold text-white hover:bg-[#024bc0] shadow-sm transition text-lg">
                                Lanjutkan
                            </button>
                            <button x-show="step === 4" type="submit" form="visa-application-form" class="w-full inline-flex items-center justify-center rounded-xl bg-[#0361fc] px-6 py-4 font-bold text-white hover:bg-[#024bc0] shadow-sm transition text-lg">
                                Bayar
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Leaflet & Alpine -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        /* Override flatpickr styling to match custom theme */
        .flatpickr-calendar {
            font-family: inherit;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            border: 1px solid #e2e8f0;
            padding: 10px;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background: #0361fc;
            border-color: #0361fc;
        }
        .flatpickr-day.today {
            border-color: #0361fc;
        }
        .flatpickr-day {
            border-radius: 0.5rem;
        }
    </style>

    <script>
        window.confirmMapLocation = function(type, lat, lng, address) {
            window.dispatchEvent(new CustomEvent('map-location-confirmed', {
                detail: { type, lat, lng, address }
            }));
            document.querySelectorAll('.leaflet-popup-close-button').forEach(btn => btn.click());
        };

        document.addEventListener('alpine:init', () => {
            Alpine.data('visaOrderForm', () => ({
                step: 1,
                stepTitles: [
                    'Lengkapi detail',
                    'Add-ons',
                    'Pengiriman dokumen',
                    'Review & bayar'
                ],
                stepDescriptions: [
                    'Lengkapi detail aplikan untuk melanjutkan aplikasi Anda',
                    'Pilih layanan untuk mendukung pembuatan visa dan rencana perjalanan Anda.',
                    'Pilih metode pengiriman atau pengambilan dokumen fisik Anda ke kantor kami.',
                    'Mohon periksa kembali semua informasi sebelum melanjutkan ke pembayaran.'
                ],
                pickupMap: null,
                pickupMarker: null,
                deliveryMap: null,
                deliveryMarker: null,
                pickupSearchQuery: '',
                deliverySearchQuery: '',
                form: {
                    traveler_name: '{{ old('traveler_name', $client->name) }}',
                    traveler_email: '{{ old('traveler_email', $client->email) }}',
                    traveler_phone: '{{ old('traveler_phone', $client->phone) }}',
                    notes: '{{ old('notes') }}',
                    departure_date: '{{ old('departure_date') }}',
                    processing_time_type: '{{ old('processing_time_type', 'Reguler') }}',
                    delivery_method: '{{ old('delivery_method', 'soft_file') }}',
                    hard_file_pickup: '{{ old('hard_file_pickup', 'kurir') }}',
                    hard_file_delivery: '{{ old('hard_file_delivery', 'kurir') }}',
                    pickup_address: '{{ old('pickup_address') }}',
                    pickup_lat: '{{ old('pickup_lat') }}',
                    pickup_lng: '{{ old('pickup_lng') }}',
                    delivery_address: '{{ old('delivery_address') }}',
                    delivery_lat: '{{ old('delivery_lat') }}',
                    delivery_lng: '{{ old('delivery_lng') }}',
                    payment_method_id: '{{ old('payment_method_id') }}',
                    addons: @json(old('addons', [])),
                },
                visaCurrency: '{{ \App\Models\VisaSetting::current()->currency }}',
                exchangeRate: {{ \App\Models\VisaSetting::getIdrToTargetRate(\App\Models\VisaSetting::current()->currency) }},
                basePrice: {{ (float) ($visaProduct->display_base_price) }},
                discountPrice: {{ (float) ($visaProduct->display_discount_price ?: 0) }},
                availableAddons: @json($availableAddons),
                
                init() {
                    // Initialize Flatpickr
                    if (this.$refs.datePicker) {
                        flatpickr(this.$refs.datePicker, {
                            dateFormat: "Y-m-d",
                            minDate: "today",
                            onChange: (selectedDates, dateStr) => {
                                this.form.departure_date = dateStr;
                            }
                        });
                    }

                    this.$watch('form.hard_file_pickup', (value) => {
                        if (value === 'kurir' && this.form.delivery_method === 'hard_file') this.initMap('pickup');
                    });
                    this.$watch('form.hard_file_delivery', (value) => {
                        if (value === 'kurir' && this.form.delivery_method === 'hard_file') this.initMap('delivery');
                    });
                    this.$watch('form.delivery_method', (value) => {
                        if (value === 'hard_file') {
                            if (this.form.hard_file_pickup === 'kurir') this.initMap('pickup');
                            if (this.form.hard_file_delivery === 'kurir') this.initMap('delivery');
                        }
                    });

                    window.addEventListener('map-location-confirmed', (e) => {
                        const { type, lat, lng, address } = e.detail;
                        if (type === 'pickup') {
                            this.form.pickup_address = address;
                            this.form.pickup_lat = lat;
                            this.form.pickup_lng = lng;
                        } else {
                            this.form.delivery_address = address;
                            this.form.delivery_lat = lat;
                            this.form.delivery_lng = lng;
                        }
                    });
                },

                initMap(type) {
                    let mapContainerId = type === 'pickup' ? 'pickup-map' : 'delivery-map';
                    
                    setTimeout(() => {
                        let container = document.getElementById(mapContainerId);
                        if (!container) return;
                        
                        if (type === 'pickup' && this.pickupMap) {
                            this.pickupMap.invalidateSize();
                            return;
                        }
                        if (type === 'delivery' && this.deliveryMap) {
                            this.deliveryMap.invalidateSize();
                            return;
                        }

                        let map = L.map(mapContainerId).setView([-6.200000, 106.816666], 13);
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
                            subdomains: 'abcd',
                            maxZoom: 20
                        }).addTo(map);

                        if (type === 'pickup') this.pickupMap = map;
                        else this.deliveryMap = map;

                        map.on('click', async (e) => {
                            await this.reverseGeocode(e.latlng.lat, e.latlng.lng, type);
                        });
                    }, 300);
                },

                async reverseGeocode(lat, lng, type) {
                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                        const data = await res.json();
                        this.setMarker(lat, lng, data.display_name, type);
                    } catch (e) {
                        console.error('Reverse geocode failed:', e);
                    }
                },

                async searchLocation(type) {
                    let query = type === 'pickup' ? this.pickupSearchQuery : this.deliverySearchQuery;
                    if (!query) return;

                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`);
                        const data = await res.json();
                        if (data && data.length > 0) {
                            const firstResult = data[0];
                            this.setMarker(firstResult.lat, firstResult.lon, firstResult.display_name, type);
                            let map = type === 'pickup' ? this.pickupMap : this.deliveryMap;
                            map.setView([firstResult.lat, firstResult.lon], 16);
                        }
                    } catch (e) {
                        console.error('Search failed:', e);
                    }
                },

                setMarker(lat, lng, address, type) {
                    let map = type === 'pickup' ? this.pickupMap : this.deliveryMap;
                    let marker = type === 'pickup' ? this.pickupMarker : this.deliveryMarker;

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    let customIcon = L.divIcon({
                        className: 'custom-map-marker',
                        html: `<svg class="w-10 h-10 text-[#0361fc] drop-shadow-lg -ml-5 -mt-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>`,
                        iconSize: [40, 40],
                        iconAnchor: [20, 40],
                        popupAnchor: [0, -40]
                    });

                    let newMarker = L.marker([lat, lng], {icon: customIcon}).addTo(map);
                    
                    if (type === 'pickup') this.pickupMarker = newMarker;
                    else this.deliveryMarker = newMarker;

                    let popupContent = `
                        <div class="text-center min-w-[200px] p-2">
                            <p class="font-bold text-slate-800 text-sm mb-1 line-clamp-2">${address}</p>
                            <div class="flex gap-2 justify-center mt-3">
                                <button type="button" onclick="window.confirmMapLocation('${type}', ${lat}, ${lng}, '${address.replace(/'/g, "\\'")}')" class="bg-[#0361fc] text-white px-3 py-1.5 rounded-lg font-bold text-xs hover:bg-[#024bc0] transition w-full shadow-sm">Gunakan alamat ini</button>
                            </div>
                        </div>
                    `;

                    newMarker.bindPopup(popupContent).openPopup();
                    map.setView([lat, lng]);
                },

                get subtotal() {
                    let total = this.discountPrice > 0 ? this.discountPrice : this.basePrice;
                    
                    this.availableAddons.forEach(addon => {
                        if (this.form.addons.includes(addon.id) || this.form.addons.includes(String(addon.id))) {
                            total += parseFloat(addon.price);
                        }
                    });

                    if (this.form.delivery_method === 'hard_file') {
                        let courierFee = 79000 / this.exchangeRate;
                        if (this.form.hard_file_pickup === 'kurir') total += courierFee;
                        if (this.form.hard_file_delivery === 'kurir') total += courierFee;
                    }

                    return total;
                },
                get tax() {
                    return Math.round(this.subtotal * 0.011);
                },
                get total() {
                    return this.subtotal + this.tax;
                },
                formatRupiah(amount) {
                    if (this.visaCurrency === 'IDR') {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
                    } else {
                        return 'RM ' + new Intl.NumberFormat('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(amount);
                    }
                },
                toggleAddon(id) {
                    const idStr = String(id);
                    const idNum = Number(id);
                    if (this.form.addons.includes(idStr) || this.form.addons.includes(idNum)) {
                        this.form.addons = this.form.addons.filter(a => String(a) !== idStr);
                    } else {
                        this.form.addons.push(idNum);
                    }
                },
                getAddonName(id) {
                    const addon = this.availableAddons.find(a => String(a.id) === String(id));
                    return addon ? addon.name : '';
                },
                getAddonPrice(id) {
                    const addon = this.availableAddons.find(a => String(a.id) === String(id));
                    return addon ? parseFloat(addon.price) : 0;
                },
                nextStep() {
                    if (this.step < 4) {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        this.step++;
                    }
                }
            }));
        });
    </script>
    @endpush
</x-layouts.app>
