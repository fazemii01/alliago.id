<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('client.login_title') }} | Alliago.id</title>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto flex min-h-screen max-w-6xl items-center justify-center px-4 sm:px-6 py-12">
        <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
            <div class="mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#0361fc] hover:text-blue-700">
                    {{ __('client.login_back') }}
                </a>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">{{ __('client.login_title') }}</h1>
                <p class="mt-2 text-sm text-slate-600">{{ __('client.login_subtitle') }}</p>
            </div>

            <form method="POST" action="{{ route('client.login.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-slate-700">{{ __('client.login_email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition" />
                    @error('email')<p class="mt-2 text-sm font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-slate-700">{{ __('client.login_password') }}</label>
                    <input id="password" name="password" type="password" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition" />
                </div>

                <label class="flex items-center gap-3 text-sm font-medium text-slate-600">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-[#0361fc] focus:ring-[#0361fc]" />
                    {{ __('client.login_remember') }}
                </label>

                <button type="submit" class="w-full rounded-full bg-[#0361fc] px-4 py-3 font-bold text-white shadow-sm shadow-[#0361fc]/20 hover:bg-blue-700 transition">
                    {{ __('client.login_btn') }}
                </button>
            </form>

            <p class="mt-6 text-sm text-slate-600 text-center">
                {{ __('client.login_no_account') }}
                <a href="{{ route('client.register') }}" class="font-bold text-[#0361fc] hover:text-blue-700">{{ __('client.login_create') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
