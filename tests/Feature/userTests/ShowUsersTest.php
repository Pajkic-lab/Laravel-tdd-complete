<?php

namespace Tests\Feature\userTests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class showUsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');
    }

    /** @test */
    public function a_show_user_as_user_with_permission()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('show-user');
        $this->user->refresh();

        $user_id = User::first()->id;

        $this->actingAs($this->user)
            ->get('/users/' . $user_id)
            ->assertStatus(200)
            ->assertViewIs('users.show')
            ->assertViewHas('user');
    }

    /** @test */
    public function a_deny_to_show_user_as_user_with_no_permission()
    {
        $this->user->assignRole('user');
        $this->user->givePermissionTo('log-out');
        $this->user->refresh();

        $user_id = User::first()->id;

        $this->actingAs($this->user)
            ->get('/users/' . $user_id)
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
