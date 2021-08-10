<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Support\Facades\Route;

/*Route::group(['prefix' => 'truck'], function () {
    Route::get('/', function () {
        dd('This is the Truck module index page. Build something great!');
    });
});*/


Route::group(['middleware' => ['auth', 'acl']], function () {
    Route::resource('truck', 'TruckController');
});
