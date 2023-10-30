<?php

namespace App\Listeners;

use App\Events\PaymentRejected;
use App\Mail\NotifyRejectedPayment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PaymentRejectedEmailListener implements ShouldQueue
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
    public function handle(PaymentRejected $event): void
    {
        Mail::to(
            User::find($event->payment->user_id)
        )->send(
            new NotifyRejectedPayment($event->payment)
        );

    }
}
