<x-layouts.app title="Client Area | Alliago.id">
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="mx-auto max-w-7xl px-6 py-10">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Client Area</p>
                    <h1 class="mt-2 text-4xl font-semibold">Manage orders, profile, and document communication</h1>
                    <p class="mt-3 max-w-3xl text-slate-300">Welcome back, {{ $client->name }}. This client area is now positioned as your control center for tracking visa orders, keeping personal information ready, and responding when admin needs attention on your documents.</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('visa.index') }}" class="rounded-2xl bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 hover:bg-amber-300">Browse visa products</a>
                    <form method="POST" action="{{ route('client.logout') }}">
                        @csrf
                        <button type="submit" class="rounded-2xl border border-white/10 px-5 py-3 text-sm font-medium text-slate-200 hover:bg-white/5">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-300">Active orders</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $orderStats['activeOrders'] }}</p>
                    <p class="mt-2 text-sm text-slate-400">This area will list every visa order the client owns.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-300">Documents need attention</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $orderStats['documentsNeedAttention'] }}</p>
                    <p class="mt-2 text-sm text-slate-400">Use this count to quickly see when admin requests a revision or missing file.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-300">Unread admin messages</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $orderStats['unreadAdminMessages'] }}</p>
                    <p class="mt-2 text-sm text-slate-400">Communication threads will live here when document review and notes are added.</p>
                </div>
            </div>

            <div class="mt-12 grid gap-8 xl:grid-cols-[1.35fr_0.95fr]">
                <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold">My orders</h2>
                            <p class="mt-2 text-sm text-slate-300">Orders will appear here with progress, required actions, and direct communication to admin.</p>
                        </div>
                        <a href="{{ route('visa.index') }}" class="text-sm font-medium text-amber-300">Browse visa products</a>
                    </div>

                    <div class="mt-6 grid gap-4">
                        @forelse ($applications as $application)
                            <div class="rounded-3xl border border-white/10 bg-slate-900/40 p-6">
                                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <p class="text-sm text-amber-300">{{ $application->reference_number }}</p>
                                        <h3 class="mt-2 text-xl font-semibold">{{ $application->visaProduct->name }}</h3>
                                        <p class="mt-2 text-sm text-slate-300">{{ $application->visaProduct->country->name }} · Traveler: {{ $application->traveler_name }}</p>
                                        <div class="mt-4">
                                            <a href="{{ route('client.applications.show', $application) }}" class="text-sm font-medium text-amber-300 hover:text-amber-200">Open order details</a>
                                        </div>
                                    </div>
                                    <span class="rounded-full border border-white/10 px-3 py-1 text-xs uppercase tracking-wide text-slate-300">{{ str_replace('_', ' ', $application->status) }}</span>
                                </div>

                                <div class="mt-5 grid gap-3 md:grid-cols-3 text-sm">
                                    <div class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300">
                                        Documents pending: {{ $application->documents->whereIn('status', ['pending_upload', 'needs_revision'])->count() }}
                                    </div>
                                    <div class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300">
                                        Admin messages: {{ $application->messages->where('is_admin', true)->count() }}
                                    </div>
                                    <div class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300">
                                        Last update: {{ $application->updated_at->diffForHumans() }}
                                    </div>
                                </div>

                                <div class="mt-5 grid gap-4 xl:grid-cols-[1.1fr_0.9fr]">
                                    <div class="rounded-2xl border border-white/10 bg-slate-950/40 p-5">
                                        <div class="flex items-center justify-between gap-4">
                                            <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-300">Document checklist</h4>
                                            <span class="text-xs text-slate-500">Upload PDF/JPG/PNG up to 5MB</span>
                                        </div>

                                        <div class="mt-4 space-y-4">
                                            @foreach ($application->documents as $document)
                                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                                        <div>
                                                            <p class="font-medium text-white">{{ $document->label }}</p>
                                                            <p class="mt-1 text-xs uppercase tracking-wide text-amber-300">{{ str_replace('_', ' ', $document->status) }}</p>
                                                            @if ($document->admin_feedback)
                                                                <p class="mt-2 text-sm text-rose-300">Admin note: {{ $document->admin_feedback }}</p>
                                                            @endif
                                                        </div>

                                                        <form method="POST" action="{{ route('client.documents.store', [$application, $document]) }}" enctype="multipart/form-data" class="flex flex-col gap-2 md:items-end">
                                                            @csrf
                                                            <input type="file" name="document" class="block max-w-full text-xs text-slate-300 file:mr-3 file:rounded-xl file:border-0 file:bg-amber-400 file:px-3 file:py-2 file:font-medium file:text-slate-950 hover:file:bg-amber-300">
                                                            <button type="submit" class="rounded-xl border border-white/10 px-4 py-2 text-xs font-medium text-slate-200 hover:bg-white/5">
                                                                {{ $document->file_path ? 'Replace file' : 'Upload file' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-white/10 bg-slate-950/40 p-5">
                                        <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-300">Recent status timeline</h4>
                                        <div class="mt-4 space-y-3">
                                            @forelse ($application->statusLogs->take(4) as $statusLog)
                                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                                    <p class="text-sm font-medium text-white">{{ str_replace('_', ' ', $statusLog->to_status) }}</p>
                                                    @if ($statusLog->message)
                                                        <p class="mt-1 text-sm text-slate-300">{{ $statusLog->message }}</p>
                                                    @endif
                                                    <p class="mt-2 text-xs text-slate-500">{{ $statusLog->created_at->diffForHumans() }}</p>
                                                </div>
                                            @empty
                                                <div class="rounded-2xl border border-dashed border-white/15 bg-slate-900/30 p-4 text-sm text-slate-300">
                                                    No status updates yet beyond order creation.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 rounded-2xl border border-white/10 bg-slate-950/40 p-5">
                                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                        <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-300">Order communication</h4>
                                        <span class="text-xs text-slate-500">Use this thread if admin asks for clarification.</span>
                                    </div>

                                    <div class="mt-4 space-y-3">
                                        @forelse ($application->messages->take(5) as $message)
                                            <div class="rounded-2xl border border-white/10 {{ $message->is_admin ? 'bg-amber-400/10 border-amber-300/20' : 'bg-slate-900/40' }} p-4">
                                                <div class="flex items-center justify-between gap-3">
                                                    <p class="text-sm font-medium {{ $message->is_admin ? 'text-amber-200' : 'text-white' }}">
                                                        {{ $message->is_admin ? 'Admin' : 'You' }}
                                                    </p>
                                                    <p class="text-xs text-slate-500">{{ $message->created_at->diffForHumans() }}</p>
                                                </div>
                                                <p class="mt-2 text-sm text-slate-300">{{ $message->message }}</p>
                                            </div>
                                        @empty
                                            <div class="rounded-2xl border border-dashed border-white/15 bg-slate-900/30 p-4 text-sm text-slate-300">
                                                No messages yet. If admin needs anything about this order, the conversation will appear here.
                                            </div>
                                        @endforelse
                                    </div>

                                    <form method="POST" action="{{ route('client.messages.store', $application) }}" class="mt-4 space-y-3">
                                        @csrf
                                        <textarea name="message" rows="3" class="w-full rounded-2xl border border-white/10 bg-slate-900/40 px-4 py-3 text-sm text-white placeholder:text-slate-500 focus:border-amber-300 focus:outline-none" placeholder="Send a message to admin about this order..."></textarea>
                                        <div class="flex justify-end">
                                            <button type="submit" class="rounded-xl bg-amber-400 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-amber-300">
                                                Send message
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-white/15 bg-slate-900/30 p-6">
                                <p class="text-lg font-medium">No orders yet</p>
                                <p class="mt-2 text-sm text-slate-300">Create your first order from a visa product page. After that, this section becomes the workspace for status tracking, document follow-up, and admin updates.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold">What this area is for</h3>
                        <div class="mt-4 grid gap-3">
                            @foreach ($orderGuidance as $guidance)
                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 px-4 py-3 text-sm text-slate-300">
                                    {{ $guidance }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <div class="grid gap-8">
                    <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                        <h2 class="text-2xl font-semibold">Personal information</h2>
                        <p class="mt-2 text-sm text-slate-300">Keep the core profile complete so admin can process travel documents faster.</p>

                        <div class="mt-6 space-y-4">
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                                <p class="text-sm text-slate-400">Account name</p>
                                <p class="mt-2 text-lg font-semibold">{{ $client->name }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                                <p class="text-sm text-slate-400">Email address</p>
                                <p class="mt-2 text-lg font-semibold">{{ $client->email }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                                <p class="text-sm text-slate-400">Phone number</p>
                                <p class="mt-2 text-lg font-semibold">{{ $client->phone ?: 'Not provided yet' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            @foreach ($profileCompletionItems as $label => $isComplete)
                                <div class="flex items-center justify-between rounded-2xl border border-white/10 px-4 py-3 text-sm">
                                    <span class="text-slate-300">{{ $label }}</span>
                                    <span class="{{ $isComplete ? 'text-emerald-300' : 'text-amber-300' }}">{{ $isComplete ? 'Ready' : 'Needs update' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                        <h2 class="text-2xl font-semibold">Admin communication</h2>
                        <p class="mt-2 text-sm text-slate-300">Each order now has its own lightweight message thread so document issues can be resolved without leaving the client area.</p>

                        <div class="mt-6 rounded-3xl border border-dashed border-white/15 bg-slate-900/30 p-6 text-sm text-slate-300">
                            @if ($orderStats['unreadAdminMessages'] > 0)
                                You have {{ $orderStats['unreadAdminMessages'] }} unread admin message(s) across your active orders.
                            @else
                                No unread admin messages yet. When a document needs attention, the related order thread will become the main response point.
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <section class="mt-12 rounded-[2rem] border border-white/10 bg-white/5 p-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold">Recommended visa products</h2>
                        <p class="mt-2 text-sm text-slate-300">Clients can start a new order from any published visa product below.</p>
                    </div>
                    <div class="text-sm text-slate-400">
                        {{ $availableCountries->count() }} countries currently available
                    </div>
                </div>

                <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($recommendedVisaProducts as $visaProduct)
                        <a href="{{ route('visa.show', $visaProduct->slug) }}" class="rounded-3xl border border-white/10 bg-slate-900/40 p-6 transition hover:border-amber-300/40 hover:bg-white/10">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm text-amber-300">{{ $visaProduct->country->name }}</p>
                                    <h3 class="mt-2 text-xl font-semibold">{{ $visaProduct->name }}</h3>
                                </div>
                                <span class="rounded-full border border-white/10 px-3 py-1 text-xs uppercase tracking-wide text-slate-300">{{ $visaProduct->type }}</span>
                            </div>
                            <p class="mt-4 text-sm text-slate-300">{{ $visaProduct->short_description ?: 'Managed from the admin catalog and ready to become a client order.' }}</p>
                            <div class="mt-5 flex items-center justify-between text-sm">
                                <span class="text-slate-400">Starts from</span>
                                <span class="font-semibold text-white">IDR {{ number_format((float) $visaProduct->discount_price ?: (float) $visaProduct->base_price, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-3xl border border-dashed border-white/15 bg-slate-900/30 p-8 text-sm text-slate-300 md:col-span-2 xl:col-span-3">
                            No visa products have been published yet. Add countries and visa products from `/admin` first.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-layouts.app>
