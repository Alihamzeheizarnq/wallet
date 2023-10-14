<?php

use App\Http\Controllers\Api\AuthController;
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
    $router->post('logout', [PaymentController::class, 'logout']);
    $router->post('refresh', [PaymentController::class, 'index']);
    $router->post('me', [PaymentController::class, 'index']);
});


Route::group([
    'middleware' => ['api' , 'auth'],
], function ($router) {
    $router->get('payments', [PaymentController::class, 'index']);
    $router->get('payments/{payment:unique_id}', [PaymentController::class, 'show']);
    $router->post('payments', [PaymentController::class, 'store']);
    $router->patch('payments/{payment:unique_id}/reject', [PaymentController::class, 'reject']);
    $router->patch('payments/{payment:unique_id}/approved', [PaymentController::class, 'approved']);
});
