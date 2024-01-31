<?php

namespace App\Listener;

use App\Events\DestroyCalendarEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyCalendarListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DestroyCalendarEvent $event): void
    {
        //
    }
}
