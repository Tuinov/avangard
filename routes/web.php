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
    return view('layouts.app');
});


# Погода
Route::get('/weather', 'Widgets\WeatherController@index')->name('weather');

# Заказы
Route::get('/orders', 'Shop\OrderController@index')->name('orders');
# Заказы
Route::get('/order/edit{id}', 'Shop\OrderController@edit')->name('order.edit');

//Route::group(['namespace' => 'Shop'],function() {
//    Route::resource('orders', 'OrderController')->names('shop.orders');
//});