<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logActivity($model, 'created');
        });

        static::updated(function ($model) {
            self::logActivity($model, 'updated');
        });

        static::deleted(function ($model) {
            self::logActivity($model, 'deleted');
        });
    }

    protected static function logActivity($model, $action)
    {
        $userId = Auth::id() ?? 1; // Fallback to system admin if executed via CLI/Job
        $modelName = class_basename($model);
        
        // Prepare descriptive text based on action
        $description = "User {$userId} {$action} {$modelName} ID: {$model->id}";
        
        // Optionally capture changed attributes for 'updated' action
        if ($action === 'updated') {
            $changes = $model->getChanges();
            // Don't log if only timestamps changed
            if (empty(array_diff(array_keys($changes), ['updated_at']))) {
                return;
            }
            $description .= " | Changes: " . json_encode($changes);
        }

        ActivityLog::create([
            'user_id' => $userId,
            'action' => ucfirst($action) . ' ' . $modelName,
            'description' => $description,
        ]);
    }
}
