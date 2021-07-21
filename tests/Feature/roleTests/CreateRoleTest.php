<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;


class CreateRoleTest extends TestCase
{

    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');
    }

    /** @test */
    public function a_crate_role_as_admin()
    {
        $this->user->assignRole('admin');
        $this->user->refresh();

        $response = $this->actingAs($this->user)
            ->post('/roles', $this->input_data())
            ->assertStatus(302);
        $response->assertRedirect('/roles');

    }

    /** @test */
    public function a_crate_role_as_user()
    {

        $this->user->assignRole('user');
        $this->user->refresh();

        $this->actingAs($this->user)
            ->post('/roles', $this->input_data())
            ->assertStatus(403);
    }

    /** @test */
    public function a_valid_input()
    {
        $this->user->assignRole('admin');
        $this->user->refresh();

        $respons = $this->actingAs($this->user)
            ->post('/roles', $this->input_data())
            ->assertStatus(302);

        $respons->assertSessionHasNoErrors();
    }

    /** @test */
    public function a_invalid_input()
    {

        $this->user->assignRole('admin');
        $this->user->refresh();

        $response = $this->actingAs($this->user)
            ->post('/roles', [])
            ->assertStatus(302);

        $response->assertSessionHasErrors('name');
    }

    protected function input_data() {
        return [
            'name' => 'test',
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



