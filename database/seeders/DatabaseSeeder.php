<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Client;
use App\Models\Floor;
use App\Models\Reservation;
use App\Models\Room;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(AdminSeeder::class);
        
    }
}
