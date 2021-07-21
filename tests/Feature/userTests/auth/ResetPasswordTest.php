<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{

    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
    }

    /** @test */
    public function a_password_reset()
    {
        $response = $this->actingAs($this->user)
            ->post('/password/reset', $this->new_data())
            ->assertStatus(302);
        $response->assertRedirect('/dashboard');

        $response = $this->post('/login', array_merge($this->data(),
            ['password' => 'Password']));
        $this->assertAuthenticatedAs($this->user);
        $response->assertRedirect('/home');
    }

    /** @test */
    public function a_old_password_is_not_correct()
    {
        $this->actingAs($this->user)
            ->post('/password/reset', array_merge($this->new_data(),
                ['oldPassword' => 'notvalid']))
            ->assertStatus(400);
    }

    /** @test */
    public function a_password_must_be_8_characters_long()
    {
        $response = $this->actingAs($this->user)
        ->post('/password/reset', array_merge($this->new_data(),
            ['password' => 'pas']))
        ->assertStatus(302);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_password_must_have_1_uppercase_letter()
    {
        $response = $this->actingAs($this->user)
        ->post('/password/reset', array_merge($this->new_data(),
            ['password' => 'qwertyuiop']))
        ->assertStatus(302);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_password_must_have_1_lowercase_letter()
    {
        $response = $this->actingAs($this->user)
        ->post('/password/reset', array_merge($this->new_data(),
            ['password' => 'QWERTYUIOP']))
        ->assertStatus(302);

        $response->assertSessionHasErrors('password');
    }

    protected function new_data() {
        return [
            'oldPassword' => 'password',
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
