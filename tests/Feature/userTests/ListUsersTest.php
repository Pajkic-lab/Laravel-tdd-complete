<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class listUsers extends TestCase
{

    use RefreshDatabase;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::create($this->data());
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');
    }

    /** @test */
    public function a_list_of_users_as_user_with_permission()
    {
        $this->user->givePermissionTo('list-users');
        $this->user->refresh();

        $this->actingAs($this->user)
            ->get('/users')
            ->assertStatus(200);
    }

    /** @test */
    public function a_deny_to_list_of_users_as_user_with_not_valid_permission()
    {
        $this->user->givePermissionTo('log-out');
        $this->user->refresh();

        $this->actingAs($this->user)
            ->get('/users')
            ->assertStatus(403);
    }

    protected function data()
    {
        return [
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password'),
        ];
    }
}
