<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class ClientApprovalController extends Controller
{
    public function pending()
    {
        $pendingClients = User::where('role', 'client')
            ->whereDoesntHave('approval')
            ->select(['id', 'name', 'email', 'national_id', 'country', 'gender', 'created_at','is_approved'])
            ->latest()
            ->get();
            dd($pendingClients);

        return Inertia::render('Receptionist/Clients/Pending', [
            'clients' => $pendingClients
        ]);
    }

    public function approve(User $client)
    {
        $client->approvals()->create([
            'approved_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Client approved successfully');
    }
}
