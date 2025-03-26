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

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::role('client')->get();

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
        ]);
    }

    public function create()
    {
        return Inertia::render('Clients/Create');
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

        $data['role'] = 'client';

        
        $user = User::create($data);
        $user->assignRole('client');

        return redirect()->route('client.index')->with('success', 'Client created successfully.');
    }

    public function update(UpdateManagerRequest $request, User $client)
    {
        $data = $request->validated();

        unset($data['national_id']);

        if ($request->hasFile('avatar_image')) {
            if ($client->avatar_image && $client->avatar_image !== 'default.png' && Storage::exists('public/avatars/' . $client->avatar_image)) {
                Storage::delete('public/avatars/' . $client->avatar_image);
            }

            $filename = time() . '_' . $request->file('avatar_image')->getClientOriginalName();
            $request->file('avatar_image')->storeAs('public/avatars', $filename);
            $data['avatar_image'] = $filename;
        } else {
            unset($data['avatar_image']);
        }

        $client->update($data);

        return redirect()->route('client.index')->with('success', 'Receptionist updated successfully.');
    }

    public function destroy(User $client)
    {
        if ($client->avatar_image && $client->avatar_image !== 'default.png' && Storage::exists('public/avatars/' . $client->avatar_image)) {
            Storage::delete('public/avatars/' . $client->avatar_image);
        }

        $client->delete();

        return back()->with('success', 'Receptionist deleted successfully.');
    }
       
    public function pending()
    {
        $pendingClients = User::role('client')
            ->where('is_approved', null) // Fetch clients who are not approved
            ->select(['id', 'name', 'email', 'national_id', 'country', 'gender', 'created_at'])
            ->latest()
            ->get();
    
        return Inertia::render('Receptionists/ManageClients', [
            'clients' => $pendingClients,
        ]);
    }

    public function approve(User $user)
    {
        if (!$user->hasRole('client')) {
            return back()->with('error', 'Only clients can be approved.');
        }

        // Approve the client
        $user->update(['is_approved' => true]);

        return back()->with('success', 'Client approved successfully.');
    }
}
