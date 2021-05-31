<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\CollectiblesCreateForm;
use Tests\Browser\Pages\CollectiblesPage;
use Tests\DuskTestCase;

/**
 * @group Collectibles
 */
class CollectiblesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @throws \Throwable
     */
    public function testSeesExpectedLinks(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->visit(new CollectiblesPage)
                    ->assertMissing('@create-link');

            $browser->loginAs($user)
                    ->visit(new CollectiblesPage)
                    ->assertPresent('@create-link')
                    ->logout();
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanCreateCollectible(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                    ->visit(new CollectiblesPage)
                    ->click('@create-link')
                    ->waitForText(__('collectible.title.create'))
                    ->with(new CollectiblesCreateForm, static function ($browser) {
                        $browser->fillName('Test Collectible');
                        // TODO Set fields
                        $browser->submitForm();
                    })
                    ->waitForText(__('collectible.messages.create_success'))
                    ->assertVisible('@edit-link')
                    ->click('@edit-link')
                    ->waitForText(__('collectible.title.edit'));
            // TODO Validate fields
        });
    }
}
