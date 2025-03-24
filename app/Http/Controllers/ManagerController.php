<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class ManagerController extends Controller
{
    // List all managers
    public function index()
    {
        $managers = User::where('role', 'manager')->get();
        return Inertia::render('Managers/Index', [
            'managers' => $managers,
        ]);
    }

    // Show the form to create a new manager
    public function create()
    {
        return Inertia::render('Managers/Create');
    }

    // Store a new manager
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'national_id' => 'nullable|string|unique:users',
            'avatar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'country' => 'nullable|string',
            'gender' => ['nullable', Rule::in(['Male', 'Female'])],
        ]);

        // Handle file upload
        $avatarPath = null;
        if ($request->hasFile('avatar_image')) {
            $avatarPath = $request->file('avatar_image')->store('avatars', 'public');
        }

        // Create the manager
        $manager = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'national_id' => $request->national_id,
            'avatar_image' => $avatarPath,
            'role' => 'manager', // Set role to 'manager'
            'country' => $request->country,
            'gender' => $request->gender,
        ]);

        // Assign the 'manager' role using Spatie
        $managerRole = Role::findByName('manager');
        $manager->assignRole($managerRole);

        return redirect()->route('admin.managers.index')->with('success', 'Manager created successfully.');
    }

    // Show the form to edit a manager
    public function edit(User $user)
    {
        return Inertia::render('Managers/Edit', [
            'manager' => $user,
        ]);
    }

    // Update a manager
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'national_id' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'avatar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
            'country' => 'nullable|string',
            'gender' => ['nullable', Rule::in(['Male', 'Female'])],
        ]);

        // Handle file upload
        $avatarPath = $user->avatar_image;
        if ($request->hasFile('avatar_image')) {
            // Delete the old image if it exists
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            // Store the new image
            $avatarPath = $request->file('avatar_image')->store('avatars', 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'national_id' => $request->national_id,
            'avatar_image' => $avatarPath,
            'country' => $request->country,
            'gender' => $request->gender,
        ]);

        return redirect()->route('admin.managers.index')->with('success', 'Manager updated successfully.');
    }

    // Delete a manager
    public function destroy(User $user)
    {
        // Delete the avatar image if it exists
        if ($user->avatar_image && Storage::disk('public')->exists($user->avatar_image)) {
            Storage::disk('public')->delete($user->avatar_image);
        }

        $user->delete();
        return redirect()->route('admin.managers.index')->with('success', 'Manager deleted successfully.');
    }
}
