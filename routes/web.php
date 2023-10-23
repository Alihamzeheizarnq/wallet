<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    $totalAmount = User::where('id' , 6)->first();
    
    $totalAmount = $totalAmount->transactions()
    ->select('currency', DB::raw('SUM(amount) as total_amount'))
    ->groupBy('currency')
    ->pluck('total_amount', 'currency');


    dd( $totalAmount);
$this->update([
    'balance' => json_encode($totalAmount->jsonSerialize())
]);
    return view('welcome');
});
