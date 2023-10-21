<?php

namespace App\Listeners;

use App\Events\PaymentApprovedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateUserBlanace
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
    public function handle(PaymentApprovedEvent $event): void
    {
        $event
            ->payment
            ->user->updateBalance();
    }
}
