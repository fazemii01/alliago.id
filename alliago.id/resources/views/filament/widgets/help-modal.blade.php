<x-filament-widgets::widget>
    <x-filament::section>
        <div 
            x-data="{
                open: false,
                tab: 'started',
                key: 'alliago_help_v3_{{ auth()->id() }}',
                init() {
                    setTimeout(() => {
                        if (!sessionStorage.getItem(this.key)) {
                            this.open = true;
                        }
                    }, 500);
                },
                close() {
                    this.open = false;
                    sessionStorage.setItem(this.key, '1');
                }
            }"
            class="flex items-center justify-between"
        >
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Admin Help & Guides</h2>
                    <p class="text-sm text-gray-500">New here? Check out the documentation to get started.</p>
                </div>
            </div>
            
            <button 
                @click="open = true" 
                class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
            >
                Open Guide
            </button>

            {{-- ╔══════════════════════════════════════════════════════════════════╗
                 ║  Teleport to <body> so position:fixed works correctly           ║
                 ╚══════════════════════════════════════════════════════════════════╝ --}}
            <template x-teleport="body">
                <div>
                    {{-- BACKDROP --}}
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @click="close()"
                        style="position:fixed;inset:0;z-index:9990;background:rgba(15,23,42,0.65);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);"
                    ></div>

                    {{-- MODAL --}}
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-250"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        style="position:fixed;inset:0;z-index:9991;display:flex;align-items:center;justify-content:center;padding:1.5rem;pointer-events:none;"
                    >
                        <div @click.stop style="pointer-events:auto;width:100%;max-width:42rem;background:#ffffff;border-radius:1rem;box-shadow:0 25px 60px -12px rgba(0,0,0,0.35);display:flex;flex-direction:column;max-height:90vh;overflow:hidden;">

                            {{-- ── HEADER ─────────────────────────────────────────────── --}}
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;flex-shrink:0;">
                                <div style="display:flex;align-items:center;gap:0.75rem;">
                                    <span style="width:2.25rem;height:2.25rem;background:#fffbeb;border:1px solid #fde68a;border-radius:0.625rem;display:flex;align-items:center;justify-content:center;font-size:1.125rem;flex-shrink:0;">👋</span>
                                    <div>
                                        <p style="margin:0;font-size:1.0625rem;font-weight:700;color:#0f172a;line-height:1.3;">Welcome to Alliago.id</p>
                                        <p style="margin:0.125rem 0 0;font-size:0.8125rem;color:#94a3b8;">Here is a quick overview to get you started.</p>
                                    </div>
                                </div>
                                <button @click="close()" style="padding:0.4rem;border-radius:9999px;border:none;background:transparent;cursor:pointer;color:#94a3b8;line-height:1;transition:all 0.15s;" onmouseover="this.style.background='#f1f5f9';this.style.color='#475569'" onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            {{-- ── TAB BAR ─────────────────────────────────────────────── --}}
                            <div style="display:flex;border-bottom:1px solid #f1f5f9;background:#f8fafc;padding:0.5rem 1rem 0;flex-shrink:0;gap:0.5rem;">
                                
                                {{-- Getting Started Tab --}}
                                <button
                                    @click="tab = 'started'"
                                    :style="tab === 'started' ? 'border-bottom-color:#f59e0b;color:#d97706;background:#fff;' : 'border-bottom-color:transparent;color:#64748b;background:transparent;'"
                                    style="display:flex;align-items:center;gap:0.5rem;padding:0.625rem 1rem;font-size:0.8125rem;font-weight:600;border:none;border-bottom:2px solid transparent;border-radius:0.5rem 0.5rem 0 0;cursor:pointer;transition:all 0.15s;"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Getting Started
                                </button>

                                {{-- What's New Tab --}}
                                <button
                                    @click="tab = 'changelog'"
                                    :style="tab === 'changelog' ? 'border-bottom-color:#f59e0b;color:#d97706;background:#fff;' : 'border-bottom-color:transparent;color:#64748b;background:transparent;'"
                                    style="display:flex;align-items:center;gap:0.5rem;padding:0.625rem 1rem;font-size:0.8125rem;font-weight:600;border:none;border-bottom:2px solid transparent;border-radius:0.5rem 0.5rem 0 0;cursor:pointer;transition:all 0.15s;"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    What's New
                                </button>

                                {{-- Documentation Tab --}}
                                <button
                                    @click="tab = 'docs'"
                                    :style="tab === 'docs' ? 'border-bottom-color:#f59e0b;color:#d97706;background:#fff;' : 'border-bottom-color:transparent;color:#64748b;background:transparent;'"
                                    style="display:flex;align-items:center;gap:0.5rem;padding:0.625rem 1rem;font-size:0.8125rem;font-weight:600;border:none;border-bottom:2px solid transparent;border-radius:0.5rem 0.5rem 0 0;cursor:pointer;transition:all 0.15s;"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Documentation
                                </button>

                            </div>

                            {{-- ── SCROLLABLE BODY ─────────────────────────────────────── --}}
                            <div style="flex:1;overflow-y:auto;padding:1.5rem;">

                                {{-- ══ GETTING STARTED ════════════════════════════════════ --}}
                                <div x-show="tab === 'started'">
                                    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:0.75rem;padding:1rem;margin-bottom:1.25rem;">
                                        <p style="margin:0 0 0.25rem;font-size:0.875rem;font-weight:600;color:#92400e;">👋 Welcome to the Admin Panel</p>
                                        <p style="margin:0;font-size:0.8125rem;color:#b45309;line-height:1.6;">This dashboard allows you to oversee all visa operations, client applications, and financial metrics in one place.</p>
                                    </div>

                                    <p style="margin:0 0 1rem;font-size:0.875rem;font-weight:700;color:#0f172a;">Quick Start Guide</p>

                                    @foreach([
                                        ['🌐','#eff6ff','#bfdbfe','Set Up Your Visa Catalog',   'Start by adding Countries and Visa Products in the Visa Catalog section. Each product needs a country and price before it goes live.'],
                                        ['📋','#fffbeb','#fde68a','Process Client Applications', 'Once clients submit orders they appear in Operations → Applications. Review documents, update statuses, and send feedback.'],
                                        ['💰','#ecfdf5','#a7f3d0','Track Invoices & Payments',   'Paid orders generate invoices automatically. Visit Finance → Invoices. Configure payment options under Finance → Payment Methods.'],
                                        ['🔐','#f5f3ff','#ddd6fe','Configure Team Access',       'Add staff under Operations → Users. Then set which dashboard sections each role can access in Settings → Roles & Permissions.'],
                                    ] as [$emoji, $bg, $border, $title, $desc])
                                    <div style="display:flex;align-items:flex-start;gap:1rem;background:#fff;border:1px solid #f1f5f9;border-radius:0.75rem;padding:1rem;box-shadow:0 1px 3px rgba(0,0,0,0.04);margin-bottom:0.75rem;">
                                        <div style="width:2.5rem;height:2.5rem;background:{{ $bg }};border:1px solid {{ $border }};border-radius:9999px;display:flex;align-items:center;justify-content:center;font-size:1.125rem;flex-shrink:0;">{{ $emoji }}</div>
                                        <div>
                                            <p style="margin:0 0 0.25rem;font-size:0.8125rem;font-weight:600;color:#0f172a;">{{ $title }}</p>
                                            <p style="margin:0;font-size:0.8125rem;color:#64748b;line-height:1.6;">{{ $desc }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- ══ CHANGELOG ══════════════════════════════════════════ --}}
                                <div x-show="tab === 'changelog'">
                                    <p style="margin:0 0 1.25rem;font-size:0.875rem;font-weight:700;color:#0f172a;">Recent Updates</p>
                                    @foreach([
                                        ['v1.5.0','May 9, 2026',  'Latest', '#d1fae5','#065f46',['🔐 Grouped Roles & Permissions by category (Operations, Finance, Catalog, Settings).','🗑️ Delete action on Applications — row, header, and bulk delete.','🏳️ Fixed missing flag emojis in the animated countries marquee.','📖 Welcome modal + Help & Guides + Documentation Center.']],
                                        ['v1.4.0','May 5, 2026',  'Feature','#ede9fe','#5b21b6',['📊 Dashboard stats widget with trend tracking.','💳 Xendit payment gateway with automatic webhook handling.','🔔 Auto-revision messages sent to clients when documents are flagged.']],
                                        ['v1.3.0','Apr 15, 2026', 'Feature','#dbeafe','#1e40af',['💬 Real-time messaging between admin and clients.','🌟 Testimonials and Site FAQs management pages.','🗺️ Animated country marquee strip on the landing page.']],
                                    ] as [$ver, $date, $tag, $tagBg, $tagClr, $changes])
                                    <div style="position:relative;padding-left:1.25rem;border-left:2px solid #e2e8f0;margin-bottom:1.5rem;">
                                        <div style="position:absolute;left:-0.5rem;top:0.375rem;width:0.875rem;height:0.875rem;border-radius:9999px;border:2px solid #f59e0b;background:#fff;"></div>
                                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;flex-wrap:wrap;">
                                            <span style="font-size:0.875rem;font-weight:700;color:#0f172a;">{{ $ver }}</span>
                                            <span style="background:{{ $tagBg }};color:{{ $tagClr }};font-size:0.625rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;padding:0.125rem 0.5rem;border-radius:9999px;">{{ $tag }}</span>
                                            <span style="font-size:0.75rem;color:#94a3b8;">{{ $date }}</span>
                                        </div>
                                        @foreach($changes as $c)
                                        <div style="display:flex;align-items:flex-start;gap:0.5rem;margin-bottom:0.375rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 20 20" fill="#10b981" style="flex-shrink:0;margin-top:0.1875rem;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                            <span style="font-size:0.8125rem;color:#475569;line-height:1.5;">{{ $c }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>

                                {{-- ══ DOCUMENTATION ══════════════════════════════════════ --}}
                                <div x-show="tab === 'docs'">
                                    <div style="background:#0f172a;border-radius:0.875rem;padding:1.25rem;margin-bottom:1.25rem;">
                                        <p style="margin:0 0 0.25rem;font-size:0.9375rem;font-weight:700;color:#fff;">📖 Knowledge Base</p>
                                        <p style="margin:0 0 1rem;font-size:0.8125rem;color:#94a3b8;">Detailed guides for every admin feature.</p>
                                        <a href="{{ route('filament.admin.pages.documentation-page') }}" @click="close()" style="display:flex;align-items:center;justify-content:center;gap:0.5rem;background:#f59e0b;color:#fff;font-size:0.8125rem;font-weight:600;padding:0.625rem 1rem;border-radius:0.625rem;text-decoration:none;" onmouseover="this.style.background='#d97706'" onmouseout="this.style.background='#f59e0b'">
                                            ↗ Open Documentation Center
                                        </a>
                                    </div>

                                    <p style="margin:0 0 0.75rem;font-size:0.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">Quick Links</p>
                                    @foreach([
                                        ['📋','Applications & Document Review','Process orders, review docs, message clients.'],
                                        ['🗺️','Visa Catalog Management',        'Countries, visa products, pricing.'],
                                        ['💰','Finance & Payments',             'Invoices, payment gateway, reports.'],
                                        ['🔐','Roles & Permissions',            'Team access control by category.'],
                                        ['⚙️','Settings & Site Content',        'FAQs, testimonials, user management.'],
                                    ] as [$e, $t, $d])
                                    <a href="{{ route('filament.admin.pages.documentation-page') }}" @click="close()" style="display:flex;align-items:center;gap:0.75rem;background:#fff;border:1px solid #f1f5f9;border-radius:0.75rem;padding:0.875rem;text-decoration:none;margin-bottom:0.5rem;transition:all 0.15s;" onmouseover="this.style.borderColor='#fde68a'" onmouseout="this.style.borderColor='#f1f5f9'">
                                        <span style="font-size:1.25rem;flex-shrink:0;">{{ $e }}</span>
                                        <div style="flex:1;">
                                            <p style="margin:0;font-size:0.8125rem;font-weight:600;color:#0f172a;">{{ $t }}</p>
                                            <p style="margin:0.125rem 0 0;font-size:0.75rem;color:#94a3b8;">{{ $d }}</p>
                                        </div>
                                        <span style="color:#cbd5e1;font-size:0.875rem;">›</span>
                                    </a>
                                    @endforeach
                                </div>

                            </div>{{-- /scrollable --}}

                            {{-- ── FOOTER ──────────────────────────────────────────────── --}}
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.5rem;border-top:1px solid #f1f5f9;background:#f8fafc;flex-shrink:0;">
                                <p style="margin:0;font-size:0.8125rem;color:#64748b;">
                                    Need more help?
                                    <a href="{{ route('filament.admin.pages.help-page') }}" @click="close()" style="color:#d97706;font-weight:600;text-decoration:none;">View full guide</a>
                                </p>
                                <button @click="close()" style="background:#f59e0b;color:#fff;font-size:0.8125rem;font-weight:600;padding:0.625rem 1.25rem;border-radius:0.625rem;border:none;cursor:pointer;box-shadow:0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.background='#d97706'" onmouseout="this.style.background='#f59e0b'">
                                    Let's Go →
                                </button>
                            </div>

                        </div>{{-- /modal box --}}
                    </div>{{-- /modal wrapper --}}
                </div>{{-- /teleport wrapper --}}
            </template>{{-- /x-teleport --}}
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
