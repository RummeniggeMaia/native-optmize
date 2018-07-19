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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('campaingns','CampaingnController');
Route::resource('widgets','WidgetController');
Route::resource('categories','CategoryController');
Route::resource('users', 'UserController');
Route::resource('payments', 'PaymentController');

Route::get('/clicks')->middleware('clicks');
Route::post('/impressions')->middleware('impressions');
Route::get('/postbacks')->middleware('postbacks');
Route::get('/random_creatives')->middleware('random_creatives');
Route::get('/smartlinks')->middleware('smartlinks');
Route::get('/iframe')->middleware('iframe');