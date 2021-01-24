<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws \Exception
     */
    public function testForgotPasswordExists(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }

    /**
     * @throws \Exception
     */
    public function testForgotPasswordSendsEmail(): void
    {
        /** @var User $testUser */
        $testUser = User::factory()->create();
        $notificationMock = Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => $testUser->email,
        ]);
        $response->assertSessionHas('status', __(PasswordBroker::RESET_LINK_SENT));

        $notificationMock->assertSentTo($testUser, ResetPasswordNotification::class);
    }

    /**
     * @throws \Exception
     */
    public function testForgotPasswordFailsWithUnknownEmail(): void
    {
        $this->get(route('password.request'));

        $response = $this->post(route('password.email'), [
            'email' => 'unknown@example.com',
        ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHasErrors(['email']);
    }
}
