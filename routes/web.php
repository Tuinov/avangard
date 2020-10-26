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


Route::group(['namespace' => 'Shop\Admin'],function() {
    # Заказы
    Route::get('/orders', 'MainController@index')->name('orders');
    # Заказ редактировать
    Route::get('/order/edit/{id}', 'MainController@edit')->name('order.edit');
    # Заказ изменить
    Route::post('/order/update/{id}', 'MainController@update')->name('order.update');

    # Заказы сортировка
    Route::get('/orders/late', 'MainController@getOrdersLate')->name('orders.late');
    Route::get('/orders/now', 'MainController@getOrdersNow')->name('orders.now');
    Route::get('/orders/new', 'MainController@getOrdersNew')->name('orders.new');
    Route::get('/orders/completed', 'MainController@getOrdersСompleted')->name('orders.completed');
});