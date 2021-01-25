<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

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
                    ->assertPresent('@login_link')
                    ->assertPresent('@register_link')
                    ->assertMissing('@logout_link');

            $browser->loginAs($user)
                    ->visit(new HomePage)
                    ->assertMissing('@login_link')
                    ->assertMissing('@register_link')
                    ->assertPresent('@logout_link')
                    ->logout();
        });
    }
}
