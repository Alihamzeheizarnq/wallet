<?php

namespace App\Console\Commands;

use App\Contracts\RateInterface;
use App\Models\Rate;
use Illuminate\Console\Command;

class UpdateRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(RateInterface $rate)
    {
        $rate->formatted()->map(function ($item){
            Rate::create($item);
        });
    }
}
