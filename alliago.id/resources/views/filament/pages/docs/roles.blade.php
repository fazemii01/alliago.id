@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">🔐 Roles & Permissions</h2>
        <p class="{{ $p }} text-gray-500">Control what each staff member can see and do inside the admin dashboard.</p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Important: Scope of Permissions</h3>
        <div class="rounded-lg bg-blue-50 border border-blue-100 p-3 text-sm text-blue-800">
            🔒 Permissions <strong>only control the admin dashboard</strong>. They have zero effect on the public-facing website
            (landing page, visa catalog, client checkout, etc.). A user without any admin role cannot access the dashboard at all.
        </div>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Built-in Roles</h3>
        <div class="space-y-3">
            @foreach([
                ['admin', 'danger', 'Full access to all dashboard features. Cannot be deleted. Automatically receives all permissions.'],
                ['staff', 'warning', 'Starts with zero permissions. Grant access to specific sections using the role editor.'],
            ] as [$role, $color, $desc])
            <div class="flex items-start gap-3">
                <span class="inline-block rounded-md px-2.5 py-1 text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-700 shrink-0 font-mono">{{ $role }}</span>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
        <p class="{{ $p }} text-gray-400 text-xs">You can create additional custom roles (e.g. <code>finance-manager</code>, <code>support-agent</code>) from the Roles list.</p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Permission Groups</h3>
        <p class="{{ $p }} mb-3">Permissions are organized into 4 categories that mirror the admin navigation:</p>
        <div class="grid grid-cols-2 gap-3">
            @foreach([
                ['Operations', 'amber', 'heroicon-o-clipboard-document-list', 'Applications processing, User management'],
                ['Finance',    'emerald','heroicon-o-banknotes',              'Invoice viewing, Payment method management'],
                ['Catalog',    'blue',   'heroicon-o-globe-alt',              'Visa products, Countries management'],
                ['Settings',   'purple', 'heroicon-o-cog-6-tooth',           'FAQs, Testimonials, Role management'],
            ] as [$group, $color, $icon, $covers])
            <div class="rounded-lg border border-{{ $color }}-100 bg-{{ $color }}-50 p-3">
                <div class="flex items-center gap-2 mb-1">
                    <x-dynamic-component :component="$icon" class="h-4 w-4 text-{{ $color }}-600" />
                    <span class="text-sm font-bold text-{{ $color }}-800">{{ $group }}</span>
                </div>
                <p class="text-xs text-{{ $color }}-700">{{ $covers }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Editing a Role's Permissions</h3>
        <ol class="space-y-2 text-sm text-gray-600 list-none">
            @foreach([
                'Go to <strong>Settings → Roles & Permissions</strong>.',
                'Click <strong>Edit</strong> on the role you want to configure.',
                'Use the tab bar to switch between permission groups (Operations / Finance / Catalog / Settings).',
                'Check individual permissions, or click <strong>Toggle all</strong> to grant/revoke the entire group at once.',
                'Save changes.',
            ] as $i => $step)
            <li class="flex items-start gap-3">
                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-purple-100 text-xs font-bold text-purple-700">{{ $i + 1 }}</span>
                <span>{!! $step !!}</span>
            </li>
            @endforeach
        </ol>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Table Coverage Badges</h3>
        <p class="{{ $p }}">
            The Roles list table shows <strong>X/Y coverage badges</strong> for each permission group.
            For example, <span class="rounded px-1.5 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold">5/5</span> means full access,
            <span class="rounded px-1.5 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-bold">2/5</span> means partial,
            and <span class="rounded px-1.5 py-0.5 bg-gray-100 text-gray-600 text-xs font-bold">0/5</span> means no access for that section.
            Use the <strong>"Has access to category"</strong> filter to find roles by which dashboard section they can access.
        </p>
    </div>

</div>
