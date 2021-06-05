<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

/**
 * @group Home
 */
class HomeTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @throws \Throwable
     */
    public function testSeesExpectedLinks(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->visit(new HomePage)
                    ->assertPresent('@login-link')
                    ->assertPresent('@register-link')
                    ->assertMissing('@logout-link');

            $browser->loginAs($user)
                    ->visit(new HomePage)
                    ->waitFor('@logout-link', 2)
                    ->assertMissing('@login-link')
                    ->assertMissing('@register-link')
                    ->assertPresent('@logout-link')
                    ->logout();
        });
    }
}
