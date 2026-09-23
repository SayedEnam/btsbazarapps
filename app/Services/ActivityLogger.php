<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Record one audit-trail entry. Called explicitly from the business
     * logic that already knows what happened and why (application
     * approvals, payroll status changes, officer/user/role management) —
     * not wired in generically for every model event, so the log stays a
     * readable narrative ("Karim approved Application #APP-1025") rather
     * than a raw diff of every column on every save.
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'model_type' => $model?->getMorphClass(),
            'model_id' => $model?->getKey(),
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
