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

class ReceptionistController extends Controller
{
    public function index()
    {
        $receptionist = User::role('receptionist')->get();

        return Inertia::render('Receptionists/Index', [
            'receptionists' => $receptionist,
        ]);
    }

    public function create()
    {
        return Inertia::render('Receptionists/Create');
    }

    public function store(StoreManagerRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // رفع الصورة
        if ($request->hasFile('avatar_image')) {
            $file = $request->file('avatar_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/avatars', $filename);
            $data['avatar_image'] = $filename;
        } else {
            $data['avatar_image'] = 'default.png'; // صورة افتراضية
        }

        $data['role'] = 'receptionist';

        $user = User::create($data);
        $user->assignRole('receptionist');

        return redirect()->route('receptionist.index')->with('success', 'Receptionist created successfully.');
    }

    public function update(UpdateManagerRequest $request, User $receptionist)
    {
        $data = $request->validated();

        unset($data['national_id']);

        if ($request->hasFile('avatar_image')) {
            if ($receptionist->avatar_image && $receptionist->avatar_image !== 'default.png' && Storage::exists('public/avatars/' . $receptionist->avatar_image)) {
                Storage::delete('public/avatars/' . $receptionist->avatar_image);
            }

            $filename = time() . '_' . $request->file('avatar_image')->getClientOriginalName();
            $request->file('avatar_image')->storeAs('public/avatars', $filename);
            $data['avatar_image'] = $filename;
        } else {
            unset($data['avatar_image']);
        }

        $receptionist->update($data);

        return redirect()->route('receptionist.index')->with('success', 'Receptionist updated successfully.');
    }

    public function destroy(User $receptionist)
    {
        if ($receptionist->avatar_image && $receptionist->avatar_image !== 'default.png' && Storage::exists('public/avatars/' . $receptionist->avatar_image)) {
            Storage::delete('public/avatars/' . $receptionist->avatar_image);
        }

        $receptionist->delete();

        return back()->with('success', 'Receptionist deleted successfully.');
    }
    public function ban(User $receptionist)
    {
        $receptionist->update(['is_banned' => true]);

        return back()->with('success', 'Receptionist banned successfully.');
    }

    public function unban(User $receptionist)
    {
        $receptionist->update(['is_banned' => false]);

        return back()->with('success', 'Receptionist unbanned successfully.');
    }

}
