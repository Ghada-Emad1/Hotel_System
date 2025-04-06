<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
class MyApprovedClientController extends Controller
{
    public function index()
    {
        $clients = User::where('role', 'client')
            ->where('is_approved', true) // Changed from null to true
            ->with('approval', 'approval.approver:id,name')
            ->paginate(10);

        return Inertia::render('Clients/MyApprovedClient', [ // You might want to rename this view
            'clients' => $clients,
            'isAdmin' => auth()->user()->hasRole('admin'),
            'isManager' => auth()->user()->hasRole('manager'),
        ]);
    }

    // public function pending()
    // {
    //     $pendingClients = User::role('client')
    //         ->where('is_approved', false) // Fetch clients who are not approved
    //         ->select(['id', 'name', 'email', 'national_id', 'country', 'gender', 'created_at'])
    //         ->latest()
    //         ->get();

    //     return Inertia::render('Clients/PendingClient', [
    //         'clients' => $pendingClients,
    //     ]);
    // }


    // public function approve(User $client)
    // {
    //     // Ensure the user is a client
    //     if (!$client->hasRole('client')) {
    //         return back()->with('error', 'Only clients can be approved.');
    //     }

    //     // Approve the client
    //     $client->update(['is_approved' => true]);

    //     return redirect()->route('receptionist.approve_clients.index')->with('success', 'Client updated successfully.'); // Fixed route name

    // }
}
