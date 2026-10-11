<?php

namespace App\Support;

use App\Models\Registration;
use App\Models\User;

/**
 * Central role/permission resolver for the admin panel.
 *
 * Ability format: "<module>.<action>" e.g. "passports.edit", "reports.view".
 * - Users with role_id = null are legacy super admins (full access).
 * - Users with a role: the ability must exist in the role's permissions JSON.
 * - Public-registration logins get view-only access.
 */
class Access
{
    /** Module => allowed actions. Single source of truth for the whole system. */
    public const MODULES = [
        'dashboard'     => ['view'],
        'agents'        => ['view', 'create', 'edit', 'delete'],
        'passports'     => ['view', 'create', 'edit', 'delete'],
        'employees'     => ['view', 'create', 'edit', 'delete'],
        'categories'    => ['view', 'create', 'edit', 'delete'],
        'agencies'      => ['view', 'create', 'edit', 'delete'],
        'officers'      => ['view', 'create', 'edit', 'delete'],
        'processings'   => ['view', 'create', 'edit', 'delete'],
        'collections'   => ['view', 'create', 'delete'],
        'registrations' => ['view', 'create', 'delete'],
        'reports'       => ['view'],
        'audit'         => ['view'],
    ];

    public const MODULE_LABELS = [
        'dashboard'     => 'Dashboard',
        'agents'        => 'Agents',
        'passports'     => 'Passports',
        'employees'     => 'Employees',
        'categories'    => 'Categories',
        'agencies'      => 'Agencies',
        'officers'      => 'Officers',
        'processings'   => 'Processing',
        'collections'   => 'Collections',
        'registrations' => 'Registrations',
        'reports'       => 'Reports',
        'audit'         => 'Track Record',
    ];

    public const ACTION_LABELS = [
        'view'   => 'View',
        'create' => 'Create',
        'edit'   => 'Edit',
        'delete' => 'Delete',
    ];

    public static function admin(): ?User
    {
        $sessionUser = session('user');

        if (! $sessionUser instanceof User) {
            return null;
        }

        // Fetch fresh so role changes take effect immediately.
        return User::find($sessionUser->id);
    }

    public static function can(string $ability): bool
    {
        // Public-registration logins are view-only.
        if (session('user') instanceof Registration) {
            return str_ends_with($ability, '.view');
        }

        $admin = self::admin();

        if (! $admin) {
            return false;
        }

        // Legacy/super admins (no role) have full access.
        if ($admin->role_id === null) {
            return true;
        }

        return in_array($ability, $admin->role?->permissions ?? [], true);
    }
}
