<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Notifications\ClientApprovedNotification;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected $countries;

    public function __construct()
    {
        $this->countries = $this->loadCountries();
    }

    private function loadCountries()
    {
        $path = resource_path('data/countries.json');
        if (File::exists($path)) {
            $content = File::get($path);
            $countries = json_decode($content, true);

            // Validate JSON structure
            if (json_last_error() === JSON_ERROR_NONE && is_array($countries)) {
                return $countries;
            }
        }

        // Return an empty array if the file is invalid or missing
        return [];
    }




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
