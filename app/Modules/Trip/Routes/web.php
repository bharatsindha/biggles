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

    Route::get('recurring-trips/{trip}', [
        'as'   => 'trip.recurring-trips',
        'uses' => 'TripController@recurringTrips'
    ]);

    Route::get('trip/create/{trip}', [
        'as'   => 'trip.trip_create_byLane',
        'uses' => 'TripController@createTrip'
    ]);

    Route::get('trip-calendar', [
        'as'   => 'trip.trip-calendar',
        'uses' => 'TripController@calendarView'
    ]);
    Route::get('get-trips', 'TripController@getTrips')->name('trip.get-trips');
    Route::post('add-trip', 'TripController@addTrip')->name('trip.new-trip');
    Route::get('filter-lane/{lane_name?}', 'TripController@filterLane')->name('filter-lane');
    Route::resource('trip', 'TripController');
});
