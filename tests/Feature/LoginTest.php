<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginScreenExists(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function testLoginScreenRedirectsWhenLoggedIn(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function testLoginAuthenticatesUsers(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function testLoginFailsWithInvalidCredentials(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function testLoginThrottlesInvalidAttempts(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        // If we don't get the login page first the failed login will redirect to the index
        // and we can't check the for the error
        $this->get('/login');

        /** @var Response $response */
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email']);

        // TODO Figure out how to check the session for the throttle error
//        $response = $this->followRedirects($response);
//
//        $throttleMessage = trans('auth.throttle', ['seconds' => '*', 'minutes' => '*']);
//        $throttleMessagePrefix = preg_replace('/\*.*/', '', $throttleMessage);
//        $response->assertSee($throttleMessagePrefix);
    }

    public function testLogoutRemovesAuthentication(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
