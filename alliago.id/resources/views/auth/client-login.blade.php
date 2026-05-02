<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <div class="mx-auto flex min-h-screen max-w-6xl items-center justify-center px-6 py-12">
        <div class="w-full max-w-md rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur">
            <div class="mb-8">
                <a href="{{ url('/') }}" class="text-sm text-amber-300">Back to website</a>
                <h1 class="mt-4 text-3xl font-semibold">User login</h1>
                <p class="mt-2 text-sm text-slate-300">Access your orders, profile details, and document communication with admin.</p>
            </div>

            <form method="POST" action="{{ route('client.login.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white outline-none ring-0" />
                    @error('email')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium">Password</label>
                    <input id="password" name="password" type="password" required class="w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white outline-none ring-0" />
                </div>

                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input type="checkbox" name="remember" value="1" class="rounded border-white/20 bg-slate-900" />
                    Remember me
                </label>

                <button type="submit" class="w-full rounded-2xl bg-amber-400 px-4 py-3 font-semibold text-slate-950 transition hover:bg-amber-300">
                    Sign in
                </button>
            </form>

            <p class="mt-6 text-sm text-slate-300">
                Need an account?
                <a href="{{ route('client.register') }}" class="font-medium text-amber-300">Create one</a>
            </p>
        </div>
    </div>
</body>
</html>
