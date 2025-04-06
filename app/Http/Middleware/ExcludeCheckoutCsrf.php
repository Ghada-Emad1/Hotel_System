<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class ExcludeCheckoutCsrf extends Middleware
{
    protected $except = [
        '/client/create-checkout-session', // Exclude this route from CSRF
    ];
}