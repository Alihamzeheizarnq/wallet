<?php

namespace App\Services\Rate;

use App\Contracts\RateInterface;
use Illuminate\Support\Collection;


class ExampleRateService implements RateInterface
{

    public function __construct(Collection $config)
    {
    }

    public function getData(?string $currencyKey): Collection
    {
        // TODO: Implement getData() method.
    }

    public function formatted(): Collection
    {
        // TODO: Implement formatted() method.
    }
}
