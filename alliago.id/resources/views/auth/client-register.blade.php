<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account</title>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <div class="mx-auto flex min-h-screen max-w-6xl items-center justify-center px-6 py-12">
        <div class="w-full max-w-xl rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur">
            <div class="mb-8">
                <a href="{{ url('/') }}" class="text-sm text-amber-300">Back to website</a>
                <h1 class="mt-4 text-3xl font-semibold">Create your account</h1>
                <p class="mt-2 text-sm text-slate-300">Register once to manage your visa orders, personal information, and document follow-up in one place.</p>
            </div>

            <form method="POST" action="{{ route('client.register.store') }}" class="grid gap-5 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2">
                    <label for="name" class="mb-2 block text-sm font-medium">Full name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white" />
                    @error('name')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white" />
                    @error('email')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="phone" class="mb-2 block text-sm font-medium">Phone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white" />
                    @error('phone')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium">Password</label>
                    <input id="password" name="password" type="password" required class="w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white" />
                    @error('password')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white" />
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="w-full rounded-2xl bg-amber-400 px-4 py-3 font-semibold text-slate-950 transition hover:bg-amber-300">
                        Create account
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
