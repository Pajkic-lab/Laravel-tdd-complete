<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role_permission = [
            'admin' => [
                'delete-user',
                'edit-user', 'show-user', 'create-users', 'list-users','list-permissions',
                'create-role', 'update-role', 'read-role', 'delete-role', 'log-in', 'log-out'
            ],
            'user' => ['log-in', 'log-out']
        ];

        foreach($role_permission as $role_name => $permissions){
            // create role with name $role_name
            $role = Role::create([
                'name' => $role_name,
                'guard_name' => 'web'
            ]);

            // assing permissions $permissions to role $role_name
            foreach($permissions as $permission){
                $role->givePermissionTo($permission);
            }

        }
    }
}
