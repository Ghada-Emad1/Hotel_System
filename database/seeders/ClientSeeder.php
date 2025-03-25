<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Create the default admin user
        $client= User::create([
            'name' => 'Receptionist',
            'email' => 'client@client.com',
            'role' => 'client',
            'password' => bcrypt('123456'), // Hash the password
            'national_id' => '983248726', // Example national ID
            'avatar_image' => 'default.png', // Default avatar
        ]);

        // Assign the 'receptionist' role to the user
        $clienttRole = Role::where('name', 'receptionist')->first();
        $client->assignRole($clienttRole);
    }
}
