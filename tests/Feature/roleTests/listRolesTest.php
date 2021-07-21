<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class listRolesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');
    }

    /** @test */
    public function a_list_of_roles_as_admin()
    {

        $this->user->assignRole('admin');
        $this->user->refresh();

        $this->actingAs($this->user)
        ->get('/roles')
        ->assertStatus(200);
    }

    /** @test */
    public function a_list_of_roles_as_user_not_allowed()
    {

        $this->user->assignRole('user');
        $this->user->refresh();

        $this->actingAs($this->user)
        ->get('/roles')
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
