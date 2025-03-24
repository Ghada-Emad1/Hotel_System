<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ReceptionistSeed extends Seeder
{
    public function run()
    {
         // Create the default admin user
            $receptionist = User::create([
                'name' => 'Receptionist',
                'email' => 'rece@rece.com',
                'role' => 'receptionist',
                'password' => bcrypt('123456'), // Hash the password
                'national_id' => '0987654321', // Example national ID
                'avatar_image' => 'default.png', // Default avatar
            ]);

            // Assign the 'receptionist' role to the user
            $receptionistRole = Role::where('name', 'receptionist')->first();
            $receptionist->assignRole($receptionistRole);
        
    }
}
