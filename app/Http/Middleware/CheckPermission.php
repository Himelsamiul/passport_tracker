<?php

namespace App\Http\Middleware;

use App\Support\Access;
use Closure;
use Illuminate\Http\Request;

/**
 * Route-level permission guard.
 *
 * Usage: ->middleware('perm:passports.edit')
 * Aborts 403 when the logged-in admin's role lacks the ability.
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $ability)
    {
        if (! Access::can($ability)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
