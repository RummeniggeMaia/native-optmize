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
Route::get('/homewidgetslc', 'HomeController@widgetsLineChartData')->name('home.widgetslc')->middleware('role:publi');
Route::get('/homecampaignslc', 'HomeController@campaignsLineChartData')->name('home.campaignslc')->middleware('role:admin,adver');
Route::get('/homepayments', 'HomeController@paymentsDataTable')->name('home.payments')->middleware('role:admin');

Route::post('/users', 'UserController@store')->name('users.store')->middleware('role:admin');
Route::get('/users', 'UserController@index')->name('users')->middleware('role:admin');
Route::get('/users/create', 'UserController@create')->name('users.create')->middleware('role:admin');
Route::get('/users/{user}', 'UserController@show')->name('users.show')->middleware('role:admin');
Route::patch('/users/{user}', 'UserController@update')->name('users.update')->middleware('role:admin');
Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy')->middleware('role:admin');
Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit')->middleware('role:admin');
Route::get('users/{page?}', 'UserController@index')->name('users.index')->middleware('role:admin');
Route::get('/usersdata', 'UserController@indexDataTable')->name('users.data')->middleware('role:admin');

Route::post('/creatives', 'CreativeController@store')->name('creatives.store')->middleware('role:admin,adver');
Route::get('/creatives', 'CreativeController@index')->name('creatives')->middleware('role:admin,adver');
Route::get('/creatives/create', 'CreativeController@create')->name('creatives.create')->middleware('role:admin,adver');
Route::get('/creatives/{creative}', 'CreativeController@show')->name('creatives.show')->middleware('role:admin,adver');
Route::patch('/creatives/{creative}', 'CreativeController@update')->name('creatives.update')->middleware('role:admin,adver');
Route::delete('/creatives/{creative}', 'CreativeController@destroy')->name('creatives.destroy')->middleware('role:admin,adver');
Route::get('/creatives/{creative}/edit', 'CreativeController@edit')->name('creatives.edit')->middleware('role:admin,adver');
Route::get('/creatives/{page?}', 'CreativeController@index')->name('creatives')->middleware('role:admin,adver');
Route::get('/creativesdata', 'CreativeController@indexDataTable')->name('creatives.data')->middleware('role:admin,adver');
Route::get('/creativeclicksdata/{creative}', 'CreativeController@clicksDataTable')->name('creatives.clicks')->middleware('role:admin,adver');

Route::post('/campaingns', 'CampaingnController@store')->name('campaingns.store')->middleware('role:admin,adver');
Route::get('/campaingns', 'CampaingnController@index')->name('campaingns')->middleware('role:admin,adver');
Route::get('/campaingns/create', 'CampaingnController@create')->name('campaingns.create')->middleware('role:admin,adver');
Route::get('/campaingns/{campaingn}', 'CampaingnController@show')->name('campaingns.show')->middleware('role:admin,adver');
Route::patch('/campaingns/{campaingn}', 'CampaingnController@update')->name('campaingns.update')->middleware('role:admin,adver');
Route::delete('/campaingns/{campaingn}', 'CampaingnController@destroy')->name('campaingns.destroy')->middleware('role:admin,adver');
Route::get('/campaingns/{campaingn}/edit', 'CampaingnController@edit')->name('campaingns.edit')->middleware('role:admin,adver');
Route::get('/campaingns/{page?}', 'CampaingnController@index')->name('campaingns')->middleware('role:admin,adver');
Route::get('/campaignsdata', 'CampaingnController@indexDataTable')->name('campaingns.data')->middleware('role:admin,adver');
Route::get('/campcreasdata/{campaingn}', 'CampaingnController@creativesDataTable')->name('campaingns.creatives')->middleware('role:admin,adver');
Route::get('/campsinavites', 'CampaingnController@indexInatives')->name('campaingns.inatives')->middleware('role:admin');
Route::get('/campsinavitesdata', 'CampaingnController@inativesDataTable')->name('campaingns.inativesdata')->middleware('role:admin');

