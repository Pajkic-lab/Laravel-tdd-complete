<?php

namespace Tests\Feature\userTests;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{

    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());

        $key = config('app.JWT_SECRET');
        $payload = array(
            "id" => $this->user->id,
            "exp" => Carbon::now()->addMinutes(15)->timestamp,
        );
        $this->jwt = JWT::encode($payload, $key);
    }

    /** @test */
    public function a_reset_password_sending_jwt()
    {
        $response = $this->post('/password/forgot/reset/' . $this->jwt, $this->new_data())
            ->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->post('/login', array_merge($this->data(),
            ['password' => 'Password']));
        $this->assertAuthenticatedAs($this->user);
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function a_reset_password_sending_jwt_fail_not_matching_passwords()
    {
        $this->post('/password/forgot/reset/' . $this->jwt, array_merge($this->new_data(),
            ['password_confirmation' => 'notvalid']))
            ->assertStatus(302)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_reset_password_sending_jwt_password_to_short()
    {
        $this->post('/password/forgot/reset/' . $this->jwt, array_merge($this->new_data(),
            ['password' => 'pas']))
        ->assertStatus(302)
        ->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_reset_password_sending_jwt_password_does_not_have_1_uppercase()
    {
        $this->post('/password/forgot/reset/' . $this->jwt, array_merge($this->new_data(),
            ['password' => 'qwertyuiop']))
        ->assertStatus(302)
        ->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_reset_password_sending_jwt_password_does_not_have_1_lowercase()
    {
        $this->post('/password/forgot/reset/' . $this->jwt, array_merge($this->new_data(),
            ['password' => 'QWERTYUIOP']))
        ->assertStatus(302)
        ->assertSessionHasErrors('password');
    }

    protected function new_data() {
        return [
            'password' => 'Password',
            'password_confirmation' => 'Password',
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
