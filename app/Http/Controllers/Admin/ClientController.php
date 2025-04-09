<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\User;
use App\Models\ClientApproval;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
class ClientController extends Controller
{
    use ImageUploadTrait;
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);

        $query = User::where('role', 'client')->with('approval', 'approval.approver:id,name');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        // Apply country filter
        if ($request->has('country') && $request->country) {
            $query->where('country', $request->country);
        }

        // Apply gender filter
        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }

        $clients = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
            'isAdmin' => auth()->user()->hasRole('admin'),
            'isManager' => auth()->user()->hasRole('manager'),
            'filters' => $request->only(['search', 'country', 'gender']), // Pass filters to the view
            'countries' => $this->countries, // Pass countries to the view
        ]);
    }

    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();

        // Validate country against the JSON list
        if (!in_array($data['country'], $this->countries)) {
            return back()->withErrors(['country' => 'The selected country is invalid.'])->withInput();
        }

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'client';
        $data['avatar_image'] = $this->uploadImage($request->file('avatar_image'), 'avatars', null, 'avatar.png');
        $user = User::create($data);
        $user->assignRole('client');

        return redirect()->route('receptionist_client.index')->with('success', 'Client added successfully.');
    }

    public function edit(User $client)
    {
        return Inertia::render('Clients/Edit', ['client' => $client]);
    }

    public function update(UpdateClientRequest $request, User $client)
    {
        $data = $request->validated();

        // Validate country against the JSON list
        if (!in_array($data['country'], $this->countries)) {
            return back()->withErrors(['country' => 'The selected country is invalid.'])->withInput();
        }
        $data['avatar_image'] = $this->uploadImage($request->file('avatar_image'), 'avatars', $client->avatar_image, 'avatar.png');

        $client->update($data);

        return redirect()->route('receptionist_client.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(User $client)
    {
        $client->delete();
        return back()->with('success', 'Client deleted successfully.');
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


    public function approve(User $client)
    {
        // Ensure the user is a client
        if (!$client->hasRole('client')) {
            return back()->with('error', 'Only clients can be approved.');
        }

        // Approve the client
        $client->update(['is_approved' => true]);

        return redirect()->route('receptionist_client.index')->with('success', 'Client updated successfully.'); // Fixed route name

    }



    // public function reject(User $client)
    // {
    //     ClientApproval::where('client_id', $client->id)->delete();

    //     return back()->with('success', 'Client rejected successfully.');
    // }
}
