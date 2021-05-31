<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class CollectiblesPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url(): string
    {
        return route('collectibles.index', [], false);
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements(): array
    {
        return [
            '@create-link' => 'a[href="'.route('collectibles.create').'"]',
            '@edit-link' => 'a[href$="/edit"]',
        ];
    }
}
