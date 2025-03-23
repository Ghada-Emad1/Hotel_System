<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'auth' => [
                'user' => auth()->user(),
                'can' => [
                    'manage_users' => auth()->user()->can('manage_users'),
                    // Add other permissions as needed
                ],
            ],
        ]);
    }
}
