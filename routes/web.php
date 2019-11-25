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
    return view('bulksms');
});

Route::post('/bulksms', 'BulkSmsController@sendSms');
Route::get('export/', 'BulkSmsController@export');
Route::get('get_numbers', 'BulkSmsController@get_numbers');
