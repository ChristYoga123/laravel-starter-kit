<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $admin = Role::findOrCreate('Admin', 'admin');

        // Create Permissions
        $this->createPermissions('admins', 'admin');
        $this->createPermissions('roles', 'admin');

        // Assign Permissions to Roles
        $admin->givePermissionTo(Permission::all());
    }

    private function createPermissions($resources, $guard)
    {
        Permission::findOrCreate("{$resources}.{$guard}.index", $guard);
        Permission::findOrCreate("{$resources}.{$guard}.show", $guard);
        Permission::findOrCreate("{$resources}.{$guard}.create", $guard);
        Permission::findOrCreate("{$resources}.{$guard}.store", $guard);
        Permission::findOrCreate("{$resources}.{$guard}.edit", $guard);
        Permission::findOrCreate("{$resources}.{$guard}.update", $guard);
        Permission::findOrCreate("{$resources}.{$guard}.destroy", $guard);
    }
}
