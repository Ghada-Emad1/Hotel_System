<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a manager
        $manager=User::create([
            'name' => 'John Doe',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'), // Use a secure password
            'national_id' => '1234567890',
            'avatar_image' => 'avatars/johndoe.jpg',
            'role' => 'manager', // Set role to 'manager'
            'country' => 'USA',
            'gender' => 'Male',
            'is_banned' => 0,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
        $managerRole= Role::where('name', 'manager')->first();
        $manager->assignRole($managerRole);
    }
}
