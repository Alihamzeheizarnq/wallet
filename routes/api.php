<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CurrencyController;
use App\Http\Controllers\Api\V1\DepositController;
use App\Http\Controllers\Api\V1\PaymentController;
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
    'prefix' => 'v1/auth',
    'as' => 'api.'
], function ($router) {
    $router->post('login', [AuthController::class, 'login']);
    $router->post('register', [AuthController::class, 'register']);
    $router->post('logout', [AuthController::class, 'logout']);
    $router->post('refresh', [AuthController::class, 'refresh']);
    $router->get('me', [AuthController::class, 'me']);
});


Route::group([
    'middleware' => ['auth'],
    'prefix' => 'v1',
    'as' => 'api.'
], function ($router) {
    $router->get('payments', [PaymentController::class, 'index'])->name('payment.index');
    $router->post('payments', [PaymentController::class, 'store'])->name('payment.store');
    $router->get('payments/{payment}', [PaymentController::class, 'show'])->name('payment.show');
    $router->delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payment.destroy');
    $router->patch('payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payment.reject');
    $router->patch('payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payment.approve');

    // currency routes
    $router->patch('currencies/{currency}/deactivate', [CurrencyController::class, 'deactivate'])
        ->name('currencies.deactivate');
    $router->patch('currencies/{currency}/activate', [CurrencyController::class, 'activate'])
        ->name('currencies.activate');

    $router->apiResource('currencies', CurrencyController::class);

    $router->post('deposit', [DepositController::class, 'deposit']);
});
