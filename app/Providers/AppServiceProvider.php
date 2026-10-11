<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Render paginator links() with Bootstrap 5 markup so the admin theme styles them.
        Paginator::useBootstrapFive();

        // Audit trail: auto-log create/update/delete for every tracked model.
        foreach ([
            \App\Models\Agent::class,
            \App\Models\Passport::class,
            \App\Models\Employee::class,
            \App\Models\Category::class,
            \App\Models\Agency::class,
            \App\Models\PassportOfficer::class,
            \App\Models\PassportProcessing::class,
            \App\Models\PassportCollection::class,
            \App\Models\Role::class,
            \App\Models\User::class,
            \App\Models\Registration::class,
        ] as $model) {
            $model::observe(\App\Observers\AuditObserver::class);
        }
    }
}
