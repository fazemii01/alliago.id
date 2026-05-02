<x-layouts.app :title="'Create Order | ' . $visaProduct->name">
    <x-home.header />

    <div class="min-h-screen bg-slate-50 text-slate-900 pt-24 pb-16">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('visa.show', $visaProduct->slug) }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#0361fc] hover:text-blue-700">
                &larr; Back to visa details
            </a>

            <div class="mt-6 grid gap-8 lg:grid-cols-[minmax(0,1fr)_320px]">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#0361fc]">Create Order</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl text-slate-900">{{ $visaProduct->name }}</h1>
                    <p class="mt-3 text-lg text-slate-600">Start a simple order for this visa product. The next step after creation is document follow-up inside your client area.</p>

                    <form method="POST" action="{{ route('client.applications.store', $visaProduct) }}" class="mt-8 space-y-6">
                        @csrf

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label for="traveler_name" class="block text-sm font-bold text-slate-700">Traveler name</label>
                                <input id="traveler_name" name="traveler_name" type="text" value="{{ old('traveler_name', $client->name) }}" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition">
                                @error('traveler_name')<p class="mt-2 text-sm font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="traveler_email" class="block text-sm font-bold text-slate-700">Traveler email</label>
                                <input id="traveler_email" name="traveler_email" type="email" value="{{ old('traveler_email', $client->email) }}" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition">
                                @error('traveler_email')<p class="mt-2 text-sm font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="traveler_phone" class="block text-sm font-bold text-slate-700">Traveler phone</label>
                            <input id="traveler_phone" name="traveler_phone" type="text" value="{{ old('traveler_phone', $client->phone) }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition">
                            @error('traveler_phone')<p class="mt-2 text-sm font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-bold text-slate-700">Initial notes</label>
                            <textarea id="notes" name="notes" rows="4" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none transition" placeholder="Add anything admin should know before reviewing your documents.">{{ old('notes') }}</textarea>
                            @error('notes')<p class="mt-2 text-sm font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-full bg-[#0361fc] px-6 py-3 font-bold text-white hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20 transition">
                            Create order
                        </button>
                    </form>
                </div>

                <aside class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm h-fit">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Selected product</p>
                    <h2 class="mt-1 text-2xl font-bold text-slate-900">{{ $visaProduct->name }}</h2>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-[#0361fc]">{{ $visaProduct->country->name }}</p>
                    <p class="mt-4 text-sm text-slate-600">{{ $visaProduct->short_description ?: 'This order will be created as a lightweight draft first.' }}</p>

                    <div class="mt-6 space-y-3 text-sm text-slate-700">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">Processing time</span>
                            <span class="mt-1 block font-bold text-slate-900">{{ $visaProduct->processing_time ?: 'Contact support' }}</span>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">Required documents</span>
                            <span class="mt-1 block font-bold text-slate-900">{{ $visaProduct->documents->count() }}</span>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">Starting price</span>
                            <span class="mt-1 block font-bold text-slate-900">IDR {{ number_format((float) $visaProduct->discount_price ?: (float) $visaProduct->base_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-layouts.app>
