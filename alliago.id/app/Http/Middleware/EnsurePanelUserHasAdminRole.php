<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePanelUserHasAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Filament::auth()->user();

        abort_unless($user && ($user->hasRole('admin') || $user->hasRole('staff')), 403);

        return $next($request);
    }
}
