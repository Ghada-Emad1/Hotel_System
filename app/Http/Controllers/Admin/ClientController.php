<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\User;
use App\Models\ClientApproval;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::where('role', 'client')
            ->with('approval', 'approval.approver:id,name')
            ->paginate(10);

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
            'isAdmin' => auth()->user()->hasRole('admin'),
            'isManager' => auth()->user()->hasRole('manager'),
        ]);
    }

    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'client';

        $client = User::create($data);
        $client->assignRole('client');

        return redirect()->route('clients.index')->with('success', 'Client added successfully.');
    }

    public function edit(User $client)
    {
        return Inertia::render('Clients/Edit', ['client' => $client]);
    }

    public function update(UpdateClientRequest $request, User $client)
    {
        $data = $request->validated();
        $client->update($data);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(User $client)
    {
        $client->delete();
        return back()->with('success', 'Client deleted successfully.');
    }

    public function approve(User $client)
    {
        if (!ClientApproval::where('client_id', $client->id)->exists()) {
            ClientApproval::create([
                'client_id' => $client->id,
                'approved_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Client approved successfully.');
    }

    public function reject(User $client)
    {
        ClientApproval::where('client_id', $client->id)->delete();

        return back()->with('success', 'Client rejected successfully.');
    }
}
