<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class CollectiblesCreateForm extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector(): string
    {
        return '#collectible-form';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser): void
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements(): array
    {
        return [
            '@name' => 'input[name="name"]',
            '@add-category-field' => '.category-fields a.add-field',
            '@add-item-field' => '.item-fields a.add-field',
            '@submit' => 'button[type="submit"]',
        ];
    }

    /**
     * @param Browser $browser
     * @param string $name
     */
    public function fillName(Browser $browser, string $name): void
    {
        $browser->type('@name', $name);
    }

    /**
     * @param Browser $browser
     */
    public function submitForm(Browser $browser): void
    {
        $browser->click('@submit');
    }
}
