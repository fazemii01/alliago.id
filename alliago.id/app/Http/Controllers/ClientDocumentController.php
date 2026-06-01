<?php

namespace App\Http\Controllers;

use App\Contracts\FileStorage;
use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientDocumentController extends Controller
{
    protected FileStorage $fileStorage;

    public function __construct(FileStorage $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }

    public function store(Request $request, Application $application, ApplicationDocument $document): RedirectResponse
    {
        abort_unless($application->user_id === $request->user()->id || $request->user()->hasRole('admin'), 403);
        abort_unless($document->application_id === $application->id, 404);

        $data = $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if ($document->file_path) {
            $this->fileStorage->delete($document->file_path);
        }

        $previousStatus = $application->status;
        $filePath = $this->fileStorage->store($data['document'], 'application-documents');

        $document->update([
            'file_path' => $filePath,
            'status' => 'pending_review',
            'admin_feedback' => null,
            'reviewed_at' => null,
        ]);

        $application->update([
            'status' => 'documents_pending',
        ]);

        $application->statusLogs()->create([
            'from_status' => $previousStatus,
            'to_status' => 'documents_pending',
            'message' => 'User uploaded or replaced a required document.',
        ]);

        return redirect()
            ->route('client.applications.show', $application)
            ->with('status', 'Document uploaded successfully. Admin can now review it.');
    }
}
