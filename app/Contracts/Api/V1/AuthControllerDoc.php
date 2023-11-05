<?php

namespace App\Contracts\Api\V1;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface AuthControllerDoc
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     tags={"Auth"},
     *     summary="Login to get JWT token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *        @OA\Response(response=200,description="Successful operation"),
     *        @OA\Response(response=400,description="Bad Request"),
     *        @OA\Response(response=401,description="Unauthenticated"),
     *        @OA\Response(response=404,description="Resource Not Found")
     * )
     */
    public function login(): JsonResponse;

    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password" , "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *         @OA\Response(response=201,description="Successful operation"),
     *         @OA\Response(response=400,description="Bad Request"),
     *         @OA\Response(response=404,description="Resource Not Found")
     *  )
     */
    public function register(Request $request): JsonResponse;
    /**
     * @OA\Get(
     *     path="/api/v1/auth/me",
     *     tags={"Auth"},
     *     summary="Get current user details",
     *     security={{ "bearerAuth": {} }},
     *          @OA\Response(response=200,description="Successful operation"),
     *          @OA\Response(response=400,description="Bad Request"),
     *          @OA\Response(response=401,description="Unauthenticated"),
     *          @OA\Response(response=404,description="Resource Not Found")
     *  )
     */
    public function me(): JsonResponse;
    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     tags={"Auth"},
     *     summary="Logout the current user",
     *     security={{ "bearerAuth": {} }},
     *           @OA\Response(response=200,description="Successful operation"),
     *           @OA\Response(response=400,description="Bad Request"),
     *           @OA\Response(response=401,description="Unauthenticated"),
     *           @OA\Response(response=404,description="Resource Not Found")
     *   )
     */
    public function logout(): JsonResponse;

    /**
     * @OA\Post(
     *     path="/api/v1/auth/refresh",
     *     tags={"Auth"},
     *     summary="Refresh JWT token",
     *     security={{ "bearerAuth": {} }},
     *            @OA\Response(response=200,description="Successful operation"),
     *            @OA\Response(response=400,description="Bad Request"),
     *            @OA\Response(response=401,description="Unauthenticated"),
     *            @OA\Response(response=404,description="Resource Not Found")
     * )
     */
    public function refresh(): JsonResponse;

}
