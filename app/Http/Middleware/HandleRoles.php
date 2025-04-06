<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Symfony\Component\HttpFoundation\Response;

class HandleRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            Log::info('User logged in:', ['user' => $user->toArray()]);
            Log::info('User roles:', ['roles' => $user->getRoleNames()]);

            try {
                // Check if the user has the role
                if (!$user->hasRole($user->role)) {
                    // Sync the user's role
                    $user->syncRoles([$user->role]);
                }
            } catch (\Exception $e) {
                // Log the error but don't break the application
                Log::error('Error syncing user roles: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
