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
       return collect([
          [
              'key' => 'usd',
              'value' => 56000,
              'date' => '2023-01-01 00:00:00'
          ],
           [
               'key' => 'irr',
               'value' => 40000,
               'date' => '2023-01-01 00:00:00'
           ],
           [
               'key' => 'jpr',
               'value' => 10000,
               'date' => '2023-01-02 00:00:00'
           ],

       ]);
    }

    public function formatted(): Collection
    {
        return $this->getData();
    }
}
