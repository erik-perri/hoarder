<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class LoginForm extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector(): string
    {
        return 'form[action="'.route('login').'"]';
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
            '@email' => 'input[name="email"]',
            '@password' => 'input[name="password"]',
            '@remember-me' => 'input[name="remember"]',
            '@submit' => 'button[type="submit"]',
        ];
    }

    /**
     * @param Browser $browser
     * @param string $email
     * @param string $password
     * @param bool $remember
     */
    public function submitForm(Browser $browser, string $email, string $password, bool $remember = false): void
    {
        $browser->type('@email', $email)
                ->type('@password', $password);

        if ($remember) {
            $browser->click('@remember-me');
        }

        $browser->click('@submit');
    }
}
