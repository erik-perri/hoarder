<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\LoginForm;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;

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
                    ->assertSee(__('auth.title.login'))
                    ->with(new LoginForm, fn ($browser) => $browser->submitForm($user->email, 'password'))
                    ->on(new HomePage)
                    ->assertPresent('@logout_link')
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
                    ->with(new LoginForm, fn ($browser) => $browser->submitForm($user->email, 'invalid'))
                    ->on(new LoginPage)
                    ->assertSee(__('auth.failed'));
        });
    }
}
