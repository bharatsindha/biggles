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

Route::group(['middleware' => ['auth','acl']], function () {
    Route::resource('depot', 'DepotController');
    Route::resource('local', 'LocalController');
    Route::get('local/create/{depotId}', [
        'as'   => 'depot.local.create',
        'uses' => 'LocalController@createLocal'
    ]);
    Route::get('depot-company', [
        'as'   => 'depot.company',
        'uses' => 'DepotController@indexCompany'
    ]);
});
