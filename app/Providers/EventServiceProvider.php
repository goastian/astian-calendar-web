<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        \App\Events\StoreCalendarEvent::class => [
            \App\Listener\StoreCalendarListener::class,
        ],

        \App\Events\UpdateCalendarEvent::class => [
            \App\Listener\UpdateCalendarListener::class,
        ],

        \App\Events\DestroyCalendarEvent::class => [
            \App\Listener\DestroyCalendarListener::class,
        ],

        \App\Events\StoreUserEvent::class => [
            \App\Listener\StoreUserListener::class,
        ],

        \App\Events\UpdateUserEvent::class => [
            \App\Listener\UpdateUserListener::class,
        ],

        \App\Events\DestroyUserEvent::class => [
            \App\Listener\DestroyUserListener::class,
        ],

    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
