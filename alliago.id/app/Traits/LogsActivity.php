<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            self::logAction($model, 'create', "Created " . class_basename($model) . ": " . self::getModelIdentifier($model));
        });

        static::updated(function (Model $model) {
            if ($model->wasChanged()) {
                $changes = $model->getChanges();
                unset($changes['updated_at']); // Skip updated_at noise
                
                if (!empty($changes)) {
                    $original = array_intersect_key($model->getOriginal(), $changes);
                    
                    // Redact sensitive keys
                    $sensitiveKeys = ['password', 'remember_token', 'secret', 'token', 'key', 'payload'];
                    foreach ($sensitiveKeys as $key) {
                        if (isset($changes[$key])) $changes[$key] = '[REDACTED]';
                        if (isset($original[$key])) $original[$key] = '[REDACTED]';
                    }

                    self::logAction($model, 'update', "Updated " . class_basename($model) . ": " . self::getModelIdentifier($model), [
                        'old' => $original,
                        'new' => $changes,
                    ]);
                }
            }
        });

        static::deleted(function (Model $model) {
            $original = $model->getOriginal();
            
            $sensitiveKeys = ['password', 'remember_token', 'secret', 'token', 'key', 'payload'];
            foreach ($sensitiveKeys as $key) {
                if (isset($original[$key])) $original[$key] = '[REDACTED]';
            }

            self::logAction($model, 'delete', "Deleted " . class_basename($model) . ": " . self::getModelIdentifier($model), [
                'old' => $original,
            ]);
        });
    }

    protected static function logAction(Model $model, string $action, string $description, ?array $properties = null): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('admin') || $user->hasRole('staff')) {
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => $action,
                    'model_type' => get_class($model),
                    'model_id' => (string) $model->getKey(),
                    'description' => $description,
                    'properties' => $properties,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        }
    }

    protected static function getModelIdentifier(Model $model): string
    {
        return $model->name ?? $model->title ?? $model->label ?? $model->reference_number ?? $model->filename ?? $model->subject ?? (string) $model->getKey();
    }
}
