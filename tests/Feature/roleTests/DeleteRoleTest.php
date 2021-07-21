<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteRoleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');
    }

    /** @test */
    public function a_delete_role_as_admin()
    {

        $this->user->assignRole('admin');
        $this->user->refresh();

        $role_id = Role::first()->id;

        $this->actingAs($this->user)
            ->delete('/roles/' . $role_id)
            ->assertStatus(200);
        $this->assertCount(1, Role::all());
    }

    /** @test */
    public function a_deny_role_delete_without_id_as_admin()
    {

        $this->user->assignRole('admin');
        $this->user->refresh();

        $this->actingAs($this->user)
            ->delete('/roles/')
            ->assertStatus(405);
    }

    /** @test */
    public function a_deny_to_delete_role_as_user()
    {

        $this->user->assignRole('user');
        $this->user->refresh();

        $role_id = Role::first()->id;

        $this->actingAs($this->user)
            ->delete('/roles/' . $role_id)
            ->assertStatus(403);

    }

    protected function role_data() {
        return [
            'name' => 'test',
            'guard_name' => 'web',
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
