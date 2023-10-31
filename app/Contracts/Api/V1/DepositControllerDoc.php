<?php

namespace App\Contracts\Api\V1;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;

interface DepositControllerDoc
{

    /**
     * @OA\Post(
     *     path="/api/v1/deposit ",
     *     tags={"Deposit"},
     *     summary="Deposit",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"from", "to", "currency_key", "amount"},
     *              @OA\Property(property="from", type="int", format="from", example="1"),
     *              @OA\Property(property="to", type="int", format="to", example="2"),
     *              @OA\Property(property="currency_key", type="string", format="currency_key", example="usd"),
     *              @OA\Property(property="amount", type="int", format="to", example="2000"),
     *         )
     *     ),
     *       @OA\Response(response=200,description="Successful operation"),
     *       @OA\Response(response=201,description="Successful operation"),
     *       @OA\Response(response=202,description="Successful operation"),
     *       @OA\Response(response=204,description="Successful operation"),
     *       @OA\Response(response=400,description="Bad Request"),
     *       @OA\Response(response=401,description="Unauthenticated"),
     *       @OA\Response(response=403,description="Forbidden"),
     *       @OA\Response(response=404,description="Resource Not Found")
     * )
     */
    public function deposit(DepositRequest $request): JsonResponse;
}
