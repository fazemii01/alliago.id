<x-layouts.app :title="'Create Order | ' . $visaProduct->name">
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="mx-auto max-w-5xl px-6 py-10">
            <a href="{{ route('visa.show', $visaProduct->slug) }}" class="text-sm text-amber-300">Back to visa details</a>

            <div class="mt-6 grid gap-8 lg:grid-cols-[minmax(0,1fr)_320px]">
                <div class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Create Order</p>
                    <h1 class="mt-2 text-4xl font-semibold">{{ $visaProduct->name }}</h1>
                    <p class="mt-3 text-slate-300">Start a simple order for this visa product. The next step after creation is document follow-up inside your client area.</p>

                    <form method="POST" action="{{ route('client.applications.store', $visaProduct) }}" class="mt-8 space-y-6">
                        @csrf

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label for="traveler_name" class="block text-sm font-medium text-slate-200">Traveler name</label>
                                <input id="traveler_name" name="traveler_name" type="text" value="{{ old('traveler_name', $client->name) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-amber-300">
                                @error('traveler_name')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="traveler_email" class="block text-sm font-medium text-slate-200">Traveler email</label>
                                <input id="traveler_email" name="traveler_email" type="email" value="{{ old('traveler_email', $client->email) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-amber-300">
                                @error('traveler_email')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="traveler_phone" class="block text-sm font-medium text-slate-200">Traveler phone</label>
                            <input id="traveler_phone" name="traveler_phone" type="text" value="{{ old('traveler_phone', $client->phone) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-amber-300">
                            @error('traveler_phone')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-200">Initial notes</label>
                            <textarea id="notes" name="notes" rows="4" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-amber-300" placeholder="Add anything admin should know before reviewing your documents.">{{ old('notes') }}</textarea>
                            @error('notes')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="rounded-2xl bg-amber-400 px-6 py-3 font-semibold text-slate-950 hover:bg-amber-300">
                            Create order
                        </button>
                    </form>
                </div>

                <aside class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                    <p class="text-sm text-slate-400">Selected product</p>
                    <h2 class="mt-2 text-2xl font-semibold">{{ $visaProduct->name }}</h2>
                    <p class="mt-2 text-sm text-amber-300">{{ $visaProduct->country->name }}</p>
                    <p class="mt-4 text-sm text-slate-300">{{ $visaProduct->short_description ?: 'This order will be created as a lightweight draft first.' }}</p>

                    <div class="mt-6 space-y-3 text-sm text-slate-300">
                        <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">Processing time: {{ $visaProduct->processing_time ?: 'Contact support' }}</div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">Required documents: {{ $visaProduct->documents->count() }}</div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">Starting price: IDR {{ number_format((float) $visaProduct->discount_price ?: (float) $visaProduct->base_price, 0, ',', '.') }}</div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-layouts.app>
