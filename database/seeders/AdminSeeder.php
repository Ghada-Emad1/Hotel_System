<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Check if the admin user already exists
        $admin = User::where('email', 'admin@admin.com')->first();

        if (!$admin) {
            // Create the default admin user
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456'), // Hash the password
                'national_id' => '12345678901234', // Example national ID
                'avatar_image' => 'default.png', // Default avatar
            ]);

            // Assign the 'admin' role to the user
            $adminRole = Role::where('name', 'admin')->first();
            $admin->assignRole($adminRole);
        }
    }
}