Route::post('/widgets', 'WidgetController@store')->name('widgets.store')->middleware('role:publi');
Route::get('/widgets', 'WidgetController@index')->name('widgets')->middleware('role:publi');
Route::get('/widgets/create', 'WidgetController@create')->name('widgets.create')->middleware('role:publi');
Route::get('/widgets/{widget}', 'WidgetController@show')->name('widgets.show')->middleware('role:publi');
Route::patch('/widgets/{widget}', 'WidgetController@update')->name('widgets.update')->middleware('role:publi');
Route::delete('/widgets/{widget}', 'WidgetController@destroy')->name('widgets.destroy')->middleware('role:publi');
Route::get('/widgets/{widget}/edit', 'WidgetController@edit')->name('widgets.edit')->middleware('role:publi');
Route::get('/widgets/{page?}', 'WidgetController@index')->name('widgets.index')->middleware('role:publi');
Route::get('/widgetsdata', 'WidgetController@indexDataTable')->name('widgets.data')->middleware('role:publi');
Route::get('/widgetlogsdata/{widget}', 'WidgetController@logsDataTable')->name('widgets.logs')->middleware('role:publi');

Route::post('/categories', 'CategoryController@store')->name('categories.store')->middleware('role:admin');
Route::get('/categories', 'CategoryController@index')->name('categories')->middleware('role:admin');
Route::get('/categories/create', 'CategoryController@create')->name('categories.create')->middleware('role:admin');
Route::get('/categories/{category}', 'CategoryController@show')->name('categories.show')->middleware('role:admin');
Route::patch('/categories/{category}', 'CategoryController@update')->name('categories.update')->middleware('role:admin');
Route::delete('/categories/{category}', 'CategoryController@destroy')->name('categories.destroy')->middleware('role:admin');
Route::get('/categories/{category}/edit', 'CategoryController@edit')->name('categories.edit')->middleware('role:admin');
Route::get('/categories/{page?}', 'CategoryController@index')->name('categories.index')->middleware('role:admin');
Route::get('/categoriesdata', 'CategoryController@indexDataTable')->name('categories.data')->middleware('role:admin');

Route::post('/payments', 'PaymentController@store')->name('payments.store')->middleware('role:publi');
Route::get('/payments', 'PaymentController@index')->name('payments')->middleware('role:publi');
Route::get('/payments/create', 'PaymentController@create')->name('payments.create')->middleware('role:publi');
Route::get('/paymentsdata', 'PaymentController@indexDataTable')->name('payments.data')->middleware('role:publi');
Route::get('/payments/{payment}', 'PaymentController@show')->name('payments.show')->middleware('role:admin');
Route::patch('/payments/{payment}', 'PaymentController@update')->name('payments.update')->middleware('role:admin');
Route::delete('/payments/{payment}', 'PaymentController@destroy')->name('payments.destroy')->middleware('role:admin');
Route::get('/payments/{payment}/edit', 'PaymentController@edit')->name('payments.edit')->middleware('role:admin');
Route::get('/payments/{payment}/voucher', 'PaymentController@voucher')->name('payments.voucher')->middleware('role:admin');
Route::patch('/payments/vouchers/{payment}', 'PaymentController@sendVoucher')->name('payments.send_voucher')->middleware('role:admin');

Route::patch('/users/payment/{payment}', 'UserController@payment')->name('users.payment')->middleware('role:admin');

Route::get('/auth/account', 'Auth\AuthController@edit')->name('auth.account')->middleware('role:admin,adver,publi');
Route::get('/auth/change', 'Auth\AuthController@changePassword')->name('auth.changePassword')->middleware('role:admin,adver,publi');
Route::get('/auth/paymentData', 'Auth\AuthController@paymentData')->name('auth.paymentData')->middleware('role:admin,adver,publi');

Route::patch('/auth/update', 'Auth\AuthController@update')->name('auth.update')->middleware('role:admin,adver,publi');
Route::patch('/auth/updatePassword', 'Auth\AuthController@updatePassword')
    ->name('auth.updatePassword')->middleware('role:admin,adver,publi');
Route::patch('/auth/updatePaymentData', 'Auth\AuthController@updatePaymentData')
    ->name('auth.updatePaymentData')->middleware('role:admin,adver,publi');
    
Route::post('/clicks')->middleware('clicks');
Route::post('/impressions')->middleware('impressions');
Route::get('/postbacks')->middleware('postbacks');
Route::get('/random_creatives')->middleware('random_creatives');
Route::get('/smartlinks')->middleware('smartlinks');
Route::get('/iframe')->middleware('iframe');
