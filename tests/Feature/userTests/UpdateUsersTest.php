<?php

namespace Tests\Feature\userTests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');
    }

    /** @test */
    public function a_update_user_as_user_with_permission()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('edit-user');
        $this->user->refresh();

        $user_id = User::first()->id;

        $response =$this->actingAs($this->user)
            ->patch('/users/' . $user_id, $this->user_data())
            ->assertStatus(302);
        $response->assertRedirect('/users');
    }

    /** @test */
    public function a_deny_to_update_user_as_user_with_wrong_permission()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('log-out');
        $this->user->refresh();

        $user_id = User::first()->id;

        $this->actingAs($this->user)
            ->patch('/users/' . $user_id, $this->user_data())
            ->assertStatus(403);
    }

    /** @test */
    public function a_deny_to_update_user_with_empty_name_input()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('edit-user');
        $this->user->refresh();

        $user_id = User::first()->id;

        $response =$this->actingAs($this->user)
            ->patch('/users/' . $user_id, array_merge($this->user_data(),
            ['name'=> '' ]))
            ->assertStatus(302);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_deny_to_update_user_with_not_valide_email_input()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('edit-user');
        $this->user->refresh();

        $user_id = User::first()->id;

        $response =$this->actingAs($this->user)
            ->patch('/users/' . $user_id, array_merge($this->user_data(),
            ['email'=> 'notemail' ]))
            ->assertStatus(302);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_deny_to_update_user_with_not_valide_password_input()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('edit-user');
        $this->user->refresh();

        $user_id = User::first()->id;

        $response =$this->actingAs($this->user)
            ->patch('/users/' . $user_id, array_merge($this->user_data(),
            ['password'=> '' ]))
            ->assertStatus(302);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_deny_to_update_user_with_not_checked_role_name_input()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('edit-user');
        $this->user->refresh();

        $user_id = User::first()->id;

        $response =$this->actingAs($this->user)
            ->patch('/users/' . $user_id, array_merge($this->user_data(),
            ['role_name'=> '' ]))
            ->assertStatus(302);

        $response->assertSessionHasErrors('role_name');
    }

    protected function user_data()
    {
        return [
            'name' => 'Testuser',
            'email' => 'marko@gmail.com',
            'password' => 'Pass.123',
            'role_name' => 'user',
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
