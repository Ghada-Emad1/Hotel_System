<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use App\Notifications\ClientApprovedNotification;

class ClientPendingController extends Controller
{

    
    // Fetch only pending clients (where is_approved is null or false)
    public function index()
    {
        $clients = User::where('role', 'client')
            ->where('is_approved', false) // Changed from null to true
            ->with('approval', 'approval.approver:id,name')
            ->paginate(10);

        return Inertia::render('Clients/PendingClient', [ // You might want to rename this view
            'clients' => $clients,
            'isAdmin' => auth()->user()->hasRole('admin'),
            'isManager' => auth()->user()->hasRole('manager'),
        ]);
    }
    
    public function pending()
    {
        $pendingClients = User::role('client')
            ->where('is_approved', false) // Fetch clients who are not approved
            ->select(['id', 'name', 'email', 'national_id', 'country', 'gender', 'created_at'])
            ->latest()
            ->get();

        return Inertia::render('Clients/PendingClient', [
            'clients' => $pendingClients,
        ]);
    }


    public function approve(User $client)
    {
        // Ensure the user is a client
        if (!$client->hasRole('client')) {
            return back()->with('error', 'Only clients can be approved.');
        }
        die('test');
        @dd($client);
        // Approve the client
        $client->update(['is_approved' => true]);

        $client->notify(new ClientApprovedNotification());

        return redirect()->route('receptionist_client.index')->with('success', 'Client updated successfully.'); // Fixed route name

    }
}
