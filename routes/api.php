<?php

use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('payments', [PaymentController::class, 'index']);
Route::get('payments/{payment:unique_id}', [PaymentController::class, 'show']);
Route::post('payments', [PaymentController::class, 'store']);
Route::patch('payments/{payment:unique_id}/reject', [PaymentController::class, 'reject']);
Route::patch('payments/{payment:unique_id}/approved', [PaymentController::class, 'approved']);
