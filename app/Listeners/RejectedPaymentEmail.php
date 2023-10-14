<?php

namespace App\Listeners;

use App\Events\PaymentRejectedEvent;
use App\Mail\notifyRejectedPayment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class RejectedPaymentEmail implements ShouldQueue
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
    public function handle(PaymentRejectedEvent $event): void
    {
        Mail::to(
            User::find(1)
        )->send(
            new notifyRejectedPayment($event->payment)
        );

    }
}
