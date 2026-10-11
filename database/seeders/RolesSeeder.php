<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Support\Access;
use Illuminate\Database\Seeder;

/**
 * Starter roles. Idempotent — safe to run on every deploy.
 */
class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $all = fn (string $module) => array_map(
            fn ($action) => $module . '.' . $action,
            Access::MODULES[$module]
        );

        $viewOnly = ['dashboard.view', 'reports.view'];
        foreach (Access::MODULES as $module => $actions) {
            $viewOnly[] = $module . '.view';
        }

        Role::firstOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'Manager',
                'description' => 'Full operations access, no admin management.',
                'permissions' => array_values(array_filter(
                    array_merge(
                        ...array_map($all, array_keys(Access::MODULES))
                    ),
                    fn ($ability) => ! str_starts_with($ability, 'dashboard.')
                )),
            ]
        );

        Role::firstOrCreate(
            ['slug' => 'viewer'],
            [
                'name' => 'Viewer',
                'description' => 'Read-only access to every module.',
                'permissions' => array_values(array_unique($viewOnly)),
            ]
        );
    }
}
