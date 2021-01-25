<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\RegisterForm;
use Tests\Browser\Pages\RegisterPage;
use Tests\Browser\Pages\VerifyEmailPage;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @throws \Throwable
     */
    public function testRegisterCreatesUser(): void
    {
        $this->browse(function (Browser $browser) {
            /** @var User $user */
            $user = User::factory()->make();

            $browser->logout()
                    ->visit('/register')
                    ->assertSee(__('auth.title.register'))
                    ->with(new RegisterForm, function ($browser) use ($user) {
                        return $browser->submitForm($user->name, $user->email, 'password');
                    })
                    ->on(new VerifyEmailPage)
                    ->assertSee(__('auth.verify_email_message'));
        });
    }

    /**
     * @throws \Throwable
     */
    public function testRegisterFailsWithMismatchedPassword(): void
    {
        $this->browse(function (Browser $browser) {
            /** @var User $user */
            $user = User::factory()->make();

            $browser->logout()
                    ->visit('/register')
                    ->assertSee(__('auth.title.register'))
                    ->with(new RegisterForm, function ($browser) use ($user) {
                        return $browser->submitForm($user->name, $user->email, 'password', 'other');
                    })
                    ->on(new RegisterPage)
                    ->assertSee(__('auth.password_match'));
        });
    }
}
