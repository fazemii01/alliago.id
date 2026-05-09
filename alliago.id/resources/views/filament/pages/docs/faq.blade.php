@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">❓ Frequently Asked Questions</h2>
        <p class="{{ $p }} text-gray-500">Common questions from admin team members.</p>
    </div>

    @foreach([
        [
            'q' => 'Why can\'t my staff member see a certain section?',
            'a' => 'Staff members need explicit permissions for each dashboard section. Go to <strong>Settings → Roles & Permissions</strong>, edit the "staff" role, and enable the appropriate permission group tab. Remember: permissions only affect the admin dashboard, not the public website.',
        ],
        [
            'q' => 'Can I delete an invoice?',
            'a' => 'No — invoices are permanent financial records and cannot be deleted from the admin UI. This is intentional to maintain financial audit integrity. If you need to void a transaction, contact your payment gateway (Xendit) directly.',
        ],
        [
            'q' => 'How do I change the price of a visa product without affecting existing paid orders?',
            'a' => 'Just update the price in <strong>Visa Catalog → Visa Products</strong>. Invoice amounts are locked at the time of payment, so existing invoices are unaffected by any price changes you make.',
        ],
        [
            'q' => 'How do I notify a client that their application needs changes?',
            'a' => 'Open the application, change the relevant document\'s status to <strong>Needs Revision</strong>, and add your note in the Feedback field. When you save, a message is automatically sent to the client with your feedback. You can also send free-form messages via the Messages tab at the bottom of the application.',
        ],
        [
            'q' => 'What happens when all documents are approved?',
            'a' => 'The application status automatically changes to <strong>Ready</strong>. The client will see this in their portal. You can then manually set it to <strong>Completed</strong> once the visa has been obtained.',
        ],
        [
            'q' => 'Can I add new permission groups or permissions?',
            'a' => 'Not from the UI — the permission structure is defined in code (<code>RolesAndPermissionsSeeder</code>). Contact your developer to add new resource permissions if you add new admin sections.',
        ],
        [
            'q' => 'How do I add a new staff member?',
            'a' => 'Go to <strong>Operations → Users → New User</strong>. Fill in their name, email, and a temporary password. Assign them the <strong>staff</strong> role. Then go to <strong>Settings → Roles</strong> and configure what sections they can access.',
        ],
    ] as $faq)
    <div x-data="{ open: false }" class="rounded-xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <button
            @click="open = !open"
            class="flex w-full items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors"
        >
            <span class="text-sm font-semibold text-gray-900">{{ $faq['q'] }}</span>
            <x-heroicon-o-chevron-down
                class="h-4 w-4 text-gray-400 transition-transform duration-200 shrink-0 ml-3"
                ::class="open ? 'rotate-180' : ''"
            />
        </button>
        <div x-show="open" x-collapse class="border-t border-gray-100 px-5 py-4 text-sm text-gray-600 leading-relaxed">
            {!! $faq['a'] !!}
        </div>
    </div>
    @endforeach

</div>
