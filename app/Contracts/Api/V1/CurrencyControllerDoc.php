<?php

namespace App\Contracts\Api\V1;

use App\Http\Requests\StoreCurrencyRequest;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

interface CurrencyControllerDoc
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @OA\Get(
     *     path="/api/v1/currencies",
     *     operationId="getListCurrency",
     *     tags={"Currency"},
     *     summary="summary",
     *     description="get list of Currency",
     *
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200,description="Successful operation"),
     *     @OA\Response(response=201,description="Successful operation"),
     *     @OA\Response(response=202,description="Successful operation"),
     *     @OA\Response(response=204,description="Successful operation"),
     *     @OA\Response(response=400,description="Bad Request"),
     *     @OA\Response(response=401,description="Unauthenticated"),
     *     @OA\Response(response=403,description="Forbidden"),
     *     @OA\Response(response=404,description="Resource Not Found")
     * )
     */
    public function index(): JsonResponse;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @OA\Post(
     *     path="/api/v1/currencies",
     *     operationId="storeCurrency",
     *     tags={"Currency"},
     *     summary="currency",
     *     description="store currency",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                  type="object",
     *                  required={"amount","currency"},
     *                  @OA\Property(property="amount", type="text"),
     *                  @OA\Property(property="currency", type="text"),
     *            ),
     *        ),
     *    ),
     *
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200,description="Successful operation"),
     *      @OA\Response(response=201,description="Successful operation"),
     *      @OA\Response(response=202,description="Successful operation"),
     *      @OA\Response(response=204,description="Successful operation"),
     *      @OA\Response(response=400,description="Bad Request"),
     *      @OA\Response(response=401,description="Unauthenticated"),
     *      @OA\Response(response=403,description="Forbidden"),
     *      @OA\Response(response=404,description="Resource Not Found")
     * )
     */
    public function store(StoreCurrencyRequest $request): JsonResponse;


    /**
     * Display a listing of the resource.
     *
     * @param Currency $currency
     * @return JsonResponse
     * @OA\Patch(
     *     path="/api/v1/currencies/{currency}/activate",
     *     operationId="rejectCurrency",
     *     tags={"Currency"},
     *     summary="currency",
     *     description="reject currency",
     *      @OA\Parameter(
     *          name="id",
     *          description="currency id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200,description="Successful operation"),
     *      @OA\Response(response=201,description="Successful operation"),
     *      @OA\Response(response=202,description="Successful operation"),
     *      @OA\Response(response=204,description="Successful operation"),
     *      @OA\Response(response=400,description="Bad Request"),
     *      @OA\Response(response=401,description="Unauthenticated"),
     *      @OA\Response(response=403,description="Forbidden"),
     *      @OA\Response(response=404,description="Resource Not Found")
     * )
     */
    public function activate(Currency $currency): JsonResponse;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @OA\Patch(
     *     path="/api/v1/currencies/{currency}/deactivate ",
     *     operationId="approvedCurrency",
     *     tags={"Currency"},
     *     summary="currency",
     *     description="approved currency",
     *      @OA\Parameter(
     *          name="id",
     *          description="currency id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200,description="Successful operation"),
     *      @OA\Response(response=201,description="Successful operation"),
     *      @OA\Response(response=202,description="Successful operation"),
     *      @OA\Response(response=204,description="Successful operation"),
     *      @OA\Response(response=400,description="Bad Request"),
     *      @OA\Response(response=401,description="Unauthenticated"),
     *      @OA\Response(response=403,description="Forbidden"),
     *      @OA\Response(response=404,description="Resource Not Found")
     * )
     */
    public function deactivate(Currency $currency): JsonResponse;
}
