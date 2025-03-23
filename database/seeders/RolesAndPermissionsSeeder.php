<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'manage managers']);
        Permission::create(['name' => 'manage receptionists']);
        Permission::create(['name' => 'manage clients']);
        Permission::create(['name' => 'manage floors']);
        Permission::create(['name' => 'manage rooms']);
        Permission::create(['name' => 'approve clients']);
        Permission::create(['name' => 'make reservations']);

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'manage managers',
            'manage receptionists',
            'manage clients',
            'manage floors',
            'manage rooms',
        ]);

        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'manage receptionists',
            'manage floors',
            'manage rooms',
        ]);

        $receptionistRole = Role::create(['name' => 'receptionist']);
        $receptionistRole->givePermissionTo([
            'approve clients',
        ]);

        $clientRole = Role::create(['name' => 'client']);
        $clientRole->givePermissionTo([
            'make reservations',
        ]);
    }
}
