<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

/*
 * ফিচার নোট (অডিট ট্রেইল — এই ফাইলে যা কাজ হয়েছে):
 * • কে (কোন অ্যাডমিন), কখন, কী কাজ করলো (create/edit/delete/login) সব লগ হয়।
 * • Audit::log() — যেকোনো জায়গা থেকে কল করা যায়; Observer গুলো মডেল ইভেন্টে
 *   অটো লগ পাঠায় (AppServiceProvider-এ রেজিস্টার্ড)।
 * • ইউজারের নাম/রোল স্ন্যাপশট হিসেবে থাকে — ইউজার ডিলিট হলেও লগ অক্ষত থাকে।
 * • Update-এ কোন ফিল্ড বদলালো (old → new) changes JSON-এ থাকে।
 */

class Audit
{
    /** Record an activity-log entry. Never throws — logging must not break the app. */
    public static function log(string $action, string $module, string $description, ?Model $subject = null, ?array $changes = null): void
    {
        try {
            $user = session('user');

            static::logFor(
                $user instanceof User ? $user : null,
                $user instanceof User ? null : ($user?->name),
                $action,
                $module,
                $description,
                $subject,
                $changes
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /** Used by the observer — writes with an explicit user + diffs. */
    public static function logFor(
        ?User $user,
        ?string $fallbackName,
        string $action,
        string $module,
        string $description,
        ?Model $subject = null,
        ?array $changes = null
    ): void {
        try {
            ActivityLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? $fallbackName ?? 'System',
                'role_name' => $user?->role?->name ?? ($user && $user->role_id === null ? 'Super Admin' : null),
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'subject_type' => $subject ? $subject::class : null,
                'subject_id' => $subject?->getKey(),
                'changes' => $changes,
                'ip_address' => Request::ip(),
                'user_agent' => substr((string) Request::userAgent(), 0, 255),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
