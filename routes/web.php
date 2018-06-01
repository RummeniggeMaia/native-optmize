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
        return view('home')->with(['widgets' => array(), 'earnings' => 0]);
    }
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['user'])->group(function () {
    Route::post('/widgets', 'WidgetController@store')->name('widgets.store');
    Route::get('/widgets', 'WidgetController@index')->name('widgets');
    Route::get('/widgets/create', 'WidgetController@create')->name('widgets.create');
    Route::get('/widgets/{widget}', 'WidgetController@show')->name('widgets.show');
    Route::patch('/widgets/{widget}', 'WidgetController@update')->name('widgets.update');
    Route::delete('/widgets/{widget}', 'WidgetController@destroy')->name('widgets.destroy');
    Route::get('/widgets/{widget}/edit', 'WidgetController@edit')->name('widgets.edit');
    Route::get('/widgets/{page?}', 'WidgetController@index')->name('widgets.index');
    Route::get('/widgetsdata', 'WidgetController@indexDataTable')->name('widgets.data');
    Route::get('/widgetlogsdata/{widget}', 'WidgetController@logsDataTable')->name('widgets.logs');

    Route::post('/payments', 'PaymentController@store')->name('payments.store');
    Route::get('/payments', 'PaymentController@index')->name('payments');
    Route::get('/payments/create', 'PaymentController@create')->name('payments.create');
    Route::get('/paymentsdata', 'PaymentController@indexDataTable')->name('payments.data');

    Route::get('/homewidgetslc', 'HomeController@widgetsLineChartData')->name('home.widgetslc');
});

Route::middleware(['admin'])->group(function () {
    Route::post('/users', 'UserController@store')->name('users.store');
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/users/create', 'UserController@create')->name('users.create');
    Route::get('/users/{user}', 'UserController@show')->name('users.show');
    Route::patch('/users/{user}', 'UserController@update')->name('users.update');
    Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy');
    Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::get('users/{page?}', 'UserController@index')->name('users.index');
    Route::get('/usersdata', 'UserController@indexDataTable')->name('users.data');
    Route::patch('/users/payment/{payment}', 'UserController@payment')->name('users.payment');

    Route::post('/creatives', 'CreativeController@store')->name('creatives.store');
    Route::get('/creatives', 'CreativeController@index')->name('creatives');
    Route::get('/creatives/create', 'CreativeController@create')->name('creatives.create');
    Route::get('/creatives/{creative}', 'CreativeController@show')->name('creatives.show');
    Route::patch('/creatives/{creative}', 'CreativeController@update')->name('creatives.update');
    Route::delete('/creatives/{creative}', 'CreativeController@destroy')->name('creatives.destroy');
    Route::get('/creatives/{creative}/edit', 'CreativeController@edit')->name('creatives.edit');
    Route::get('/creatives/{page?}', 'CreativeController@index')->name('creatives');
    Route::get('/creativesdata', 'CreativeController@indexDataTable')->name('creatives.data');
    Route::get('/creativeclicksdata/{creative}', 'CreativeController@clicksDataTable')->name('creatives.clicks');

    Route::post('/campaingns', 'CampaingnController@store')->name('campaingns.store');
    Route::get('/campaingns', 'CampaingnController@index')->name('campaingns');
    Route::get('/campaingns/create', 'CampaingnController@create')->name('campaingns.create');
    Route::get('/campaingns/{campaingn}', 'CampaingnController@show')->name('campaingns.show');
    Route::patch('/campaingns/{campaingn}', 'CampaingnController@update')->name('campaingns.update');
    Route::delete('/campaingns/{campaingn}', 'CampaingnController@destroy')->name('campaingns.destroy');
    Route::get('/campaingns/{campaingn}/edit', 'CampaingnController@edit')->name('campaingns.edit');
    Route::get('/campaingns/{page?}', 'CampaingnController@index')->name('campaingns');
    Route::get('/campaignsdata', 'CampaingnController@indexDataTable')->name('campaingns.data');
    Route::get('/campcreasdata/{campaingn}', 'CampaingnController@creativesDataTable')->name('campaingns.creatives');

    Route::post('/categories', 'CategoryController@store')->name('categories.store');
    Route::get('/categories', 'CategoryController@index')->name('categories');
    Route::get('/categories/create', 'CategoryController@create')->name('categories.create');
    Route::get('/categories/{category}', 'CategoryController@show')->name('categories.show');
    Route::patch('/categories/{category}', 'CategoryController@update')->name('categories.update');
    Route::delete('/categories/{category}', 'CategoryController@destroy')->name('categories.destroy');
    Route::get('/categories/{category}/edit', 'CategoryController@edit')->name('categories.edit');
    Route::get('/categories/{page?}', 'CategoryController@index')->name('categories.index');
    Route::get('/categoriesdata', 'CategoryController@indexDataTable')->name('categories.data');

    Route::get('/payments/{payment}', 'PaymentController@show')->name('payments.show');
    Route::patch('/payments/{payment}', 'PaymentController@update')->name('payments.update');
    Route::delete('/payments/{payment}', 'PaymentController@destroy')->name('payments.destroy');
    Route::get('/payments/{payment}/edit', 'PaymentController@edit')->name('payments.edit');
    Route::get('/payments/{payment}/voucher', 'PaymentController@voucher')->name('payments.voucher');
    Route::patch('/payments/vouchers/{payment}', 'PaymentController@sendVoucher')->name('payments.send_voucher');

    Route::get('/homepayments', 'HomeController@paymentsDataTable')->name('home.payments');
});

Route::get('/auth/account', 'Auth\AuthController@edit')->name('auth.account');
Route::get('/auth/change', 'Auth\AuthController@changePassword')->name('auth.changePassword');
Route::get('/auth/paymentData', 'Auth\AuthController@paymentData')->name('auth.paymentData');

Route::patch('/auth/update', 'Auth\AuthController@update')->name('auth.update');
Route::patch('/auth/updatePassword', 'Auth\AuthController@updatePassword')
    ->name('auth.updatePassword');
Route::patch('/auth/updatePaymentData', 'Auth\AuthController@updatePaymentData')
    ->name('auth.updatePaymentData');

Route::post('/clicks')->middleware('clicks');
Route::post('/impressions')->middleware('impressions');
Route::get('/postbacks')->middleware('postbacks');
Route::get('/random_creatives')->middleware('random_creatives');
Route::get('/smartlinks')->middleware('smartlinks');
