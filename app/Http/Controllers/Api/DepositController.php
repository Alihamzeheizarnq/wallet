<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Models\Currency;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DepositController extends Controller
{
    use ApiResponse;
    public function deposit(DepositRequest $request)
    {
        DB::beginTransaction();
        $fromUser = User::where('id', $request->from)->first();
        $toUser = User::where('id', $request->to)->first();

        $fromUser->transactions()->lockForUpdate();
        $toUser->transactions()->lockForUpdate();

        $currency = Currency::where('key', $request->currency_key)->first();

        $balance = $fromUser->getBalance($currency);

        if($request->amount > $balance){
            throw new BadRequestException('amount must be smaller than your balance');
        }

        sleep(60);

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

        return $this->successResponse();

    }

    public function deposit2(DepositRequest $request)
    {
        $fromUser = User::where('id', $request->from)->lockForUpdate()->first();

        $fromUser->transactions()->create([
            'user_id' => $fromUser->id,
            'amount' => $request->amount * -1,
            'currency_key' => $request->currency_key,
            'balance' => $request->amount,
        ]);

        return $this->successResponse();

    }

}
