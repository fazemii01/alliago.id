<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Models\ApplicationDocument;
use Illuminate\Support\Arr;
use Filament\Resources\Pages\EditRecord;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Auto-mark unread user messages as read when admin opens the edit page.
        $this->record->messages()
            ->where('is_admin', false)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $originalStatus = $this->record->status;

        $data['documents'] = $this->syncDocumentReviewTimestamps($data['documents'] ?? []);
        $this->sendRevisionPrompts($data['documents'] ?? []);
        $data['status'] = $this->resolveApplicationStatus($data['documents'], $data['status'] ?? $originalStatus);

        if ($originalStatus !== $data['status']) {
            $this->record->statusLogs()->create([
                'admin_id' => auth()->id(),
                'from_status' => $originalStatus,
                'to_status' => $data['status'],
                'message' => 'Status updated from admin dashboard.',
            ]);
        }

        return $data;
    }

    /**
     * Auto-create a targeted message when admin marks a document as needs_revision.
     * Only fires for documents that were NOT already in needs_revision state.
     */
    protected function sendRevisionPrompts(array $documents): void
    {
        $existingDocuments = $this->record->documents->keyBy('id');

        foreach ($documents as $document) {
            $status = $document['status'] ?? null;
            if ($status !== 'needs_revision') {
                continue;
            }

            $existing = $existingDocuments->get(Arr::get($document, 'id'));
            if (! $existing || $existing->status === 'needs_revision') {
                continue;
            }

            $label = $document['label'] ?? $existing->label ?? 'Document';
            $feedback = $document['admin_feedback'] ?? null;

            $body = "📋 Revision requested for \"{$label}\".";
            if (filled($feedback)) {
                $body .= "\n\nAdmin note: {$feedback}";
            }
            $body .= "\n\nPlease upload a corrected file from your order detail page.";

            $this->record->messages()->create([
                'sender_id' => auth()->id(),
                'is_admin' => true,
                'message' => $body,
            ]);
        }
    }

    protected function syncDocumentReviewTimestamps(array $documents): array
    {
        $existingDocuments = $this->record->documents->keyBy('id');

        return array_map(function (array $document) use ($existingDocuments): array {
            $status = $document['status'] ?? null;
            $existingDocument = $existingDocuments->get(Arr::get($document, 'id'));
            $alreadyReviewed = filled($existingDocument?->reviewed_at);

            if (in_array($status, ['approved', 'needs_revision'], true) && ! $alreadyReviewed) {
                $document['reviewed_at'] = now();
            }

            if (! in_array($status, ['approved', 'needs_revision'], true)) {
                $document['reviewed_at'] = null;
            }

            return $document;
        }, $documents);
    }

    protected function resolveApplicationStatus(array $documents, string $currentStatus): string
    {
        if ($documents === []) {
            return $currentStatus;
        }

        $statuses = collect($documents)
            ->pluck('status')
            ->filter()
            ->values();

        if ($statuses->contains('needs_revision')) {
            return 'needs_revision';
        }

        if ($statuses->contains('pending_upload') || $statuses->contains('pending_review')) {
            return 'documents_pending';
        }

        $reviewableStatuses = $statuses->reject(fn (string $status): bool => $status === 'optional');

        if ($reviewableStatuses->isNotEmpty() && $reviewableStatuses->every(fn (string $status): bool => $status === 'approved')) {
            return 'ready';
        }

        return $currentStatus;
    }
}
