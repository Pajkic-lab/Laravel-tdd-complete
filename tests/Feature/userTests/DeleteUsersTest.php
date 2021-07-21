<?php

namespace Tests\Feature\userTests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DeleteUsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');
    }

    /** @test */
    public function a_delete_role_as_user_with_permission()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('delete-user');
        $this->user->refresh();

        $user_id = $this->user->id;

        $this->actingAs($this->user)
            ->delete('/users/' . $user_id)
            ->assertStatus(200);
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function a_deny_to_delete_role_as_user_without_permission()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('log-out');
        $this->user->refresh();

        $user_id = $this->user->id;

        $this->actingAs($this->user)
            ->delete('/users/'. $user_id)
            ->assertStatus(403);
    }

    /** @test */
    public function a_deny_user_delete_without_id_as_admin()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('delete-user');
        $this->user->refresh();

        $this->actingAs($this->user)
            ->delete('/roles/')
            ->assertStatus(405);
    }

    protected function data() {
        return [
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password'),
        ];
    }
}
