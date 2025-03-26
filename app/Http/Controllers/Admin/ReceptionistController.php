<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReceptionistRequest;
use App\Http\Requests\UpdateReceptionistRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ReceptionistController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = User::where('role', 'receptionist')->with('manager:id,name');

        return Inertia::render('Receptionists/Index', [
            'receptionists' => $query->paginate(10),
            'isAdmin' => $user->hasRole('admin'),
            'userId' => $user->id
        ]);
    }

    public function store(StoreReceptionistRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'receptionist';

        if (auth()->user()->hasRole('manager')) {
            $data['manager_id'] = auth()->id();
        }

        if ($request->hasFile('avatar_image')) {
            $file = $request->file('avatar_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/avatars', $filename);
            $data['avatar_image'] = $filename;
        } else {
            $data['avatar_image'] = 'avatar.png';
        }

        $receptionist = User::create($data);
        $receptionist->assignRole('receptionist');

        return redirect()->route('receptionist.index')->with('success', 'Receptionist created successfully.');
    }

    public function update(UpdateReceptionistRequest $request, User $receptionist)
    {
        $data = $request->validated();

        if ($request->hasFile('avatar_image')) {
            if ($receptionist->avatar_image && Storage::disk('public')->exists('avatars/' . $receptionist->avatar_image)) {
                Storage::disk('public')->delete('avatars/' . $receptionist->avatar_image);
            }

            $filename = time() . '_' . $request->file('avatar_image')->getClientOriginalName();
            $request->file('avatar_image')->storeAs('public/avatars', $filename);
            $data['avatar_image'] = $filename;
        }

        $receptionist->update($data);

        return redirect()->route('receptionist.index')->with('success', 'Receptionist updated successfully.');
    }

    public function destroy(User $receptionist)
    {
        if ($receptionist->avatar_image && Storage::disk('public')->exists('avatars/' . $receptionist->avatar_image)) {
            Storage::disk('public')->delete('avatars/' . $receptionist->avatar_image);
        }

        $receptionist->delete();

        return back()->with('success', 'Receptionist deleted successfully.');
    }
    public function ban(User $receptionist)
    {
        if (auth()->user()->hasRole('manager') && $receptionist->manager_id !== auth()->id()) {
            return back()->with('error', 'You can only ban receptionists you created.');
        }

        $receptionist->ban();
        return back()->with('success', 'Receptionist banned successfully.');
    }

    public function unban(User $receptionist)
    {
        if (auth()->user()->hasRole('manager') && $receptionist->manager_id !== auth()->id()) {
            return back()->with('error', 'You can only unban receptionists you created.');
        }

        $receptionist->unban();
        return back()->with('success', 'Receptionist unbanned successfully.');
    }




}
