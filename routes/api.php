<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can signup API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// before login APIs
Route::post('signup', 'API\SignupController@signup');
Route::post('login', 'API\SignupController@login');

// After user Login APIs
Route::middleware('auth:api')->group(function () {
	Route::post('applyloan', 'API\LoanController@applyLoan');
	Route::post('approveloan', 'API\LoanController@approveLoan');
	Route::post('payemi', 'API\LoanController@payEmi');
	Route::post('logout', 'API\SignupController@logout');
});