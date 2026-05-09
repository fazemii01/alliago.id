<x-filament-panels::page>
    {{-- 
        Help & Guides Page
        Three tabs: Getting Started · What's New · Documentation
        Uses Alpine.js (bundled with Filament) for tab switching.
    --}}
    <div
        x-data="{ activeTab: 'started' }"
        class="max-w-3xl mx-auto"
    >
        {{-- ── Page Hero ─────────────────────────────────────────────────── --}}
        <div class="mb-8 flex items-center gap-4 rounded-2xl border border-amber-100 bg-amber-50 p-6">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white text-3xl shadow-sm ring-1 ring-amber-100">
                👋
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Welcome to Alliago.id Admin</h2>
                <p class="mt-1 text-sm text-amber-800/70">
                    This page has everything you need — quick start guide, recent updates, and full documentation.
                </p>
            </div>
        </div>

        {{-- ── Tab Bar ────────────────────────────────────────────────────── --}}
        <div class="mb-6 flex gap-1 rounded-xl border border-gray-200 bg-gray-100 p-1">
            <button
                @click="activeTab = 'started'"
                :class="activeTab === 'started'
                    ? 'bg-white text-amber-600 shadow-sm ring-1 ring-gray-200'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all"
            >
                <x-heroicon-o-play-circle class="h-4 w-4" />
                Getting Started
            </button>
            <button
                @click="activeTab = 'changelog'"
                :class="activeTab === 'changelog'
                    ? 'bg-white text-amber-600 shadow-sm ring-1 ring-gray-200'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all"
            >
                <x-heroicon-o-clock class="h-4 w-4" />
                What's New
            </button>
            <button
                @click="activeTab = 'docs'"
                :class="activeTab === 'docs'
                    ? 'bg-white text-amber-600 shadow-sm ring-1 ring-gray-200'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all"
            >
                <x-heroicon-o-book-open class="h-4 w-4" />
                Documentation
            </button>
        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- TAB 1 · GETTING STARTED                                        --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'started'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            <h3 class="mb-4 text-base font-bold text-gray-900">Quick Start Guide</h3>

            <div class="relative space-y-4 before:absolute before:inset-y-0 before:left-5 before:w-px before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">

                @foreach([
                    [
                        'step' => '1',
                        'color' => 'blue',
                        'icon' => 'heroicon-o-globe-alt',
                        'title' => 'Set Up Your Visa Catalog',
                        'desc' => 'Start by adding Countries and Visa Products under the Visa Catalog section. Each product needs a country, price, and processing time before it appears on the public site.',
                        'link' => '/admin/visa-products',
                        'linkLabel' => 'Go to Visa Products →',
                    ],
                    [
                        'step' => '2',
                        'color' => 'amber',
                        'icon' => 'heroicon-o-clipboard-document-list',
                        'title' => 'Process Client Applications',
                        'desc' => 'Once clients submit orders, they appear in Operations → Applications. Review documents, update statuses, and leave feedback directly in the application edit page.',
                        'link' => '/admin/applications',
                        'linkLabel' => 'Go to Applications →',
                    ],
                    [
                        'step' => '3',
                        'color' => 'emerald',
                        'icon' => 'heroicon-o-banknotes',
                        'title' => 'Track Invoices & Payments',
                        'desc' => 'All paid orders generate an invoice automatically. Go to Finance → Invoices to view payment status. Configure payment gateways under Finance → Payment Methods.',
                        'link' => '/admin/invoices',
                        'linkLabel' => 'Go to Invoices →',
                    ],
                    [
                        'step' => '4',
                        'color' => 'purple',
                        'icon' => 'heroicon-o-users',
                        'title' => 'Manage Team Access',
                        'desc' => 'Add staff accounts under Operations → Users. Assign them to the "staff" role, then configure exactly which dashboard sections they can access under Settings → Roles & Permissions.',
                        'link' => '/admin/roles',
                        'linkLabel' => 'Go to Roles →',
                    ],
                    [
                        'step' => '5',
                        'color' => 'rose',
                        'icon' => 'heroicon-o-star',
                        'title' => 'Customize Site Content',
                        'desc' => 'Manage the public-facing landing page content — FAQs and Testimonials — from Settings. These feed directly into the Alliago.id homepage without any code changes.',
                        'link' => '/admin/site-faqs',
                        'linkLabel' => 'Go to Site FAQs →',
                    ],
                ] as $item)
                <div class="relative flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                    {{-- Step circle --}}
                    <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-{{ $item['color'] }}-200 bg-{{ $item['color'] }}-50 text-sm font-bold text-{{ $item['color'] }}-600">
                        {{ $item['step'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900">{{ $item['title'] }}</h4>
                        <p class="mt-1 text-sm leading-relaxed text-gray-500">{{ $item['desc'] }}</p>
                        <a href="{{ $item['link'] }}" class="mt-2 inline-flex items-center text-xs font-semibold text-amber-600 hover:underline">
                            {{ $item['linkLabel'] }}
                        </a>
                    </div>
                </div>
                @endforeach

            </div>

            {{-- Tips box --}}
            <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
                <strong>💡 Pro Tip:</strong> Use the <strong>Dashboard</strong> stats at the top to spot applications that need immediate attention — orange "Needs Attention" count means clients are waiting.
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- TAB 2 · CHANGELOG / WHAT'S NEW                                 --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'changelog'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            <h3 class="mb-4 text-base font-bold text-gray-900">Recent Updates</h3>

            <div class="space-y-8">
                @foreach([
                    [
                        'version' => 'v1.5.0',
                        'date' => 'May 9, 2026',
                        'tag' => 'Latest',
                        'tagClass' => 'bg-emerald-100 text-emerald-700',
                        'dotClass' => 'border-amber-500',
                        'changes' => [
                            ['icon' => '🔐', 'text' => 'Rebuilt Roles & Permissions with grouped categories (Operations, Finance, Catalog, Settings) — much easier to manage.'],
                            ['icon' => '🗑️', 'text' => 'Added Delete action to Applications — single delete on table row and bulk delete.'],
                            ['icon' => '🏳️', 'text' => 'Fixed missing country flag emojis in the animated countries marquee on the landing page.'],
                            ['icon' => '📖', 'text' => 'Added this Help & Guides page with changelog, quick-start, and full Documentation Center.'],
                        ],
                    ],
                    [
                        'version' => 'v1.4.0',
                        'date' => 'May 5, 2026',
                        'tag' => 'Feature',
                        'tagClass' => 'bg-purple-100 text-purple-700',
                        'dotClass' => 'border-gray-300',
                        'changes' => [
                            ['icon' => '📊', 'text' => 'Added analytical stats widget to the Filament dashboard (Total Applications, Needs Attention, Total Clients).'],
                            ['icon' => '💳', 'text' => 'Integrated Xendit payment gateway with automated webhook handling and invoice amount locking.'],
                            ['icon' => '🔔', 'text' => 'Automatic revision-request messages sent to clients when admin marks a document as "needs revision".'],
                        ],
                    ],
                    [
                        'version' => 'v1.3.0',
                        'date' => 'April 15, 2026',
                        'tag' => 'Feature',
                        'tagClass' => 'bg-blue-100 text-blue-700',
                        'dotClass' => 'border-gray-300',
                        'changes' => [
                            ['icon' => '💬', 'text' => 'Launched real-time messaging between admin and clients inside each application.'],
                            ['icon' => '🌟', 'text' => 'Testimonials and Site FAQs management added to the admin Settings section.'],
                            ['icon' => '🗺️', 'text' => 'Animated country marquee strip added to the landing page with flag emojis.'],
                        ],
                    ],
                    [
                        'version' => 'v1.2.0',
                        'date' => 'April 1, 2026',
                        'tag' => 'Update',
                        'tagClass' => 'bg-slate-100 text-slate-600',
                        'dotClass' => 'border-gray-300',
                        'changes' => [
                            ['icon' => '🎨', 'text' => 'Full light-theme redesign for the client portal and landing page.'],
                            ['icon' => '🚀', 'text' => 'Deployed to production on Nginx with proper routing configuration.'],
                            ['icon' => '🔑', 'text' => 'Admin panel dark mode disabled — forced to light theme for consistency.'],
                        ],
                    ],
                ] as $entry)
                <div class="relative pl-6 before:absolute before:inset-y-0 before:left-[7px] before:w-px before:bg-gray-200">
                    {{-- Timeline dot --}}
                    <div class="absolute left-0 top-1.5 h-3.5 w-3.5 rounded-full border-2 bg-white {{ $entry['dotClass'] }}"></div>

                    <div class="mb-2 flex items-center gap-3">
                        <span class="text-base font-bold text-gray-900">{{ $entry['version'] }}</span>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $entry['tagClass'] }}">
                            {{ $entry['tag'] }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $entry['date'] }}</span>
                    </div>

                    <ul class="space-y-2">
                        @foreach($entry['changes'] as $change)
                        <li class="flex items-start gap-2 text-sm text-gray-600">
                            <span class="shrink-0 text-base leading-5">{{ $change['icon'] }}</span>
                            <span class="leading-relaxed">{{ $change['text'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- TAB 3 · DOCUMENTATION                                          --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'docs'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Hero banner --}}
            <div class="mb-6 rounded-2xl bg-gray-900 p-6 text-white">
                <div class="flex items-center gap-3 mb-3">
                    <x-heroicon-o-book-open class="h-8 w-8 text-amber-400" />
                    <div>
                        <h3 class="text-lg font-bold">Documentation Center</h3>
                        <p class="text-sm text-gray-400">Detailed guides for every feature in the admin dashboard.</p>
                    </div>
                </div>
                <a
                    href="{{ route('filament.admin.pages.documentation-page') }}"
                    class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-amber-600"
                >
                    <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                    Open Full Documentation Center
                </a>
            </div>

            {{-- Quick links --}}
            <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-500">Quick Links</h4>

            <div class="space-y-3">
                @foreach([
                    ['icon' => '📋', 'color' => 'amber', 'title' => 'Applications & Document Review', 'desc' => 'How to process applications, review documents, and communicate with clients.', 'anchor' => '#applications'],
                    ['icon' => '🗺️', 'color' => 'blue', 'title' => 'Visa Catalog Management', 'desc' => 'Creating countries, visa products, pricing, and setting processing times.', 'anchor' => '#catalog'],
                    ['icon' => '💰', 'color' => 'emerald', 'title' => 'Finance & Payments', 'desc' => 'Understanding invoices, payment gateway setup, and financial reporting.', 'anchor' => '#finance'],
                    ['icon' => '🔐', 'color' => 'purple', 'title' => 'Roles & Permissions', 'desc' => 'Managing admin users, roles, and granular permission groups.', 'anchor' => '#roles'],
                    ['icon' => '⚙️', 'color' => 'rose', 'title' => 'Site Content & Settings', 'desc' => 'Managing public FAQs, testimonials, and site-wide settings.', 'anchor' => '#settings'],
                ] as $doc)
                <a
                    href="{{ route('filament.admin.pages.documentation-page') }}{{ $doc['anchor'] }}"
                    class="group flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-4 shadow-sm transition-all hover:border-amber-200 hover:shadow-md"
                >
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-{{ $doc['color'] }}-50 text-xl ring-1 ring-{{ $doc['color'] }}-100">
                        {{ $doc['icon'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h5 class="text-sm font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">
                                {{ $doc['title'] }}
                            </h5>
                            <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-300 group-hover:text-amber-500 transition-colors" />
                        </div>
                        <p class="mt-0.5 text-xs leading-relaxed text-gray-500">{{ $doc['desc'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-8 flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 px-5 py-4 text-sm text-gray-500">
            <span>Need more help? Contact your system administrator.</span>
            <a href="{{ route('filament.admin.pages.documentation-page') }}" class="font-semibold text-amber-600 hover:underline">
                Full Documentation →
            </a>
        </div>
    </div>
</x-filament-panels::page>
