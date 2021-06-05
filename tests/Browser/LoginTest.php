<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\LoginForm;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;

/**
 * @group Auth
 */
class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @throws \Throwable
     */
    public function testLoginAuthenticatesUsers(): void
    {
        $this->browse(function (Browser $browser) {
            /** @var User $user */
            $user = User::factory()->create();

            $browser->visit(new LoginPage)
                    ->waitFor('#login-form', 2)
                    // TODO Figure out how to re-merge the fill and submit form action. After switching to a SPA it
                    //      started failing with 'stale element reference: element is not attached to the page document'
                    //      in the assert method of LoginForm (presumably due to the form no longer existing after the
                    //      redirect).
                    ->with(new LoginForm, fn ($browser) => $browser->fillForm($user->email, 'password'))
                    ->click('#login-form button[type="submit"]')
                    ->waitForText('Home', 2)
                    ->on(new HomePage)
                    ->assertPresent('@logout-link')
                    ->logout();
        });
    }

    /**
     * @throws \Throwable
     */
    public function testLoginFailsWithInvalidCredentials(): void
    {
        $this->browse(function (Browser $browser) {
            /** @var User $user */
            $user = User::factory()->create();

            $browser->visit(new LoginPage)
                    ->assertSee(__('auth.title.login'))
                    ->with(new LoginForm, fn ($browser) => $browser->fillForm($user->email, 'invalid'))
                    ->click('#login-form button[type="submit"]')
                    // TODO Figure out a better way to handle this. It makes the checks below useless but without it
                    //      the test will fail due to the check happening before Vue has updated the DOM.
                    ->waitForText(__('auth.failed'), 2)
                    ->on(new LoginPage)
                    ->assertSee(__('auth.failed'));
        });
    }
}
