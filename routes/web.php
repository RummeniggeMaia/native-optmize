<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    if (Auth::guest()) {
        return view('auth.login');
    } else {
        return view('home');
    }
});

Auth::routes();

//Route::get('/clicks', ['middleware' => 'cors', function() {
//    return \Response::json('ok', 200);
//}]);

Route::get('/home', 'HomeController@index')->name('home');



Route::middleware(['admin'])->group(function () {
    Route::post('/creatives', 'CreativeController@store')->name('creatives.store');
    Route::get('/creatives', 'CreativeController@index')->name('creatives');
    Route::get('/creatives/create', 'CreativeController@create')->name('creatives.create');
    Route::get('/creatives/{creative}', 'CreativeController@show')->name('creatives.show');
    Route::patch('/creatives/{creative}', 'CreativeController@update')->name('creatives.update');
    Route::delete('/creatives/{creative}', 'CreativeController@destroy')->name('creatives.destroy');
    Route::get('/creatives/{creative}/edit', 'CreativeController@edit')->name('creatives.edit');
    Route::get('/creatives/{page?}', 'CreativeController@index')->name('creatives');

    Route::post('/campaingns', 'CampaingnController@store')->name('campaingns.store');
    Route::get('/campaingns', 'CampaingnController@index')->name('campaingns');
    Route::get('/campaingns/create', 'CampaingnController@create')->name('campaingns.create');
    Route::get('/campaingns/{campaingn}', 'CampaingnController@show')->name('campaingns.show');
    Route::patch('/campaingns/{campaingn}', 'CampaingnController@update')->name('campaingns.update');
    Route::delete('/campaingns/{campaingn}', 'CampaingnController@destroy')->name('campaingns.destroy');
    Route::get('/campaingns/{campaingn}/edit', 'CampaingnController@edit')->name('campaingns.edit');
    Route::get('/campaingns/{page?}', 'CampaingnController@index')->name('campaingns');

    Route::post('/categories', 'CategoryController@store')->name('categories.store');
    Route::get('/categories', 'CategoryController@index')->name('categories');
    Route::get('/categories/create', 'CategoryController@create')->name('categories.create');
    Route::get('/categories/{campaingn}', 'CategoryController@show')->name('categories.show');
    Route::patch('/categories/{campaingn}', 'CategoryController@update')->name('categories.update');
    Route::delete('/categories/{campaingn}', 'CategoryController@destroy')->name('categories.destroy');
    Route::get('/categories/{campaingn}/edit', 'CategoryController@edit')->name('categories.edit');
    Route::get('/categories/{page?}', 'CategoryController@index')->name('categories.index');

    Route::post('/users', 'UserController@store')->name('users.store');
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/users/create', 'UserController@create')->name('users.create');
    Route::get('/users/{widget}', 'UserController@show')->name('users.show');
    Route::patch('/users/{widget}', 'UserController@update')->name('users.update');
    Route::delete('/users/{widget}', 'UserController@destroy')->name('users.destroy');
    Route::get('/users/{widget}/edit', 'UserController@edit')->name('users.edit');
    Route::get('users/{page?}', 'UserController@index')->name('users.index');
});

Route::middleware(['user'])->group(function () {
    Route::post('/widgets', 'WidgetController@store')->name('widgets.store');
    Route::get('/widgets', 'WidgetController@index')->name('widgets');
    Route::get('/widgets/create', 'WidgetController@create')->name('widgets.create');
    Route::get('/widgets/{widget}', 'WidgetController@show')->name('widgets.show');
    Route::patch('/widgets/{widget}', 'WidgetController@update')->name('widgets.update');
    Route::delete('/widgets/{widget}', 'WidgetController@destroy')->name('widgets.destroy');
    Route::get('/widgets/{widget}/edit', 'WidgetController@edit')->name('widgets.edit');
    Route::get('/widgets/{page?}', 'WidgetController@index')->name('widgets.index');
});

Route::post('/clicks')->middleware('clicks');
Route::get('/random_creatives/{type?}')->middleware('random_creatives');
