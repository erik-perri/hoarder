<?php

namespace App\Providers;

use App\Models\Collection;
use App\Policies\CollectionPolicy;
use App\Policies\GoalPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Collection::class => CollectionPolicy::class,
        Collection\Goal::class => GoalPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject(__('auth.verify_email.subject'))
                ->line(__('auth.verify_email.line1'))
                ->action(__('auth.verify_email.link'), $url)
                ->line(__('auth.verify_email.line2'));
        });
    }
}
