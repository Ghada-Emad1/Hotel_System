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
        Permission::create(['name' => 'manage_managers']);
        Permission::create(['name' => 'manage_receptionists']);
        Permission::create(['name' => 'manage_clients']);
        Permission::create(['name' => 'manage_floors']);
        Permission::create(['name' => 'manage_rooms']);
        Permission::create(['name' => 'approve_clients']);
        Permission::create(['name' => 'make_reservations']);
        Permission::create(['name' => 'view_reports']);

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'manage_managers',
            'manage_receptionists',
            'manage_clients',
            'manage_floors',
            'manage_rooms',
            'view_reports',
        ]);

        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'manage_receptionists',
            'manage_floors',
            'manage_rooms',
            'view_reports'
        ]);

        $receptionistRole = Role::create(['name' => 'receptionist']);
        $receptionistRole->givePermissionTo([
            'approve_clients',
        ]);

        $clientRole = Role::create(['name' => 'client']);
        $clientRole->givePermissionTo([
            'make_reservations',
        ]);
    }
}
