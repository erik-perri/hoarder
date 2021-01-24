<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws \Exception
     */
    public function testResetPasswordExists(): void
    {
        $response = $this->get(route('password.reset', ['token' => 'fake']));

        $response->assertStatus(200);
    }

    /**
     * @throws \Exception
     */
    public function testResetPasswordChangesPassword(): void
    {
        /** @var User $testUser */
        $testUser = User::factory()->create();

        $resetToken = $this->getResetToken($testUser);

        $response = $this->post(route('password.update'), [
            'token' => $resetToken,
            'email' => $testUser->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertSessionHas('status', __('passwords.reset'));
        $response->assertRedirect(route('login'));

        $this->post(route('login'), [
            'email' => $testUser->email,
            'password' => 'new_password',
        ]);
        $this->assertAuthenticated();
    }

    /**
     * @throws \Exception
     */
    public function testResetPasswordFailsWithInvalidInput(): void
    {
        /** @var User $testUser */
        $testUser = User::factory()->create();
        $resetToken = $this->getResetToken($testUser);

        $response = $this->post(route('password.update'), [
            'token' => $resetToken,
            'email' => $testUser->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password_',
        ]);
        $response->assertSessionHasErrors(['password']);

        $response = $this->post(route('password.update'), [
            'token' => 'fake',
            'email' => $testUser->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);
        $response->assertSessionHasErrors(['email']);

        $response = $this->post(route('password.update'), [
            'token' => 'fake',
            'email' => 'fake@example.com',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);
        $response->assertSessionHasErrors(['email']);
    }

    private function getResetToken(User $user): string
    {
        $notificationMock = Notification::fake();

        $this->post(route('password.email'), ['email' => $user->email]);

        $notifications = $notificationMock->sent($user, ResetPassword::class);

        self::assertCount(1, $notifications);

        return $notifications[0]->token;
    }
}
