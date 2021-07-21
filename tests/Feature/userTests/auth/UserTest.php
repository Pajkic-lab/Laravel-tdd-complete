<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{

    use RefreshDatabase;
    protected $user;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
    }

    /** @test */
    public function a_valid_login()
    {
        $response = $this->post('/login', $this->plain_data());
        $this->assertAuthenticatedAs($this->user);
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function a_not_valid_email()
    {
        $response = $this->post('/login', array_merge($this->plain_data(),
            ['email' => 'testascas']));

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_empty_password_input()
    {
        $response = $this->post('/login', array_merge($this->plain_data(),
            ['password' => '']));

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_invalid_password()
    {
        $response = $this->post('/login', array_merge($this->plain_data(),
            ['password' => '123']));

        $response->assertSessionHas('status');
    }

    /** @test */
    public function a_valid_logout()
    {
        $this->actingAs($this->user)
            ->post('/logout')
            ->assertStatus(200);
    }

    protected function plain_data() {
        return [
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'password' => 'password',
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

