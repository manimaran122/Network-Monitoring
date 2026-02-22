<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'edit articles',
            'delete articles',
            'publish articles',
            'unpublish articles',
            'manage roles',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // create roles and assign generic permissions - just as an example
        $role = \Spatie\Permission\Models\Role::create(['name' => 'writer']);
        $role->givePermissionTo('edit articles');

        $role = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $role->givePermissionTo(\Spatie\Permission\Models\Permission::all());
    }
}
