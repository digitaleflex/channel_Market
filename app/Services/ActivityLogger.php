<?php

namespace App\Services;

use App\Mail\AdminActivityAlertMail;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an administrative or system action, and trigger email alerts.
     */
    public static function log(string $action, string $description, ?Model $model = null): void
    {
        $oldValues = null;
        $newValues = null;

        if ($model) {
            if ($model->wasRecentlyCreated) {
                $newValues = $model->getAttributes();
            } else {
                $newValues = $model->getChanges();
                $oldValues = [];
                foreach ($newValues as $key => $value) {
                    $oldValues[$key] = $model->getOriginal($key);
                }
            }

            // Exclude sensitive or large fields to keep logs clean
            $excludeFields = ['password', 'remember_token', 'description', 'file_path', 'image', 'created_at', 'updated_at'];
            if ($oldValues) {
                $oldValues = array_diff_key($oldValues, array_flip($excludeFields));
            }
            if ($newValues) {
                $newValues = array_diff_key($newValues, array_flip($excludeFields));
            }
        }

        try {
            // Write to database
            $log = ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'description' => $description,
                'old_values' => !empty($oldValues) ? $oldValues : null,
                'new_values' => !empty($newValues) ? $newValues : null,
                'ip_address' => Request::ip(),
            ]);

            // Notify administrators
            $adminEmails = ['elfridayemadje5@gmail.com', 'digitaleflex@gmail.com'];
            Mail::to($adminEmails)->send(new AdminActivityAlertMail($log));
        } catch (\Throwable $e) {
            Log::error('ActivityLogger failed: ' . $e->getMessage(), [
                'action' => $action,
                'description' => $description
            ]);
        }
    }
}
