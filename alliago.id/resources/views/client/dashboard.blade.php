<x-layouts.app title="Client Area | Alliago.id">
    <x-home.header />

    <div class="min-h-screen bg-slate-50 text-slate-900 pt-24 pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#0361fc]">Client Area</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl text-slate-900">Manage orders, profile, and documents</h1>
                    <p class="mt-3 max-w-3xl text-lg text-slate-600">Welcome back, {{ $client->name }}. This client area is your control center for tracking visa orders, keeping personal information ready, and responding to admin requests.</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('visa.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#0361fc] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20">Browse visas</a>
                    <form method="POST" action="{{ route('client.logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-50 transition-transform duration-500 group-hover:scale-150"></div>
                    <div class="relative">
                        <p class="text-sm font-bold text-slate-500">Active orders</p>
                        <p class="mt-2 text-4xl font-extrabold text-slate-900">{{ $orderStats['activeOrders'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">This area will list every visa order you own.</p>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-50 transition-transform duration-500 group-hover:scale-150"></div>
                    <div class="relative">
                        <p class="text-sm font-bold text-slate-500">Documents need attention</p>
                        <p class="mt-2 text-4xl font-extrabold text-slate-900">{{ $orderStats['documentsNeedAttention'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">Count of admin requests for revisions or missing files.</p>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-rose-50 transition-transform duration-500 group-hover:scale-150"></div>
                    <div class="relative">
                        <p class="text-sm font-bold text-slate-500">Unread admin messages</p>
                        <p class="mt-2 text-4xl font-extrabold text-slate-900">{{ $orderStats['unreadAdminMessages'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">Communication threads with admin document reviews.</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-8 xl:grid-cols-[1.35fr_0.95fr]">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">My orders</h2>
                            <p class="mt-1 text-sm text-slate-500">Orders will appear here with progress, required actions, and direct communication.</p>
                        </div>
                        <a href="{{ route('visa.index') }}" class="text-sm font-bold text-[#0361fc] hover:text-blue-700 whitespace-nowrap">Browse visas &rarr;</a>
                    </div>

                    <div class="mt-8 grid gap-6">
                        @forelse ($applications as $application)
                            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-black/5">
                                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <p class="text-xs font-bold tracking-wider text-slate-500">{{ $application->reference_number }}</p>
                                        <h3 class="mt-1 text-xl font-bold text-slate-900">{{ $application->visaProduct->name }}</h3>
                                        <p class="mt-1 text-sm text-slate-600">{{ $application->visaProduct->country->name }} · Traveler: <span class="font-medium text-slate-900">{{ $application->traveler_name }}</span></p>
                                        <div class="mt-3">
                                            <a href="{{ route('client.applications.show', $application) }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#0361fc] hover:text-blue-700">Open order details <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></a>
                                        </div>
                                    </div>
                                    <span class="inline-flex shrink-0 items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-700 ring-1 ring-inset ring-slate-500/10">{{ str_replace('_', ' ', $application->status) }}</span>
                                </div>

                                <div class="mt-6 grid gap-3 sm:grid-cols-3 text-sm">
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-slate-600">
                                        <span class="font-bold text-slate-900">{{ $application->documents->whereIn('status', ['pending_upload', 'needs_revision'])->count() }}</span> pending docs
                                    </div>
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-slate-600">
                                        <span class="font-bold text-slate-900">{{ $application->messages->where('is_admin', true)->count() }}</span> admin messages
                                    </div>
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-slate-600">
                                        Updated <span class="font-bold text-slate-900">{{ $application->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                                    <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                                        <div class="flex items-center justify-between gap-4">
                                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Document checklist</h4>
                                            <span class="text-[10px] uppercase font-bold text-slate-400">PDF/JPG/PNG max 5MB</span>
                                        </div>

                                        <div class="mt-4 space-y-3">
                                            @foreach ($application->documents as $document)
                                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                                        <div>
                                                            <p class="font-bold text-slate-900">{{ $document->label }}</p>
                                                            <p class="mt-0.5 text-[10px] font-bold uppercase tracking-wider text-[#0361fc]">{{ str_replace('_', ' ', $document->status) }}</p>
                                                            @if ($document->admin_feedback)
                                                                <p class="mt-2 text-xs font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">Admin note: {{ $document->admin_feedback }}</p>
                                                            @endif
                                                        </div>

                                                        <form method="POST" action="{{ route('client.documents.store', [$application, $document]) }}" enctype="multipart/form-data" class="flex flex-col gap-2 md:items-end">
                                                            @csrf
                                                            <input type="file" name="document" class="block w-full max-w-[200px] text-xs text-slate-500 file:mr-2 file:rounded-full file:border-0 file:bg-slate-200 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-slate-700 hover:file:bg-slate-300">
                                                            <button type="submit" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:border-slate-300">
                                                                {{ $document->file_path ? 'Replace' : 'Upload' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Status timeline</h4>
                                        <div class="mt-4 relative space-y-4 before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                                            @forelse ($application->statusLogs->take(4) as $statusLog)
                                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                                    <div class="flex items-center justify-center w-5 h-5 rounded-full border-2 border-white bg-slate-200 text-slate-500 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2"></div>
                                                    <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] rounded-2xl border border-slate-100 bg-slate-50 p-3 shadow-sm">
                                                        <p class="text-xs font-bold text-slate-900">{{ str_replace('_', ' ', $statusLog->to_status) }}</p>
                                                        @if ($statusLog->message)
                                                            <p class="mt-1 text-xs text-slate-600">{{ $statusLog->message }}</p>
                                                        @endif
                                                        <p class="mt-1 text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ $statusLog->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-center text-sm text-slate-500">
                                                    No status updates yet.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 rounded-3xl border border-slate-100 bg-slate-50 p-5 shadow-sm">
                                    <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Order communication</h4>
                                        <span class="text-xs text-slate-500">Use this thread if admin asks for clarification.</span>
                                    </div>

                                    <div class="mt-4 space-y-3">
                                        @forelse ($application->messages->take(5) as $message)
                                            <div class="rounded-2xl border {{ $message->is_admin ? 'border-amber-100 bg-amber-50' : 'border-slate-200 bg-white' }} p-4 shadow-sm">
                                                <div class="flex items-center justify-between gap-3">
                                                    <p class="text-xs font-bold uppercase tracking-wider {{ $message->is_admin ? 'text-amber-600' : 'text-slate-900' }}">
                                                        {{ $message->is_admin ? 'Admin' : 'You' }}
                                                    </p>
                                                    <p class="text-xs text-slate-400">{{ $message->created_at->diffForHumans() }}</p>
                                                </div>
                                                <p class="mt-2 text-sm text-slate-700">{{ $message->message }}</p>
                                            </div>
                                        @empty
                                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-4 text-center text-sm text-slate-500">
                                                No messages yet. If admin needs anything about this order, the conversation will appear here.
                                            </div>
                                        @endforelse
                                    </div>

                                    <form method="POST" action="{{ route('client.messages.store', $application) }}" class="mt-4 space-y-3">
                                        @csrf
                                        <textarea name="message" rows="3" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none" placeholder="Send a message to admin about this order..."></textarea>
                                        <div class="flex justify-end">
                                            <button type="submit" class="rounded-full bg-[#0361fc] px-4 py-2 text-sm font-bold text-white hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20 transition">
                                                Send message
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <p class="mt-4 text-lg font-bold text-slate-900">No orders yet</p>
                                <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">Create your first order from a visa product page. After that, this section becomes the workspace for status tracking, document follow-up, and admin updates.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-10">
                        <h3 class="text-lg font-bold text-slate-900">What this area is for</h3>
                        <div class="mt-4 grid gap-3">
                            @foreach ($orderGuidance as $guidance)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 shadow-sm">
                                    {{ $guidance }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <div class="grid gap-8">
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-slate-900">Personal information</h2>
                        <p class="mt-1 text-sm text-slate-500">Keep the core profile complete so admin can process travel documents faster.</p>

                        <div class="mt-6 space-y-4">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Account name</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $client->name }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Email address</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $client->email }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Phone number</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $client->phone ?: 'Not provided yet' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            @foreach ($profileCompletionItems as $label => $isComplete)
                                <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-white px-4 py-3 text-sm shadow-sm ring-1 ring-slate-900/5">
                                    <span class="font-medium text-slate-700">{{ $label }}</span>
                                    @if($isComplete)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-600 ring-1 ring-inset ring-emerald-600/20">Ready</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-600 ring-1 ring-inset ring-amber-600/20">Needs update</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-slate-900">Admin communication</h2>
                        <p class="mt-1 text-sm text-slate-500">Each order now has its own lightweight message thread so document issues can be resolved without leaving the client area.</p>

                        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-600">
                            @if ($orderStats['unreadAdminMessages'] > 0)
                                <span class="font-bold text-rose-600">You have {{ $orderStats['unreadAdminMessages'] }} unread admin message(s) across your active orders.</span>
                            @else
                                No unread admin messages yet. When a document needs attention, the related order thread will become the main response point.
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <section class="mt-12 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Recommended visa products</h2>
                        <p class="mt-1 text-sm text-slate-500">Clients can start a new order from any published visa product below.</p>
                    </div>
                    <div class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-600">
                        {{ $availableCountries->count() }} countries available
                    </div>
                </div>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($recommendedVisaProducts as $visaProduct)
                        <a href="{{ route('visa.show', $visaProduct->slug) }}" class="group flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#0361fc]/30 hover:shadow-xl hover:shadow-[#0361fc]/5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $visaProduct->country->name }}</p>
                                    <h3 class="mt-1 text-xl font-bold text-slate-900 group-hover:text-[#0361fc] transition-colors">{{ $visaProduct->name }}</h3>
                                </div>
                                <span class="inline-flex shrink-0 items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#0361fc] ring-1 ring-inset ring-[#0361fc]/10">{{ $visaProduct->type }}</span>
                            </div>
                            <p class="mt-4 text-sm text-slate-600 line-clamp-2">{{ $visaProduct->short_description ?: 'Managed from the admin catalog and ready to become a client order.' }}</p>
                            <div class="mt-6 flex items-end justify-between text-sm">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Starts from</span>
                                <span class="text-lg font-bold text-slate-900">IDR {{ number_format((float) $visaProduct->discount_price ?: (float) $visaProduct->base_price, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center sm:col-span-2 lg:col-span-3">
                            <p class="text-sm text-slate-500">No visa products have been published yet. Add countries and visa products from `/admin` first.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-layouts.app>
