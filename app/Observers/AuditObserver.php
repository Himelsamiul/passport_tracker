<?php

namespace App\Observers;

use App\Support\Audit;
use Illuminate\Database\Eloquent\Model;

/*
 * ফিচার নোট (অডিট অবজার্ভার):
 * • রেজিস্টার্ড মডেলের created / updated / deleted ইভেন্টে অটো লগ পাঠায়।
 * • Update-এ শুধু বদলানো ফিল্ডগুলো old → new সহ changes JSON-এ সেভ হয়।
 * • মডিউল নাম মডেল ক্লাস থেকে ম্যাপ হয় (Agent → agents ইত্যাদি)।
 */

class AuditObserver
{
    /** Model class short name → audit module slug. */
    public const MODULE_MAP = [
        'Agent' => 'agents',
        'Passport' => 'passports',
        'Employee' => 'employees',
        'Category' => 'categories',
        'Agency' => 'agencies',
        'PassportOfficer' => 'officers',
        'PassportProcessing' => 'processings',
        'PassportCollection' => 'collections',
        'Role' => 'roles',
        'User' => 'admin_users',
        'Registration' => 'registrations',
    ];

    public function created(Model $model): void
    {
        Audit::logFor($this->admin(), $this->fallback(), 'created', $this->module($model), $this->label($model) . ' created', $model);
    }

    public function updated(Model $model): void
    {
        $changes = [];
        foreach ($model->getChanges() as $field => $new) {
            if (in_array($field, ['updated_at', 'created_at'], true)) {
                continue;
            }
            $changes[$field] = [
                'old' => $model->getOriginal($field),
                'new' => $new,
            ];
        }

        // Touch-only updates are noise — skip them.
        if ($changes === []) {
            return;
        }

        Audit::logFor(
            $this->admin(),
            $this->fallback(),
            'updated',
            $this->module($model),
            $this->label($model) . ' updated — fields: ' . implode(', ', array_keys($changes)),
            $model,
            $changes
        );
    }

    public function deleted(Model $model): void
    {
        Audit::logFor($this->admin(), $this->fallback(), 'deleted', $this->module($model), $this->label($model) . ' deleted', $model);
    }

    /** Current admin from the session (fresh DB read), or null for CLI/System. */
    private function admin(): ?\App\Models\User
    {
        $u = session('user');

        return $u instanceof \App\Models\User ? \App\Models\User::find($u->id) : null;
    }

    /** Public registration logins (view-only) are still named in the log. */
    private function fallback(): ?string
    {
        $u = session('user');

        return $u instanceof \App\Models\Registration ? $u->name : null;
    }

    private function module(Model $model): string
    {
        return self::MODULE_MAP[class_basename($model)] ?? strtolower(class_basename($model));
    }

    private function label(Model $model): string
    {
        $name = class_basename($model);
        $key = $model->getKey() ?? '—';
        $identity = $model->name ?? $model->applicant_name ?? $model->passport_number ?? null;

        return $identity ? "{$name} #{$key} ({$identity})" : "{$name} #{$key}";
    }
}
