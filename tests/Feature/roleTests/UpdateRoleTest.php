<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UpdateRoleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');
    }

    /** @test */
    public function a_update_role_as_admin_new_name()
    {
        $this->user->assignRole('admin');
        $this->user->refresh();

        $role_id = Role::first()->id;

        $response =$this->actingAs($this->user)
            ->patch('/roles/' . $role_id, $this->role_data())
            ->assertStatus(302);
        $response->assertRedirect('/roles');
    }

    /** @test */
    public function a_update_role_as_admin_with_old_name()
    {
        $this->user->assignRole('admin');
        $this->user->refresh();

        $role_id = Role::first()->id;

        $response =$this->actingAs($this->user)
            ->patch('/roles/' . $role_id, array_merge($this->role_data(),
            ['name'=> 'admin']))
            ->assertStatus(302);
        $response->assertRedirect('/roles');
    }

    /** @test */
    public function a_fail_to_update_role_as_admin_with_already_taken_name()
    {
        $this->user->assignRole('admin');
        $this->user->refresh();

        $role_id = Role::first()->id;

        $this->actingAs($this->user)
            ->patch('/roles/' . $role_id, array_merge($this->role_data(),
            ['name'=> 'user']))
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function a_fail_to_update_role_as_admin_with_no_permissions()
    {
        $this->user->assignRole('admin');
        $this->user->refresh();

        $role_id = Role::first()->id;

        $this->actingAs($this->user)
            ->patch('/roles/' . $role_id, array_merge($this->role_data(),
            ['permission_name'=> [] ]))
            ->assertSessionHasErrors(['permission_name']);
    }

    /** @test */
    public function a_fail_to_update_role_as_user()
    {

        $this->user->assignRole('user');
        $this->user->refresh();

        $role_id = Role::first()->id;

        $this->actingAs($this->user)
            ->patch('/roles/' . $role_id, $this->role_data())
            ->assertStatus(403);
    }

    /** @test */
    public function a_fail_to_update_role_as_admin_with_invalid_input()
    {

        $this->user->assignRole('user');
        $this->user->refresh();

        $role_id = Role::first()->id;

        $this->actingAs($this->user)
            ->patch('/roles/' . $role_id, [])
            ->assertStatus(403);
    }

    protected function role_data() {
        return [
            'name' => 'test',
            'guard_name' => 'web',
            'permission_name' => ['create-role'],
        ];
    }

    protected function data() {
        return [
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password'),
        ];
    }

}
