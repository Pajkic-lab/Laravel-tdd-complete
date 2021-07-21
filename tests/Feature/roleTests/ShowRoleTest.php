<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ShowRoleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');
    }

    /** @test */
    public function a_get_role_as_admin_by_id()
    {
        $this->user->assignRole('admin');
        $this->user->refresh();

        $role_id = Role::first()->id;

        $this->actingAs($this->user)
            ->get('/roles/' . $role_id)
            ->assertStatus(200)
            ->assertViewIs('roles.show')
            ->assertViewHas('role');
    }

    /** @test */
    public function a_deny_to_get_role_as_user_by_id()
    {
        $this->user->assignRole('user');
        $this->user->refresh();

        $role_id = Role::first()->id;

        $this->actingAs($this->user)
            ->get('/roles/' . $role_id)
            ->assertStatus(403);
    }

    protected function data() {
        return [
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password'),
        ];
    }
}
