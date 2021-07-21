<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordMailTest extends TestCase
{

    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
    }

    /** @test */
    public function a_forgot_password()
    {
        $response = $this->post('/password/forgot', ['email' => 'test@gmail.com'])
             ->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_forgot_password_invalid_email()
    {
        $this->post('/password/forgot', ['email' => 'nepostojeci@gmail.com'])
             ->assertStatus(400);
    }

    /** @test */
    public function a_forgot_password_invalid_email_input()
    {
        $this->post('/password/forgot', ['email' => 'nije_email'])
             ->assertStatus(302)
             ->assertSessionHasErrors('email');
    }

    protected function data() {
        return [
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password'),
        ];
    }
}
