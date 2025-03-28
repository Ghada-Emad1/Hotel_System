<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     * Retrieves the list of available countries for selection.
     */
    public function create(): Response
    {
        // Define allowed countries
        $countries = ['Egypt', 'Saudi Arabia', 'US', 'UAE', 'UK', 'Australia', 'Others', 'Japan', 'Canada'];

        return Inertia::render('auth/Register', [
            'countries' => $countries,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Define allowed countries again for validation
        $allowedCountries = ['Egypt', 'Saudi Arabia', 'US', 'UAE', 'UK', 'Australia', 'Others'];

        // Validate the user input (All fields are required)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar_image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'country' => ['required', 'string', 'in:' . implode(',', $allowedCountries)], // Ensure country is allowed
            'gender' => ['required', 'in:Male,Female'],
        ]);

        // Handle avatar image upload
        $avatarPath = $request->file('avatar_image')->store('avatars', 'public');

        // Create a new user with pending approval status
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar_image' => $avatarPath,
            'country' => $request->country,
            'gender' => $request->gender,
            'is_approved' => false,
            'role' => 'client',
        ]);

        event(new Registered($user));
        Auth::login($user);
        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
    }
}
