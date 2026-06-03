<x-layouts.app>
    <div class="mx-auto max-w-5xl px-4 py-8 md:px-6">
        <h1 class="mb-8 text-2xl font-bold text-slate-900">Pengaturan Akun</h1>

        <div class="grid gap-8 md:grid-cols-2">
            <!-- Update Profile Form -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-1 text-lg font-semibold text-slate-900">Informasi Profil</h2>
                <p class="mb-6 text-sm text-slate-500">Perbarui informasi profil dan alamat email akun Anda.</p>

                @if (session('status') === 'profile-updated')
                    <div class="mb-4 rounded-xl bg-green-50 p-4 text-sm font-medium text-green-700">
                        Profil berhasil diperbarui.
                    </div>
                @endif

                <form method="POST" action="{{ route('client.profile.update') }}" class="grid gap-5">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 outline-none transition focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc]" required autofocus>
                        @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 outline-none transition focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc]" required>
                        @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="mb-1.5 block text-sm font-medium text-slate-700">No. WhatsApp</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 outline-none transition focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc]">
                        @error('phone') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="rounded-full bg-[#0361fc] px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Update Password Form -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-1 text-lg font-semibold text-slate-900">Ubah Password</h2>
                <p class="mb-6 text-sm text-slate-500">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>

                @if (session('status') === 'password-updated')
                    <div class="mb-4 rounded-xl bg-green-50 p-4 text-sm font-medium text-green-700">
                        Password berhasil diperbarui.
                    </div>
                @endif

                <form method="POST" action="{{ route('client.password.update') }}" class="grid gap-5">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="mb-1.5 block text-sm font-medium text-slate-700">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 outline-none transition focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc]" required>
                        @error('current_password', 'updatePassword') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password Baru</label>
                        <input type="password" id="password" name="password" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 outline-none transition focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc]" required>
                        @error('password', 'updatePassword') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 outline-none transition focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc]" required>
                        @error('password_confirmation', 'updatePassword') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="rounded-full bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
