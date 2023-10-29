<?php

namespace App\Services\Rate;

use App\Contracts\RateInterface;
use App\Models\Currency;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class NavasanService implements RateInterface
{
    public PendingRequest $request;

    public function __construct(public Collection $config)
    {
        $this->request = Http::withUrlParameters([
            'endpoint' => $this->config->get('base_url'),
            'version' => 'latest',
            'api_key' => $this->config->get('api_key')
        ]);
    }

    /**
     * get data
     *
     * @param string|null $currencyKey
     * @return Collection
     */
    public function getData(?string $currencyKey = null): Collection
    {
        $cacheTime = now()->addHours($this->config->get('cache_time'));

        $currenciesRates = Cache::remember('currenciesRates', $cacheTime, function () {
            $response = $this->request->get('{+endpoint}/{version}/?api_key={api_key}');

            if ($response->status() !== 200) {
                throw new BadRequestException("There is an internal error");
            }

            return $response->json();
        });

        if ($currencyKey === null) {
            return collect($currenciesRates);
        }

        return collect(collect($currenciesRates)->get($currencyKey));
    }

    /**
     * formatted
     *
     * @return Collection
     */
    public function formatted(): Collection
    {
        $currencies = Currency::isActive()->get();

        $rates = [];

        foreach ($currencies as $currency) {
            $currencyData = $this->getData($currency->key);

            if ($currencyData->isEmpty()) {
                continue;
            }
            $rates = [
                ...$rates, [
                    'currency_key' => $currency->key,
                    'rate' => $currencyData->get('value'),
                    'rate_currency_key' => 'irr',
                    'date' => $currencyData->get('date')
                ]
            ];
        }

        return collect($rates);
    }
}
