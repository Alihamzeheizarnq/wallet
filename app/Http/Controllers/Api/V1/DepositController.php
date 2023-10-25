<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\DepositOccurredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DepositController extends Controller
{
    /**
     * deposit
     *
     * @param DepositRequest $request
     * @return JsonResponse
     */
    public function deposit(DepositRequest $request): JsonResponse
    {
        DB::beginTransaction();
        $fromUser = User::findOrFail($request->from);
        $toUser = User::findOrFail($request->to);

        $fromUser->transactions()->lockForUpdate();
        $toUser->transactions()->lockForUpdate();

        $currency = Currency::whereKey($request->currency_key)->first();

        $balance = $fromUser->getBalance($currency);

        if ($request->amount > $balance) {
            throw new BadRequestException(
                __('messages.errors.amount_should_be_smaller_then_balance')
            );
        }

        $fromUser->transactions()->create([
            'user_id' => $fromUser->id,
            'amount' => $request->amount * -1,
            'currency_key' => $request->currency_id,
            'balance' => $request->amount,
        ]);

        $toUser->transactions()->create([
            'user_id' => $toUser->id,
            'amount' => $request->amount,
            'currency' => $request->currency_id,
            'balance' => $request->amount,
        ]);

        $fromUser->updateBalance();
        $toUser->updateBalance();

        DB::commit();

        DepositOccurredEvent::dispatch($fromUser , $toUser);

        return apiResponse()
            ->send();
    }
}
