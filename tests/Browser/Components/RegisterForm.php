<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class RegisterForm extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector(): string
    {
        return 'form[action="'.route('register').'"]';
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
            '@email' => 'input[name="email"]',
            '@password' => 'input[name="password"]',
            '@password-confirmation' => 'input[name="password_confirmation"]',
            '@submit' => 'button[type="submit"]',
        ];
    }

    /**
     * @param Browser $browser
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string|null $passwordConfirmation
     */
    public function submitForm(
        Browser $browser,
        string $name,
        string $email,
        string $password,
        ?string $passwordConfirmation = null
    ): void {
        $browser->type('@name', $name)
                ->type('@email', $email)
                ->type('@password', $password)
                ->type('@password-confirmation', $passwordConfirmation ?? $password);

        $browser->click('@submit');
    }
}
