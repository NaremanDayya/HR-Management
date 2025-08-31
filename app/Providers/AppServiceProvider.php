<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            \Namu\WireChat\Events\MessageCreated::class,
            SendEmailVerificationNotification::class,
        );
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
