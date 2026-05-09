@php $h2 = 'text-xl font-bold text-gray-900 mb-1'; $h3 = 'text-base font-semibold text-gray-900 mb-2'; $p = 'text-sm text-gray-600 leading-relaxed'; $section = 'rounded-xl border border-gray-100 bg-white p-5 shadow-sm space-y-3'; @endphp

<div class="space-y-5">

    <div>
        <h2 class="{{ $h2 }}">⚙️ Settings</h2>
        <p class="{{ $p }} text-gray-500">Manage public-facing site content — FAQs and Testimonials.</p>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Site FAQs</h3>
        <p class="{{ $p }}">
            FAQs appear on the public landing page in the FAQ accordion section.
            You can add, edit, reorder, and delete FAQ entries from <strong>Settings → Site FAQs</strong>.
        </p>
        <ul class="space-y-1.5 text-sm text-gray-600 list-disc list-inside">
            <li>Each FAQ has a <strong>Question</strong> and <strong>Answer</strong>.</li>
            <li>Use the <strong>Sort Order</strong> field to control display order on the page.</li>
            <li>Inactive FAQs are hidden from the public site but preserved in the database.</li>
        </ul>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Testimonials</h3>
        <p class="{{ $p }}">
            Testimonials are client reviews displayed on the landing page.
            Go to <strong>Settings → Testimonials</strong> to manage them.
        </p>
        <ul class="space-y-1.5 text-sm text-gray-600 list-disc list-inside">
            <li>Each testimonial has a <strong>Client Name</strong>, <strong>Content</strong>, optional <strong>Rating</strong>, and an optional <strong>Avatar URL</strong>.</li>
            <li>Inactive testimonials are hidden from the homepage automatically.</li>
            <li>Sort order controls carousel/display sequence.</li>
        </ul>
    </div>

    <div class="{{ $section }}">
        <h3 class="{{ $h3 }}">Users</h3>
        <p class="{{ $p }}">
            <strong>Operations → Users</strong> lets you manage all registered accounts:
        </p>
        <ul class="space-y-1.5 text-sm text-gray-600 list-disc list-inside">
            <li>View all users (admin staff and clients).</li>
            <li>Create new admin/staff users and assign them roles.</li>
            <li>Edit user details (name, email, role).</li>
            <li>Delete users — with care, as this cannot be undone.</li>
        </ul>
        <div class="rounded-lg bg-amber-50 border border-amber-100 p-3 text-sm text-amber-800">
            ⚠️ Client accounts (users who signed up through the public portal) should generally not be deleted — their application history will be lost.
        </div>
    </div>

</div>
