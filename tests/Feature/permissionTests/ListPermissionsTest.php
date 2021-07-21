<?php

namespace Tests\Unit;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ListPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
        Artisan::call('db:seed --class=PermissionsSeeder');
        Artisan::call('db:seed --class=RolesSeeder');
    }

    /** @test */
    public function a_list_of_permisions_as_admin()
    {

        $this->user->assignRole('admin');
        $this->user->refresh();

        $this->actingAs($this->user)
            ->get('/permissions')
            ->assertStatus(200);

    }

    /** @test */
    public function a_list_of_permisions_as_user_not_allowed()
    {

        $this->user->assignRole('user');
        $this->user->refresh();

        $this->actingAs($this->user)
            ->get('/permissions')
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

