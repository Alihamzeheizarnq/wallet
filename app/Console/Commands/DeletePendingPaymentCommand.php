<?php

namespace App\Console\Commands;

use App\Jobs\DeletePendingPayment;
use App\Models\Payment;
use Illuminate\Console\Command;

class DeletePendingPaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-pending-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'it removes the payments those have been pending for 24h';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Payment::isPending()
            ->whereDate('created_at', '<', now()->addDay()->toDateTimeString())
            ->chunk(100, function ($items) {
                DeletePendingPayment::dispatch($items->pluck('id'));
            });
    }
}
