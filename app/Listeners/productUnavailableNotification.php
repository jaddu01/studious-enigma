<?php

namespace App\Listeners;
use App\Events\ProductUnavailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class productUnavailableNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ProductUnavailable $event)
    {
        dd($event);
    }
}
