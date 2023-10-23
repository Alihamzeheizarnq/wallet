<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    $router->post('login', [AuthController::class, 'login']);
    $router->post('register', [AuthController::class, 'register']);
    $router->post('logout', [AuthController::class, 'logout']);
    $router->post('refresh', [AuthController::class, 'refresh']);
    $router->post('me', [AuthController::class, 'me']);
});


Route::group([
    'middleware' => ['api' , 'auth'],
], function ($router) {
    $router->get('payments', [PaymentController::class, 'index']);
    $router->get('payments/{payment:unique_id}', [PaymentController::class, 'show']);
    $router->post('payments', [PaymentController::class, 'store']);
    //TODO move the route's binds into the model
    $router->patch('payments/{payment:unique_id}/reject', [PaymentController::class, 'reject']);
    //TODO change the routes
    $router->patch('payments/{payment:unique_id}/approve', [PaymentController::class, 'approved']);

    // currency routes
    $router->patch('currencies/{currency}/deactivate', [CurrencyController::class , 'deactivate']);
    $router->patch('currencies/{currency}/activate', [CurrencyController::class , 'activate']);
    $router->apiResource('currencies', CurrencyController::class);

    $router->post('deposit', [DepositController::class , 'deposit']);
});
