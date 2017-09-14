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
Route::resource('campaingn_creatives','CampaingnCreativeController');
Route::resource('widgets','WidgetController');
Route::resource('widget_campaingns','WidgetCampaingnController');
Route::resource('creatives','CreativeController');
Route::resource('creative_log','CreativeLogController');
Route::resource('categories','CategoryController');
