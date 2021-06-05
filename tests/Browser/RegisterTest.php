<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\RegisterForm;
use Tests\Browser\Pages\RegisterPage;
use Tests\Browser\Pages\VerifyEmailPage;
use Tests\DuskTestCase;
use Tests\UsesMailhog;

/**
 * @group Auth
 */
class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;
    use UsesMailhog;

    /**
     * @throws \Throwable
     */
    public function testRegisterCreatesUser(): void
    {
        $this->browse(function (Browser $browser) {
            /** @var User $user */
            $user = User::factory()->make();

            $browser->visit(new RegisterPage)
                    ->waitFor('#register-form', 2)
                    ->assertSee(__('auth.title.register'))
                    // TODO Same fixes needed as LoginTest
                    ->with(new RegisterForm, function ($browser) use ($user) {
                        return $browser->fillForm($user->name, $user->email, 'password');
                    })
                    ->click('#register-form button[type="submit"]')
                    ->waitForText(__('auth.verify_email_message'), 2)
                    ->on(new VerifyEmailPage)
                    ->assertSee(__('auth.verify_email_message'));

            $this->assertMailhogHasEmail($user->email, __('auth.verify_email.subject'));
        });
    }

    /*
     * @throws \Throwable
     */
    public function testRegisterFailsWithMismatchedPassword(): void
    {
        $this->browse(function (Browser $browser) {
            /** @var User $user */
            $user = User::factory()->make();

            $browser->visit(new RegisterPage)
                    ->assertSee(__('auth.title.register'))
                    ->with(new RegisterForm, function ($browser) use ($user) {
                        return $browser->fillForm($user->name, $user->email, 'password', 'other');
                    })
                    ->click('#register-form button[type="submit"]')
                    ->waitForText(__('auth.password_match'), 2)
                    ->on(new RegisterPage)
                    ->assertSee(__('auth.password_match'));
        });
    }
}
