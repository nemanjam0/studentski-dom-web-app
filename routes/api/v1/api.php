<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix('/user')->group(function(){
    Route::post('login','api\v1\ApiLoginController@login');
});
Route::middleware('auth:api')->group(function () {
    Route::post('getCardInfo','api\v1\ApiStudentCardController@getCardInfo')->middleware('scopes:get-card-info');
    Route::post('createCafeteriaCardTransaction','api\v1\ApiStudentCardController@createCafeteriaCardTransaction')->middleware('scopes:create-cafeteria-card-transaction');
    Route::post('createAtmCardTransaction','api\v1\ApiStudentCardController@createAtmCardTransaction')->middleware('scopes:create-atm-card-transaction');
});
