<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyCreatedRequest;
use App\Models\Currency;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

//TODO swagger docs must be completed
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
        //TODO keep it into boot as isActive
        $currencies = Currency::where('is_active',true)->latest()->paginate(20);

        //TODO make it into a service
        return $this->successResponse(
            $currencies,
            __('currency.messages.currency_list_found_successfully')
        );
    }

    /**
     * store
     *
     * @param CurrencyCreatedRequest $request
     * @return JsonResponse
     */
    public function store(CurrencyCreatedRequest $request): JsonResponse
    {
        //TODO rename CurrencyCreatedRequest to StoreCurrencyRequest

        $currency = Currency::create([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            //TODO rename abbr to iso_code
            'abbr' => $request->abbr,
            'symbol' => $request->symbol
        ]);

        return $this->successResponse(
            $currency,
            __('currency.messages.currency_successfully_created')
        );
    }

    /**
     * activate
     *
     * @param Currency $currency
     * @return JsonResponse
     */
    public function activate(Currency $currency): JsonResponse
    {
        //TODO leave a bad request error if $currency is active before
        $currency->update([
            'is_active' => true,
        ]);

        //TODO leave a event class, it will be called when it became active

        return $this->successResponse(
            $currency,
            __('currency.messages.currency_successfully_found')
        );
    }

    //TODO the deactivate action must be completed
}
