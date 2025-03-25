<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;

class ManagerController extends Controller
{
    public function index()
    {
        $managers = User::role('manager')->get();

        return Inertia::render('Managers/Index', [
            'managers' => $managers,
        ]);
    }

    public function create()
    {
        return Inertia::render('Managers/Create');
    }

    public function store(StoreManagerRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // Upload avatar image
        if ($request->hasFile('avatar_image')) {
            $file = $request->file('avatar_image');
            $filename = time() . '_' . $file->getClientOriginalName();


            $file->storeAs('avatars', $filename, 'public');


            $data['avatar_image'] = $filename;
        } else {
            $data['avatar_image'] = 'avatar.png';
        }

        $data['role'] = 'manager';

        $user = User::create($data);
        $user->assignRole('manager');

        return redirect()->route('manager.index')->with('success', 'Manager created successfully.');
    }


    public function update(UpdateManagerRequest $request, User $manager)
    {
        $data = $request->validated();
        unset($data['national_id']);

        if ($request->hasFile('avatar_image')) {
            if ($manager->avatar_image && $manager->avatar_image !== 'avatar.png' && Storage::exists('public/avatars/' . $manager->avatar_image)) {
                Storage::delete('public/avatars/' . $manager->avatar_image);
            }

            $filename = time() . '_' . $request->file('avatar_image')->getClientOriginalName();
            $request->file('avatar_image')->storeAs('public/avatars', $filename);
            $data['avatar_image'] = $filename;
        }

        $manager->update($data);

        return redirect()->route('manager.index')->with('success', 'Manager updated successfully.');
    }

    public function destroy(User $manager)
    {
        if ($manager->avatar_image && $manager->avatar_image !== 'avatar.png' && Storage::exists('public/avatars/' . $manager->avatar_image)) {
            Storage::delete('public/avatars/' . $manager->avatar_image);
        }

        $manager->delete();

        return back()->with('success', 'Manager deleted successfully.');
    }

}
