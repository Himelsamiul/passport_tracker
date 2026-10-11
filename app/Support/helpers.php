<?php

use App\Support\Access;

if (! function_exists('can_do')) {
    /**
     * Blade-friendly permission check: can_do('passports.edit').
     * Same source of truth as the 'perm' middleware and sidebar.
     */
    function can_do(string $ability): bool
    {
        return Access::can($ability);
    }
}
