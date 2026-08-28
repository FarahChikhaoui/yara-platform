<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogger
{
    public static function log(
        string $action,
        ?Model $entity = null,
        ?string $description = null,
        array $metadata = []
    ): AuditLog {
        return AuditLog::create([
            'user_id' => auth()->id(),

            'action' => $action,

            'entity_type' => $entity
                ? class_basename($entity)
                : null,

            'entity_id' => $entity?->getKey(),

            'description' => $description,

            'metadata' => !empty($metadata)
                ? $metadata
                : null,

            'ip_address' => request()->ip(),
        ]);
    }
}