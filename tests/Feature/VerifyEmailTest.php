<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws \Exception
     */
    public function testRegisterSendsValidVerificationEmail(): void
    {
        /** @var User $testUser */
        $testUser = User::factory()->make();
        $notificationMock = Notification::fake();

        $this->post(route('register'), [
            'name' => $testUser->name,
            'email' => $testUser->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();

        $createdUser = User::whereEmail($testUser->email)->firstOrFail();
        self::assertNull($createdUser->email_verified_at);

        $notifications = $notificationMock->sent($createdUser, VerifyEmail::class);
        self::assertCount(1, $notifications);

        $notificationInfo = $notifications[0]->toMail($createdUser)->toArray();
        $verifyUrl = $notificationInfo['actionUrl'] ?? null;

        self::assertNotNull($verifyUrl);
        $response = $this->get($verifyUrl);
        $response->assertSessionHas('status', __('auth.email_verified'));

        $createdUser = User::whereEmail($testUser->email)->firstOrFail();

        self::assertNotNull($createdUser->email_verified_at);
    }

    /**
     * @throws \Exception
     */
    public function testCanRequestNewVerificationEmail(): void
    {
        /** @var User $testUser */
        $testUser = User::factory()->create(['email_verified_at' => null]);
        $notificationMock = Notification::fake();

        self::assertNull($testUser->email_verified_at);

        $this->actingAs($testUser)->get(route('verification.notice'));

        $response = $this->post(route('verification.send'));
        $response->assertSessionHas('status', __('auth.verification_link_sent'));

        $notifications = $notificationMock->sent($testUser, VerifyEmail::class);
        self::assertCount(1, $notifications);

        $notificationInfo = $notifications[0]->toMail($testUser)->toArray();
        $verifyUrl = $notificationInfo['actionUrl'] ?? null;
        self::assertNotNull($verifyUrl);

        $response = $this->get($verifyUrl);
        $response->assertSessionHas('status', __('auth.email_verified'));

        $modifiedUser = User::whereEmail($testUser->email)->firstOrFail();

        self::assertNotNull($modifiedUser->email_verified_at);
    }

    /**
     * @throws \Exception
     */
    public function testVerificationRedirectsWhenAlreadyVerified(): void
    {
        /** @var User $testUser */
        $testUser = User::factory()->create(['email_verified_at' => new \DateTime()]);

        $response = $this->actingAs($testUser)->get(route('verification.notice'));
        $response->assertRedirect(RouteServiceProvider::HOME);

        $response = $this->post(route('verification.send'));
        $response->assertRedirect(RouteServiceProvider::HOME);
        $response->assertSessionMissing('status');

        $verificationEmail = (new VerifyEmail())->toMail($testUser)->toArray();

        $response = $this->get($verificationEmail['actionUrl']);
        $response->assertRedirect(RouteServiceProvider::HOME);
        $response->assertSessionHas('status', __('auth.email_verified_already'));
    }
}
