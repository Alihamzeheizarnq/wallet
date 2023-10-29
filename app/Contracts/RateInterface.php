<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface RateInterface
{
    public function __construct(Collection $config);
    public function getData(?string $currencyKey): Collection;
    public function formatted(): Collection;
}
