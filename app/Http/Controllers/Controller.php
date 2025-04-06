<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Notifications\ClientApprovedNotification;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  

public function approveClient($id)
{
    $client = User::findOrFail($id);
    $client->status = 'approved';
    $client->save();

    @dd($client);
    

    // send email to client
    $client->notify((new ClientApprovedNotification()));

    return redirect()->back()->with('success', 'Client approved successfully and email sent.');
}

}
