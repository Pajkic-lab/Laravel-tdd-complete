<?php

namespace Tests\Feature\changeLanguageTests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ChangeLanguageTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::create($this->data());
    }

    /** @test */
    public function a_langeage_change()
    {
        $response = $this->actingAs($this->user)
             ->post('/lang/change', $this->input_data());

        $response->assertSessionHas('locale', 'sr');
    }

    protected function data()
    {
        return [
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password'),
        ];
    }

    protected function input_data() {
        return [
            'lang' => 'sr'
        ];
    }
}
