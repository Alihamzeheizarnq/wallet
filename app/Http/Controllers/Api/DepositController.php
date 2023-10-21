<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    use ApiResponse;
    public function deposit(DepositRequest $request)
    {
        DB::beginTransaction();
        $fromUser = User::where('id', $request->from)->lockForUpdate()->first();
        $toUser = User::where('id', $request->to)->lockForUpdate()->first();

        $fromUser->transactions()->create([
            'user_id' => $fromUser->id,
            'amount' => $request->amount * -1,
            'currency' => $request->currency_id,
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
}
