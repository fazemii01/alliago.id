<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account | Alliago.id</title>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto flex min-h-screen max-w-6xl items-center justify-center px-4 sm:px-6 py-12">
        <div class="w-full max-w-xl rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
            <div class="mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#0361fc] hover:text-blue-700">
                    &larr; Back to website
                </a>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">Create your account</h1>
                <p class="mt-2 text-sm text-slate-600">Register once to manage your visa orders, personal information, and document follow-up in one place.</p>
            </div>

            <form method="POST" action="{{ route('client.register.store') }}" class="grid gap-5 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2">
                    <label for="name" class="mb-2 block text-sm font-bold text-slate-700">Full name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition" />
                    @error('name')<p class="mt-2 text-sm font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition" />
                    @error('email')<p class="mt-2 text-sm font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="phone" class="mb-2 block text-sm font-bold text-slate-700">Phone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition" />
                    @error('phone')<p class="mt-2 text-sm font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                    <input id="password" name="password" type="password" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition" />
                    @error('password')<p class="mt-2 text-sm font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-700">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition" />
                </div>

                <div class="md:col-span-2 mt-2">
                    <button type="submit" class="w-full rounded-full bg-[#0361fc] px-4 py-3 font-bold text-white shadow-sm shadow-[#0361fc]/20 hover:bg-blue-700 transition">
                        Create account
                    </button>
                    
                    <p class="mt-6 text-sm text-slate-600 text-center">
                        Already have an account?
                        <a href="{{ route('client.login') }}" class="font-bold text-[#0361fc] hover:text-blue-700">Sign in</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
