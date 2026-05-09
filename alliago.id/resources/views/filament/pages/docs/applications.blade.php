@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; $badge = 'inline-block rounded-md px-2 py-0.5 text-xs font-semibold'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">📋 Applications</h2>
        <p class="{{ $p }} text-gray-500">Everything related to processing client visa applications.</p>
    </div>

    {{-- What is an Application --}}
    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">What is an Application?</h3>
        <p class="{{ $p }}">
            An <strong>Application</strong> is created when a client purchases a visa product through the client portal.
            It contains the client's personal information, uploaded documents, payment status, and a communication thread between the client and admin.
        </p>
    </div>

    {{-- Application Statuses --}}
    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Application Statuses</h3>
        <p class="{{ $p }} mb-3">Each application moves through the following stages:</p>
        <div class="space-y-2">
            @foreach([
                ['Draft',             'gray',    'The client has not completed payment yet.'],
                ['Pending Payment',   'yellow',  'Order placed, waiting for payment confirmation.'],
                ['Documents Pending', 'orange',  'Payment confirmed, waiting for client to upload required documents.'],
                ['Under Review',      'blue',    'Admin is actively reviewing submitted documents.'],
                ['Needs Revision',    'red',     'Admin has flagged one or more documents for re-upload.'],
                ['Ready',             'emerald', 'All documents approved — visa application is ready to be submitted to the embassy.'],
                ['Completed',         'green',   'The visa has been obtained and the application is closed.'],
            ] as [$status, $color, $desc])
            <div class="flex items-start gap-3">
                <span class="{{ $badge }} bg-{{ $color }}-100 text-{{ $color }}-700 shrink-0 mt-0.5">{{ $status }}</span>
                <p class="text-sm text-gray-500">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Processing an Application --}}
    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">How to Process an Application</h3>
        <ol class="space-y-3 list-none">
            @foreach([
                'Go to <strong>Operations → Applications</strong> and click the <strong>Process</strong> button on any row.',
                'Review each document by clicking the <strong>View Document</strong> link next to it.',
                'Change each document\'s status to <strong>Approved</strong>, <strong>Needs Revision</strong>, or leave as pending.',
                'If revision is needed, add a note in the <em>Feedback</em> field — this is automatically sent to the client as a message.',
                'The overall Application status auto-updates based on document statuses (e.g. all approved → <strong>Ready</strong>).',
                'You can also override the status manually from the status dropdown at the top of the form.',
                'Save using the <strong>Save changes</strong> button at the bottom.',
            ] as $i => $step)
            <li class="flex items-start gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-100 text-xs font-bold text-amber-700">{{ $i + 1 }}</span>
                <p class="text-sm text-gray-600 leading-relaxed">{!! $step !!}</p>
            </li>
            @endforeach
        </ol>
    </div>

    {{-- Messaging --}}
    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Client Messaging</h3>
        <p class="{{ $p }}">
            Each application has a <strong>Messages</strong> tab at the bottom of the edit page (a Relation Manager).
            Admins can send messages directly to the client here. Unread message counts appear as a badge in the Applications list table.
            When you open an application, all unread client messages are automatically marked as read.
        </p>
        <div class="rounded-lg bg-blue-50 border border-blue-100 p-3 text-sm text-blue-800">
            💡 When you set a document to <strong>Needs Revision</strong> and save, a message is automatically sent to the client with the document name and your feedback note.
        </div>
    </div>

    {{-- Deleting --}}
    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Deleting Applications</h3>
        <p class="{{ $p }}">
            Applications can be deleted from the table list using the <strong>Delete</strong> action button,
            or from inside the edit page using the <strong>Delete</strong> button in the page header.
            Bulk deletion is also available by selecting multiple rows in the table.
            Only admins with the <code class="bg-gray-100 px-1 rounded text-xs">applications.delete</code> permission can delete.
        </p>
    </div>

</div>
