<x-layouts.app title="{{ __('client.dashboard_client_area') }} | Alliago.id">
    <x-home.header />

    <div class="min-h-screen bg-slate-50 text-slate-900 pt-24 pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#0361fc]">{{ __('client.dashboard_client_area') }}</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl text-slate-900">{{ __('client.dashboard_title') }}</h1>
                    <p class="mt-3 max-w-3xl text-lg text-slate-600">{{ __('client.dashboard_welcome') }} {{ $client->name }}. {{ __('client.dashboard_welcome_desc') }}</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('visa.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#0361fc] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20">{{ __('common.btn_explore_visa') }}</a>
                    <form method="POST" action="{{ route('client.logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                            {{ __('common.nav_logout') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-50 transition-transform duration-500 group-hover:scale-150"></div>
                    <div class="relative">
                        <p class="text-sm font-bold text-slate-500">{{ __('client.dashboard_stat_active') }}</p>
                        <p class="mt-2 text-4xl font-extrabold text-slate-900">{{ $orderStats['activeOrders'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">{{ __('client.dashboard_stat_active_desc') }}</p>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-50 transition-transform duration-500 group-hover:scale-150"></div>
                    <div class="relative">
                        <p class="text-sm font-bold text-slate-500">{{ __('client.dashboard_stat_docs') }}</p>
                        <p class="mt-2 text-4xl font-extrabold text-slate-900">{{ $orderStats['documentsNeedAttention'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">{{ __('client.dashboard_stat_docs_desc') }}</p>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-rose-50 transition-transform duration-500 group-hover:scale-150"></div>
                    <div class="relative">
                        <p class="text-sm font-bold text-slate-500">{{ __('client.dashboard_stat_messages') }}</p>
                        <p class="mt-2 text-4xl font-extrabold text-slate-900">{{ $orderStats['unreadAdminMessages'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">{{ __('client.dashboard_stat_messages_desc') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-8 xl:grid-cols-[1.35fr_0.95fr]">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">{{ __('client.dashboard_my_applications') }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ __('client.dashboard_my_apps_subtitle') }}</p>
                        </div>
                        <a href="{{ route('visa.index') }}" class="text-sm font-bold text-[#0361fc] hover:text-blue-700 whitespace-nowrap">{{ __('client.dashboard_view_services') }}</a>
                    </div>

                    <div class="mt-8 grid gap-6">
                        @forelse ($applications as $application)
                            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-black/5 transition hover:shadow-md">
                                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <p class="text-xs font-bold tracking-wider text-slate-500">ID: {{ $application->reference_number }}</p>
                                        @if ($application->visaProduct)
                                            <h3 class="mt-1 text-xl font-bold text-slate-900">{{ $application->visaProduct->name }}</h3>
                                            <p class="mt-1 text-sm text-slate-600">{{ $application->visaProduct->country->name }} · {{ __('client.dashboard_applicant_detail') }} <span class="font-medium text-slate-900">{{ $application->traveler_name }}</span></p>
                                        @else
                                            <h3 class="mt-1 text-xl font-bold text-slate-900">{{ __('Pemesanan Tiket Pesawat') }}</h3>
                                            <p class="mt-1 text-sm text-slate-600">
                                                {{ $application->metadata['flight_details']['airline_name'] ?? 'Penerbangan' }} ({{ $application->metadata['flight_details']['flight_numbers'] ?? '' }})
                                                · {{ __('client.dashboard_applicant_detail') }} <span class="font-medium text-slate-900">{{ $application->traveler_name }}</span>
                                            </p>
                                        @endif
                                        <div class="mt-4 flex gap-3">
                                            <a href="{{ route('client.applications.show', $application) }}" class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-200 transition">
                                                {{ __('client.dashboard_detail_btn') }}
                                            </a>
                                            <a href="{{ route('client.applications.invoice', $application) }}" class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                                                {{ __('client.dashboard_invoice_btn') }}
                                            </a>
                                        </div>
                                    </div>
                                    <span class="inline-flex shrink-0 items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-700 ring-1 ring-inset ring-slate-500/10">
                                        {{ str_replace('_', ' ', $application->status) }}
                                    </span>
                                </div>

                                <div class="mt-6 grid gap-3 sm:grid-cols-3 text-sm">
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-slate-600">
                                        <span class="font-bold text-slate-900">{{ $application->documents->whereIn('status', ['pending_upload', 'needs_revision'])->count() }}</span> {{ __('client.dashboard_docs_pending') }}
                                    </div>
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-slate-600">
                                        <span class="font-bold text-slate-900">{{ $application->messages->where('is_admin', true)->count() }}</span> {{ __('client.dashboard_admin_messages') }}
                                    </div>
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-slate-600">
                                        {{ __('client.dashboard_last_updated') }} <span class="font-bold text-slate-900">{{ $application->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-6 xl:grid-cols-[1.1fr_0.9fr] grid-cols-1">
                                    @if ($application->visa_product_id)
                                        <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                                            <div class="flex items-center justify-between gap-4">
                                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('client.dashboard_checklist_title') }}</h4>
                                                <span class="text-[10px] uppercase font-bold text-slate-400">{{ __('client.dashboard_checklist_format') }}</span>
                                            </div>

                                            <div class="mt-4 space-y-3">
                                                @if (in_array($application->status, ['pending_payment', 'payment_failed']))
                                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-center text-sm text-slate-500">
                                                        {{ __('client.dashboard_pay_to_upload') }}
                                                        <div class="mt-2">
                                                            <a href="{{ route('client.applications.checkout', $application) }}" class="inline-flex items-center justify-center rounded-full bg-[#0361fc] px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-blue-700 transition">{{ __('common.btn_pay_now') }}</a>
                                                        </div>
                                                    </div>
                                                @else
                                                    @foreach ($application->documents as $document)
                                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                                                <div>
                                                                    <p class="font-bold text-slate-900">{{ $document->label }}</p>
                                                                    <p class="mt-0.5 text-[10px] font-bold uppercase tracking-wider text-[#0361fc]">
                                                                        {{ str_replace('_', ' ', $document->status) }}
                                                                    </p>
                                                                    @if ($document->admin_feedback)
                                                                        <p class="mt-2 text-xs font-medium text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">{{ __('client.dashboard_admin_note') }} {{ $document->admin_feedback }}</p>
                                                                    @endif
                                                                </div>

                                                                <form method="POST" action="{{ route('client.documents.store', [$application, $document]) }}" enctype="multipart/form-data" class="flex flex-col gap-2 md:items-end">
                                                                    @csrf
                                                                    <input type="file" name="document" class="block w-full max-w-[200px] text-xs text-slate-500 file:mr-2 file:rounded-full file:border-0 file:bg-slate-200 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-slate-700 hover:file:bg-slate-300">
                                                                    <button type="submit" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:border-slate-300">
                                                                        {{ $document->file_path ? __('client.dashboard_change') : __('client.dashboard_upload') }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <!-- Flight Details & Payment Status Card for Flights -->
                                        <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                                            <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
                                                <div>
                                                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Detail Pembayaran & Penerbangan</h4>
                                                    <p class="text-base font-bold text-slate-900 mt-1">Status Pembayaran</p>
                                                </div>
                                                @php
                                                    $paymentStatus = $application->metadata['payment_status'] ?? 'unpaid';
                                                    $amount = $application->metadata['invoice_amount'] ?? $application->metadata['price_breakdown']['total'] ?? 0;
                                                @endphp
                                                @if ($paymentStatus === 'paid')
                                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-700 ring-1 ring-inset ring-emerald-600/10">
                                                        Lunas / Paid
                                                    </span>
                                                @elseif ($paymentStatus === 'pending_verification')
                                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-amber-700 ring-1 ring-inset ring-amber-600/10">
                                                        Menunggu Verifikasi
                                                    </span>
                                                @elseif ($paymentStatus === 'declined')
                                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-rose-700 ring-1 ring-inset ring-rose-600/10">
                                                        Ditolak / Gagal
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-700 ring-1 ring-inset ring-slate-500/10">
                                                        Belum Bayar
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="mt-5 space-y-4">
                                                <!-- Amount -->
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-100 gap-4">
                                                    <div>
                                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Tagihan</p>
                                                        <p class="text-xl font-extrabold text-slate-900 mt-1">Rp {{ number_format($amount, 0, ',', '.') }}</p>
                                                    </div>
                                                    @if ($paymentStatus !== 'paid' && $paymentStatus !== 'pending_verification')
                                                        <a href="{{ route('client.applications.checkout', $application) }}" class="inline-flex items-center justify-center rounded-full bg-[#0361fc] px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition">
                                                            Bayar Sekarang &rarr;
                                                        </a>
                                                    @elseif ($paymentStatus === 'pending_verification')
                                                        <span class="text-xs font-bold text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-full">
                                                            Sedang Diverifikasi
                                                        </span>
                                                    @else
                                                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full">
                                                            Terverifikasi
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Flight Details Table -->
                                                @if (isset($application->metadata['flight_details']))
                                                    <div class="border border-slate-100 rounded-2xl overflow-hidden mt-4">
                                                        <div class="bg-slate-50 px-4 py-3 border-b border-slate-100">
                                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Detail Penerbangan</p>
                                                        </div>
                                                        <div class="p-4 space-y-2 text-sm">
                                                            <div class="flex justify-between"><span class="text-slate-500">Maskapai:</span><span class="font-bold text-slate-900">{{ $application->metadata['flight_details']['airline_name'] ?? '-' }}</span></div>
                                                            <div class="flex justify-between"><span class="text-slate-500">Nomor Penerbangan:</span><span class="font-mono font-bold text-slate-900">{{ $application->metadata['flight_details']['flight_numbers'] ?? '-' }}</span></div>
                                                            <div class="flex justify-between"><span class="text-slate-500">Rute:</span><span class="font-bold text-slate-900">{{ strtoupper($application->metadata['flight_details']['origin'] ?? '') }} &rarr; {{ strtoupper($application->metadata['flight_details']['destination'] ?? '') }}</span></div>
                                                            <div class="flex justify-between"><span class="text-slate-500">Tanggal Pergi:</span><span class="font-bold text-slate-900">{{ $application->metadata['flight_details']['depart_date'] ?? '-' }} {{ $application->metadata['flight_details']['depart_time'] ?? '' }}</span></div>
                                                            @if (isset($application->metadata['flight_details']['return_date']))
                                                                <div class="flex justify-between"><span class="text-slate-500">Tanggal Pulang:</span><span class="font-bold text-slate-900">{{ $application->metadata['flight_details']['return_date'] }} {{ $application->metadata['flight_details']['return_time'] ?? '' }}</span></div>
                                                            @endif
                                                            @if (isset($application->metadata['flight_details']['extra_baggage_weight']) && $application->metadata['flight_details']['extra_baggage_weight'] > 0)
                                                                <div class="flex justify-between"><span class="text-slate-500">Bagasi Tambahan:</span><span class="font-bold text-emerald-600">+{{ $application->metadata['flight_details']['extra_baggage_weight'] }} kg</span></div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('client.dashboard_timeline_title') }}</h4>
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
                                                    {{ __('client.dashboard_no_updates') }}
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 rounded-3xl border border-slate-100 bg-slate-50 p-5 shadow-sm">
                                    <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('client.dashboard_messages_title') }}</h4>
                                        <span class="text-xs text-slate-500">{{ __('client.dashboard_messages_hint') }}</span>
                                    </div>

                                    <div class="mt-4 space-y-3">
                                        @forelse ($application->messages->take(5) as $message)
                                            <div class="rounded-2xl border {{ $message->is_admin ? 'border-amber-100 bg-amber-50' : 'border-slate-200 bg-white' }} p-4 shadow-sm">
                                                <div class="flex items-center justify-between gap-3">
                                                    <p class="text-xs font-bold uppercase tracking-wider {{ $message->is_admin ? 'text-amber-600' : 'text-slate-900' }}">
                                                        {{ $message->is_admin ? __('client.dashboard_admin_sender') : __('client.dashboard_you') }}
                                                    </p>
                                                    <p class="text-xs text-slate-400">{{ $message->created_at->diffForHumans() }}</p>
                                                </div>
                                                <p class="mt-2 text-sm text-slate-700">{{ $message->message }}</p>
                                            </div>
                                        @empty
                                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-4 text-center text-sm text-slate-500">
                                                {{ __('client.dashboard_no_messages') }}
                                            </div>
                                        @endforelse
                                    </div>

                                    <form method="POST" action="{{ route('client.messages.store', $application) }}" class="mt-4 space-y-3">
                                        @csrf
                                        <textarea name="message" rows="3" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#0361fc] focus:ring-1 focus:ring-[#0361fc] focus:outline-none" placeholder="{{ __('client.dashboard_message_placeholder') }}"></textarea>
                                        <div class="flex justify-end">
                                            <button type="submit" class="rounded-full bg-[#0361fc] px-4 py-2 text-sm font-bold text-white hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20 transition">
                                                {{ __('common.btn_send_message') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <p class="mt-4 text-lg font-bold text-slate-900">{{ __('client.dashboard_no_applications') }}</p>
                                <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">{{ __('client.dashboard_no_apps_desc') }}</p>
                                <div class="mt-6">
                                    <a href="{{ route('visa.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#0361fc] px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-700 shadow-sm shadow-[#0361fc]/20">{{ __('client.dashboard_explore_services') }}</a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </section>

                <div class="grid gap-8">
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-slate-900">{{ __('client.dashboard_personal_info') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('client.dashboard_personal_info_subtitle') }}</p>

                        <div class="mt-6 space-y-4">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('client.dashboard_account_name') }}</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $client->name }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('client.dashboard_email_address') }}</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $client->email }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('client.dashboard_phone') }}</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $client->phone ?: __('client.dashboard_phone_empty') }}</p>
                            </div>
                            <div class="mt-2 flex justify-end">
                                <a href="{{ route('client.profile.edit') }}" class="text-sm font-bold text-[#0361fc] hover:text-blue-700">{{ __('client.dashboard_edit_profile') }}</a>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-slate-900">{{ __('client.dashboard_notifications') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('client.dashboard_notifications_subtitle') }}</p>

                        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-600">
                            @if ($orderStats['unreadAdminMessages'] > 0)
                                <span class="font-bold text-rose-600">{{ __('client.dashboard_unread_messages', ['count' => $orderStats['unreadAdminMessages']]) }}</span>
                            @else
                                {{ __('client.dashboard_no_notifications') }}
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <section class="mt-12 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">{{ __('client.dashboard_recommendations') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('client.dashboard_recommendations_subtitle') }}</p>
                    </div>
                    <div class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-600">
                        {{ $availableCountries->count() }} {{ __('client.dashboard_countries_available') }}
                    </div>
                </div>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($recommendedVisaProducts as $visaProduct)
                        <a href="{{ route('visa.show', $visaProduct->slug) }}" class="group flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#0361fc]/30 hover:shadow-xl hover:shadow-[#0361fc]/5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <img src="{{ $visaProduct->icon_url }}" alt="{{ $visaProduct->name }}" class="h-10 w-10 rounded-lg object-cover border border-slate-100 shadow-sm shrink-0">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $visaProduct->country->name }}</p>
                                        <h3 class="mt-1 text-xl font-bold text-slate-900 group-hover:text-[#0361fc] transition-colors">{{ $visaProduct->name }}</h3>
                                    </div>
                                </div>
                                <span class="inline-flex shrink-0 items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#0361fc] ring-1 ring-inset ring-[#0361fc]/10">{{ $visaProduct->type }}</span>
                            </div>
                            <p class="mt-4 text-sm text-slate-600 line-clamp-2">{{ $visaProduct->short_description ?: __('client.dashboard_default_desc') }}</p>
                            <div class="mt-6 flex items-end justify-between text-sm">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('client.dashboard_starting_from') }}</span>
                                <span class="text-lg font-bold text-slate-900">IDR {{ number_format((float) $visaProduct->discount_price ?: (float) $visaProduct->base_price, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center sm:col-span-2 lg:col-span-3">
                            <p class="text-sm text-slate-500">{{ __('client.dashboard_no_recommendations') }}</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-layouts.app>
