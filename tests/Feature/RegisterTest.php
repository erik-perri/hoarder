<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterScreenExists(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function testRegisterCreatesUser(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
