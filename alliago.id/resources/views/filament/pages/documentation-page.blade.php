<x-filament-panels::page>
    {{--
        Documentation Center
        Full-featured admin documentation with sidebar navigation + anchored sections.
    --}}

    <div class="max-w-5xl mx-auto" x-data="{ activeSection: 'overview' }">

        {{-- Back link --}}
        <div class="mb-6">
            <a href="{{ route('filament.admin.pages.help-page') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-amber-600 transition-colors">
                <x-heroicon-o-arrow-left class="h-4 w-4" />
                Back to Help & Guides
            </a>
        </div>

        <div class="flex gap-8">

            {{-- ── Sticky Sidebar Navigation ─────────────────────────────── --}}
            <aside class="hidden lg:block w-56 shrink-0">
                <div class="sticky top-4 space-y-1">
                    <p class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400 px-3">Contents</p>
                    @foreach([
                        ['id' => 'overview',      'icon' => 'heroicon-o-home',                    'label' => 'Overview'],
                        ['id' => 'applications',  'icon' => 'heroicon-o-clipboard-document-list', 'label' => 'Applications'],
                        ['id' => 'catalog',       'icon' => 'heroicon-o-globe-alt',               'label' => 'Visa Catalog'],
                        ['id' => 'finance',       'icon' => 'heroicon-o-banknotes',               'label' => 'Finance'],
                        ['id' => 'roles',         'icon' => 'heroicon-o-shield-check',            'label' => 'Roles & Permissions'],
                        ['id' => 'settings',      'icon' => 'heroicon-o-cog-6-tooth',            'label' => 'Settings'],
                        ['id' => 'faq',           'icon' => 'heroicon-o-question-mark-circle',   'label' => 'FAQ'],
                    ] as $nav)
                    <button
                        @click="activeSection = '{{ $nav['id'] }}'"
                        :class="activeSection === '{{ $nav['id'] }}' ? 'bg-amber-50 text-amber-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition-all text-left"
                    >
                        <x-dynamic-component :component="$nav['icon']" class="h-4 w-4 shrink-0" />
                        {{ $nav['label'] }}
                    </button>
                    @endforeach
                </div>
            </aside>

            {{-- ── Content Area ───────────────────────────────────────────── --}}
            <div class="flex-1 min-w-0 space-y-6">

                {{-- ── OVERVIEW ─────────────────────────────────────────── --}}
                <div x-show="activeSection === 'overview'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="rounded-2xl border border-amber-100 bg-amber-50 p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">📖 Alliago.id Admin — Documentation Center</h2>
                        <p class="text-amber-800/70 leading-relaxed">
                            This documentation covers every section of the Alliago.id administration panel.
                            Use the sidebar to jump to any topic. This panel only covers the <strong>admin dashboard</strong>;
                            public-facing pages (landing page, visa catalog, checkout) are not affected by admin settings here.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        @foreach([
                            ['emoji'=>'📋','title'=>'Applications','desc'=>'Process client visa orders','id'=>'applications'],
                            ['emoji'=>'🗺️','title'=>'Visa Catalog','desc'=>'Countries & visa products','id'=>'catalog'],
                            ['emoji'=>'💰','title'=>'Finance','desc'=>'Invoices & payments','id'=>'finance'],
                            ['emoji'=>'🔐','title'=>'Roles','desc'=>'Access control','id'=>'roles'],
                            ['emoji'=>'⚙️','title'=>'Settings','desc'=>'Site content','id'=>'settings'],
                            ['emoji'=>'❓','title'=>'FAQ','desc'=>'Common questions','id'=>'faq'],
                        ] as $card)
                        <button
                            @click="activeSection = '{{ $card['id'] }}'"
                            class="group flex items-center gap-3 rounded-xl border border-gray-100 bg-white p-4 text-left shadow-sm transition hover:border-amber-200 hover:shadow-md"
                        >
                            <span class="text-2xl">{{ $card['emoji'] }}</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">{{ $card['title'] }}</p>
                                <p class="text-xs text-gray-400">{{ $card['desc'] }}</p>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- ── APPLICATIONS ─────────────────────────────────────── --}}
                <div x-show="activeSection === 'applications'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('filament.pages.docs.applications')
                </div>

                {{-- ── VISA CATALOG ─────────────────────────────────────── --}}
                <div x-show="activeSection === 'catalog'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('filament.pages.docs.catalog')
                </div>

                {{-- ── FINANCE ──────────────────────────────────────────── --}}
                <div x-show="activeSection === 'finance'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('filament.pages.docs.finance')
                </div>

                {{-- ── ROLES ─────────────────────────────────────────────── --}}
                <div x-show="activeSection === 'roles'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('filament.pages.docs.roles')
                </div>

                {{-- ── SETTINGS ─────────────────────────────────────────── --}}
                <div x-show="activeSection === 'settings'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('filament.pages.docs.settings')
                </div>

                {{-- ── FAQ ───────────────────────────────────────────────── --}}
                <div x-show="activeSection === 'faq'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('filament.pages.docs.faq')
                </div>

            </div>
        </div>
    </div>
</x-filament-panels::page>
