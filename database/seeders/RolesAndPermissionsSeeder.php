<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // List of permissions
        $permissions = [
            'manage_managers',
            'manage_receptionists',
            'manage_clients',
            'manage_floors',
            'manage_rooms',
            'approve_clients',
            'make_reservations',
            'view_reports',
        ];

        // Ensure permissions exist without duplication
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'manage_managers',
            'manage_receptionists',
            'manage_clients',
            'manage_floors',
            'manage_rooms',
            'view_reports',
        ]);

        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions([
            'manage_receptionists',
            'manage_floors',
            'manage_rooms',
            'manage_client',
            'view_reports',
        ]);

        $receptionistRole = Role::firstOrCreate(['name' => 'receptionist']);
        $receptionistRole->syncPermissions([
            'approve_clients',
        ]);

        $clientRole = Role::firstOrCreate(['name' => 'client']);
        $clientRole->syncPermissions([
            'make_reservations',
        ]);
    }
}
