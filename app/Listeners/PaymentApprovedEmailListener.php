<?php

namespace App\Listeners;

use App\Events\PaymentApproved;
use App\Mail\NotifyApprovedPayment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PaymentApprovedEmailListener implements ShouldQueue
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
    public function handle(PaymentApproved $event): void
    {
        Mail::to(
            User::find($event->payment->user_id)
        )->send(
            new NotifyApprovedPayment($event->payment)
        );
    }
}
