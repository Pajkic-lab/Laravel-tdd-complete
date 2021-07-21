<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_predefined_roles_exist()
    {
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');

        $roles = Role::all();
        $this->assertNotEmpty($roles);
    }
}


