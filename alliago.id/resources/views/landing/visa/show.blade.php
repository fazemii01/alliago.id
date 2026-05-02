<x-layouts.app :title="$visaProduct->name . ' | Alliago.id'">
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="mx-auto max-w-7xl px-6 py-10">
            <a href="{{ route('visa.index') }}" class="text-sm text-amber-300">Back to visa catalog</a>

            <div class="mt-6 grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">
                <div>
                    <div class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">{{ $visaProduct->country->name }}</p>
                                <h1 class="mt-3 text-4xl font-semibold">{{ $visaProduct->name }}</h1>
                                <p class="mt-4 max-w-3xl text-slate-300">{{ $visaProduct->short_description ?: 'This visa product is managed in the admin catalog and ready for client orders.' }}</p>
                            </div>
                            <span class="rounded-full border border-white/10 px-4 py-2 text-sm uppercase tracking-wide text-slate-300">{{ $visaProduct->type }}</span>
                        </div>

                        <div class="mt-8 grid gap-4 md:grid-cols-3">
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-sm text-slate-400">Processing time</p>
                                <p class="mt-2 text-lg font-semibold">{{ $visaProduct->processing_time ?: 'Contact support' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-sm text-slate-400">Stay duration</p>
                                <p class="mt-2 text-lg font-semibold">{{ $visaProduct->stay_duration ?: 'Varies by product' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-sm text-slate-400">Validity</p>
                                <p class="mt-2 text-lg font-semibold">{{ $visaProduct->validity ?: 'See product terms' }}</p>
                            </div>
                        </div>

                        @if ($visaProduct->description)
                            <div class="prose prose-invert mt-8 max-w-none">
                                {!! $visaProduct->description !!}
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 grid gap-8">
                        <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                            <h2 class="text-2xl font-semibold">Requirements</h2>
                            <div class="mt-5 grid gap-4">
                                @forelse ($visaProduct->requirements as $requirement)
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                                        <h3 class="text-lg font-medium">{{ $requirement->title }}</h3>
                                        @if ($requirement->description)
                                            <p class="mt-2 text-sm text-slate-300">{{ $requirement->description }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-400">Requirements will appear here once configured in admin.</p>
                                @endforelse
                            </div>
                        </section>

                        <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                            <h2 class="text-2xl font-semibold">Required Documents</h2>
                            <div class="mt-5 grid gap-4">
                                @forelse ($visaProduct->documents as $document)
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                                        <div class="flex items-start justify-between gap-4">
                                            <h3 class="text-lg font-medium">{{ $document->name }}</h3>
                                            @if ($document->is_required)
                                                <span class="rounded-full bg-amber-400/15 px-3 py-1 text-xs font-medium text-amber-300">Required</span>
                                            @endif
                                        </div>
                                        @if ($document->description)
                                            <p class="mt-2 text-sm text-slate-300">{{ $document->description }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-400">Document requirements will appear here once configured in admin.</p>
                                @endforelse
                            </div>
                        </section>

                        <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                            <h2 class="text-2xl font-semibold">Process Steps</h2>
                            <div class="mt-5 grid gap-4">
                                @forelse ($visaProduct->processSteps as $step)
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                                        <h3 class="text-lg font-medium">{{ $step->title }}</h3>
                                        @if ($step->description)
                                            <p class="mt-2 text-sm text-slate-300">{{ $step->description }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-400">Process steps will appear here once configured in admin.</p>
                                @endforelse
                            </div>
                        </section>

                        <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                            <h2 class="text-2xl font-semibold">Frequently Asked Questions</h2>
                            <div class="mt-5 grid gap-4">
                                @forelse ($visaProduct->faqs as $faq)
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                                        <h3 class="text-lg font-medium">{{ $faq->question }}</h3>
                                        <p class="mt-2 text-sm text-slate-300">{{ $faq->answer }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-400">FAQs will appear here once configured in admin.</p>
                                @endforelse
                            </div>
                        </section>
                    </div>
                </div>

                <aside>
                    <div class="sticky top-8 rounded-[2rem] border border-white/10 bg-white/5 p-8">
                        <p class="text-sm text-slate-400">Starting price</p>
                        @if ($visaProduct->discount_price)
                            <p class="mt-3 text-sm text-slate-500 line-through">IDR {{ number_format((float) $visaProduct->base_price, 0, ',', '.') }}</p>
                        @endif
                        <p class="mt-1 text-4xl font-semibold text-white">IDR {{ number_format((float) $visaProduct->discount_price ?: (float) $visaProduct->base_price, 0, ',', '.') }}</p>

                        @if ($visaProduct->promo_label)
                            <div class="mt-5 inline-flex rounded-full bg-amber-400/15 px-3 py-1 text-sm font-medium text-amber-300">
                                {{ $visaProduct->promo_label }}
                            </div>
                        @endif

                        <div class="mt-8 space-y-3">
                            <a href="{{ auth()->check() ? route('client.dashboard') : route('client.login') }}" class="block rounded-2xl bg-amber-400 px-4 py-3 text-center font-semibold text-slate-950 transition hover:bg-amber-300">
                                {{ auth()->check() ? 'Open client area' : 'Login to create an order' }}
                            </a>
                            <a href="{{ route('client.register') }}" class="block rounded-2xl border border-white/10 px-4 py-3 text-center font-medium text-slate-200 hover:bg-white/5">
                                Create client account
                            </a>
                        </div>

                        <div class="mt-8 border-t border-white/10 pt-6">
                            <h2 class="text-lg font-semibold">Available Add-ons</h2>
                            <div class="mt-4 space-y-3">
                                @forelse ($visaProduct->addons as $addon)
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <h3 class="font-medium">{{ $addon->name }}</h3>
                                                @if ($addon->description)
                                                    <p class="mt-1 text-sm text-slate-300">{{ $addon->description }}</p>
                                                @endif
                                            </div>
                                            <span class="text-sm font-semibold text-white">IDR {{ number_format((float) $addon->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-400">No add-ons configured yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-layouts.app>
