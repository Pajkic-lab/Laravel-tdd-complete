<?php

namespace Tests\Unit;

use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

// use Spatie\Permission\Models\Permission;


class PermissionsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_list_of_permissions_exist()
    {
        Artisan::call('db:seed --class=PermissionsSeeder');

        $this->withExceptionHandling();
        $permissions = Permission::all();
        $this->assertNotEmpty($permissions);
    }

}
