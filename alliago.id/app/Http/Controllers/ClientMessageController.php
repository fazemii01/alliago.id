<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientMessageController extends Controller
{
    public function store(Request $request, Application $application): RedirectResponse
    {
        abort_unless($application->user_id === $request->user()->id || $request->user()->hasRole('admin') || $request->user()->hasRole('staff'), 403);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $application->messages()->create([
            'sender_id' => $request->user()->id,
            'is_admin' => false,
            'message' => $validated['message'],
        ]);

        return redirect()
            ->route('client.applications.show', $application)
            ->with('status', 'Message sent to admin.');
    }
}
