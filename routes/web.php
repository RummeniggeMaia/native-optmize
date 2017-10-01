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

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/creatives', 'CreativeController@store')->name('creatives.store');
Route::get('/creatives', 'CreativeController@index')->name('creatives');
Route::get('/creatives/create', 'CreativeController@create')->name('creatives.create');
Route::get('/creatives/{creative}', 'CreativeController@show')->name('creatives.show');
Route::patch('/creatives/{creative}', 'CreativeController@update')->name('creatives.update');
Route::delete('/creatives/{creative}', 'CreativeController@destroy')->name('creatives.destroy');
Route::get('/creatives/{creative}/edit', 'CreativeController@edit')->name('creatives.edit');

Route::post('/campaingns', 'CampaingnController@store')->name('campaingns.store');
Route::get('/campaingns', 'CampaingnController@index')->name('campaingns');
Route::get('/campaingns/create', 'CampaingnController@create')->name('campaingns.create');
Route::get('/campaingns/{campaingn}', 'CampaingnController@show')->name('campaingns.show');
Route::patch('/campaingns/{campaingn}', 'CampaingnController@update')->name('campaingns.update');
Route::delete('/campaingns/{campaingn}', 'CampaingnController@destroy')->name('campaingns.destroy');
Route::get('/campaingns/{campaingn}/edit', 'CampaingnController@edit')->name('campaingns.edit');

Route::post('/widgets', 'WidgetController@store')->name('widgets.store');
Route::get('/widgets', 'WidgetController@index')->name('widgets');
Route::get('/widgets/create', 'WidgetController@create')->name('widgets.create');
Route::get('/widgets/{widget}', 'WidgetController@show')->name('widgets.show');
Route::patch('/widgets/{widget}', 'WidgetController@update')->name('widgets.update');
Route::delete('/widgets/{widget}', 'WidgetController@destroy')->name('widgets.destroy');
Route::get('/widgets/{widget}/edit', 'WidgetController@edit')->name('widgets.edit');
