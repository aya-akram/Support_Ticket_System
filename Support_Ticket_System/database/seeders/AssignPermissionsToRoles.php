<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToRoles extends Seeder
{
    public function run()
    {
        $roleClient = Role::where('name', 'client')->first();
        $roleStaff = Role::where('name', 'staff')->first();

        $permissionViewTickets = Permission::where('name', 'view tickets')->first();

        $roleClient->givePermissionTo($permissionViewTickets);
        $roleStaff->givePermissionTo($permissionViewTickets);
    }
}



