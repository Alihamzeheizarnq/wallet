<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Api\V1\CurrencyControllerDoc;
use App\Enum\Approvals\CurrencyApproval;
use App\Events\CurrencyActivated;
use App\Events\CurrencyDeactivated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Resources\CurrencyCollection;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CurrencyController extends Controller implements CurrencyControllerDoc
{

    /**
     * index
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $currencies = Currency::isActive()->latest()->paginate(config('app.pre_page'));

        return apiResponse()
            ->data(new CurrencyCollection($currencies))
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

        activity()
            ->causedBy(auth()->user())
            ->performedOn($currency)
            ->log(CurrencyApproval::CREATED->value);

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

        CurrencyActivated::dispatch($currency);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($currency)
            ->log(CurrencyApproval::ACTIVATED->value);

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

        CurrencyDeactivated::dispatch($currency);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($currency)
            ->log(CurrencyApproval::DEACTIVATED->value);

        return apiResponse()
            ->data($currency)
            ->message(__('currency.messages.the_currency_was_successfully_diactivated'))
            ->send();
    }
}
