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
Route::get('login', 'API\UserController@login');
Route::get('register', 'API\UserController@register');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    // return $request->user();
    Route::get('details', 'API\UserController@details');
});

Route::get('getusers','UserController@getusers');
