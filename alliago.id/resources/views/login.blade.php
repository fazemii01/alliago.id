<x-layouts.app title="Login | Alliago.id">
    <div class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
        <div class="w-full max-w-md card rounded-[32px] p-8 md:p-12 relative overflow-hidden">
            <!-- Decorative background blob -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-blue-50 blur-2xl"></div>
            
            <div class="relative z-10">
                <div class="flex justify-center mb-8">
                    <a href="/"><img src="/images/alliago-logo.jpeg" alt="AlliaGo" class="w-12 h-12 rounded-xl object-cover shadow-lg shadow-blue-500/20"></a>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-2">Welcome Back</h1>
                    <p class="text-sm text-slate-500 font-medium">Log in to manage your visa applications.</p>
                </div>

                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="p-3 rounded-xl bg-red-50 text-red-600 text-xs font-semibold">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Email Address</label>
                        <div class="field rounded-2xl p-3 @error('email') border-red-500 @enderror">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" class="text-sm text-slate-800 placeholder:text-slate-400 font-medium bg-transparent outline-none w-full" required>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-semibold text-slate-700">Password</label>
                            <a href="#" class="text-xs font-semibold text-brand hover:underline">Forgot?</a>
                        </div>
                        <div class="field rounded-2xl p-3 @error('password') border-red-500 @enderror">
                            <input type="password" name="password" placeholder="••••••••" class="text-sm text-slate-800 placeholder:text-slate-400 font-medium bg-transparent outline-none w-full" required>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full btn-primary rounded-full py-3.5 text-sm font-semibold shadow-md">
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-xs font-medium text-slate-500">
                        Don't have an account? 
                        <a href="#" class="text-brand font-semibold hover:underline">Create one</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
