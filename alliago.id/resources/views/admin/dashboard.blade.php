<x-layouts.app title="Admin Overview | Alliago.id">
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="mx-auto max-w-7xl px-6 py-10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Admin Overview</p>
                    <h1 class="mt-2 text-4xl font-semibold">Catalog and client activity at a glance</h1>
                    <p class="mt-3 max-w-3xl text-slate-300">This lightweight admin view now lives in the `admin` folder and complements the full Filament dashboard at <span class="font-medium text-white">/admin</span>.</p>
                </div>

                <div class="flex gap-3">
                    <a href="/admin" class="rounded-2xl bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 hover:bg-amber-300">Open Filament Admin</a>
                    <a href="{{ route('client.dashboard') }}" class="rounded-2xl border border-white/10 px-5 py-3 text-sm font-medium text-slate-200 hover:bg-white/5">View client area</a>
                </div>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-300">Countries</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['countries'] }}</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-300">Visa products</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['visaProducts'] }}</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-300">Active products</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['activeVisaProducts'] }}</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-300">Client accounts</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['clients'] }}</p>
                </div>
            </div>

            <div class="mt-12 rounded-[2rem] border border-white/10 bg-white/5 p-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold">Recently updated visa products</h2>
                        <p class="mt-2 text-sm text-slate-300">Useful for admins reviewing what clients can currently order from the landing pages.</p>
                    </div>
                    <a href="/admin/visa-products" class="text-sm font-medium text-amber-300">Manage catalog</a>
                </div>

                <div class="mt-6 grid gap-4">
                    @forelse ($latestVisaProducts as $visaProduct)
                        <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-sm text-amber-300">{{ $visaProduct->country->name }}</p>
                                    <h3 class="mt-1 text-lg font-semibold">{{ $visaProduct->name }}</h3>
                                    <p class="mt-2 text-sm text-slate-300">{{ $visaProduct->short_description ?: 'Ready for client-facing orders and document coordination.' }}</p>
                                </div>
                                <div class="text-sm text-slate-400">
                                    Updated {{ $visaProduct->updated_at?->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-white/15 bg-slate-900/30 p-6 text-sm text-slate-300">
                            No visa products yet. Start by creating countries and products in the Filament admin panel.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
