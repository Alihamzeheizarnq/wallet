<?php

namespace App\Providers;

use App\Contracts\RateInterface;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class RateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RateInterface::class, function () {
            $defaultRateService = config('rate.default');
            $serviceConfig = config("rate.drivers.$defaultRateService");
            $serviceClass = config("rate.drivers.$defaultRateService.service");

            if (!class_exists($serviceClass)) {
                throw new BadRequestException("There is no $defaultRateService service", 500);
            }

            return new $serviceClass(collect($serviceConfig));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
