<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            ["name" => "delete-user", "guard_name" => "web"],
            ["name" => "edit-user", "guard_name" => "web"],
            ["name" => "show-user", "guard_name" => "web"],
            ["name" => "create-users", "guard_name" => "web"],
            ["name" => "list-users", "guard_name" => "web"],
            ["name" => "list-permissions", "guard_name" => "web"],
            ["name" => "create-role", "guard_name" => "web"],
            ["name" => "update-role", "guard_name" => "web"],
            ["name" => "read-role", "guard_name" => "web"],
            ["name" => "delete-role", "guard_name" => "web"],
            ["name" => "log-in", "guard_name" => "web"],
            ["name" => "log-out", "guard_name" => "web"],
        ]);
    }
}
