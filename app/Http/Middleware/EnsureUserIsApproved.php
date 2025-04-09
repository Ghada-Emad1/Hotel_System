<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
           return $next($request);
        }
       

        if (!auth()->user()->is_approved && auth()->user()->hasRole('client')) {
            Auth::logout();

            return redirect()->route('pending.approval')->with('error', 'Your account is pending approval. Please wait for an admin to approve your account.');
        }
        return $next($request);
    }
}