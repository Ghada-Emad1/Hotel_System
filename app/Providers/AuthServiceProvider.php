<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Authenticated;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        //Protecting the application from unauthorized users
        Event::listen(Authenticated::class, function ($event) {
            if ($event->user->status === 'pending') {
                Auth::logout();
                abort(403, 'Your account is pending approval.');
            }
        });
    }
}
