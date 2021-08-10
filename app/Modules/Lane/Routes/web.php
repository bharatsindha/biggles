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

Route::group(['middleware' => ['auth', 'acl']], function () {
    Route::resource('lane', 'LaneController');

    # Route to return Inter State Details
    Route::get('interstate', [
        'as'   => 'lane.inter_state',
        'uses' => 'InterStateController@index'
    ]);
});
