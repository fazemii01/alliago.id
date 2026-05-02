<x-layouts.app title="{{ $application->reference_number }} | Client Area">
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="mx-auto max-w-6xl px-6 py-10">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Order detail</p>
                    <h1 class="mt-2 text-4xl font-semibold">{{ $application->reference_number }}</h1>
                    <p class="mt-3 max-w-3xl text-slate-300">{{ $application->visaProduct->name }} for {{ $application->visaProduct->country->name }}. Use this page to track status, upload files, and keep the admin conversation focused on this order only.</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('client.dashboard') }}" class="rounded-2xl border border-white/10 px-5 py-3 text-sm font-medium text-slate-200 hover:bg-white/5">Back to dashboard</a>
                    <span class="rounded-2xl bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950">{{ str_replace('_', ' ', $application->status) }}</span>
                </div>
            </div>

            <div class="mt-10 grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
                <section class="space-y-6 rounded-[2rem] border border-white/10 bg-white/5 p-8">
                    <div>
                        <h2 class="text-2xl font-semibold">Document checklist</h2>
                        <p class="mt-2 text-sm text-slate-300">Upload or replace the requested files here. If admin asks for a revision, their feedback will appear directly under the related document.</p>
                    </div>

                    <div class="space-y-4">
                        @foreach ($application->documents as $document)
                            <div class="rounded-3xl border border-white/10 bg-slate-900/40 p-5">
                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-lg font-semibold">{{ $document->label }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-wide text-amber-300">{{ str_replace('_', ' ', $document->status) }}</p>
                                        @if ($document->reviewed_at)
                                            <p class="mt-2 text-xs text-slate-500">Reviewed {{ $document->reviewed_at->diffForHumans() }}</p>
                                        @endif
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
                </section>

                <div class="space-y-8">
                    <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                        <h2 class="text-2xl font-semibold">Traveler summary</h2>
                        <div class="mt-6 space-y-4 text-sm text-slate-300">
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-slate-400">Traveler name</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $application->traveler_name }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-slate-400">Traveler email</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $application->traveler_email }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-slate-400">Traveler phone</p>
                                <p class="mt-2 text-lg font-semibold text-white">{{ $application->traveler_phone ?: 'Not provided yet' }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                        <h2 class="text-2xl font-semibold">Status timeline</h2>
                        <div class="mt-6 space-y-3">
                            @foreach ($application->statusLogs as $statusLog)
                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                    <p class="text-sm font-medium text-white">{{ str_replace('_', ' ', $statusLog->to_status) }}</p>
                                    @if ($statusLog->message)
                                        <p class="mt-1 text-sm text-slate-300">{{ $statusLog->message }}</p>
                                    @endif
                                    <p class="mt-2 text-xs text-slate-500">{{ $statusLog->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <h2 class="text-2xl font-semibold">Order communication</h2>
                            <span class="text-xs text-slate-500">Keep replies specific to this order.</span>
                        </div>

                        <div class="mt-6 space-y-3">
                            @forelse ($application->messages as $message)
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
                                    No messages yet for this order.
                                </div>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('client.messages.store', $application) }}" class="mt-4 space-y-3">
                            @csrf
                            <textarea name="message" rows="3" class="w-full rounded-2xl border border-white/10 bg-slate-900/40 px-4 py-3 text-sm text-white placeholder:text-slate-500 focus:border-amber-300 focus:outline-none" placeholder="Reply to admin about this order..."></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-amber-400 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-amber-300">
                                    Send message
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
