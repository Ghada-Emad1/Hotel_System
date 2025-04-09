<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
abstract class Controller
{

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
}
