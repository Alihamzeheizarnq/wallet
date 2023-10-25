<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\CurrencyActivatedEvent;
use App\Events\CurrencyDeactivatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCurrencyRequest;
use App\Models\Currency;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CurrencyController extends Controller
{
    use ApiResponse;

    /**
     * index
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $currencies = Currency::isActive()->latest()->paginate(20);

        return apiResponse()
            ->data($currencies)
            ->message(__('currency.messages.currency_list_found_successfully'))
            ->send();
    }

    /**
     * store
     *
     * @param StoreCurrencyRequest $request
     * @return JsonResponse
     */
    public function store(StoreCurrencyRequest $request): JsonResponse
    {
        $currency = $request->user()
            ->currencies()
            ->create([
                'name' => $request->name,
                'key' => $request->key,
                'iso_code' => $request->iso_code,
                'symbol' => $request->symbol,
            ]);

        return apiResponse()
            ->data($currency)
            ->message(__('currency.messages.currency_successfully_created'))
            ->send();
    }

    /**
     * activate
     *
     * @param Currency $currency
     * @return JsonResponse
     */
    public function activate(Currency $currency): JsonResponse
    {
        if ($currency->is_active) {
            throw new BadRequestException(
                __('currency.errors.the_onle_active_currencies_can_deactivate')
            );
        }

        $currency->update([
            'is_active' => true,
        ]);

        CurrencyActivatedEvent::dispatch($currency);

        return apiResponse()
            ->data($currency)
            ->message(__('currency.messages.the_currency_was_successfully_activated'))
            ->send();
    }

    /**
     * deactivate
     *
     * @param Currency $currency
     * @return JsonResponse
     */
    public function deactivate(Currency $currency): JsonResponse
    {
        if (!$currency->is_active) {
            throw new BadRequestException(
                __('currency.errors.the_onle_inactive_currencies_can_activate')
            );
        }

        $currency->update([
            'is_active' => false,
        ]);

        CurrencyDeactivatedEvent::dispatch($currency);

        return apiResponse()
            ->data($currency)
            ->message(__('currency.messages.the_currency_was_successfully_diactivated'))
            ->send();
    }
}
