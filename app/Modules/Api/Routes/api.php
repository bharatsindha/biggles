<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::get('/api', function (Request $request) {
    // return $request->api();
})->middleware('auth:api');*/

//verifyAPIToken
Route::prefix('/v1')->group(function () {

    Route::middleware('verifyAPIToken')->group(function () {
//    Route::group(['middleware' => ['verifyAPIToken', 'cors']], function(){
        Route::get('/configurationVolume', ['as' => 'configuration.volume', 'uses' => 'MoveController@getConfigurationItem']);
        Route::post('/calculateVolume', ['as' => 'calculate.volume', 'uses' => 'MoveController@calculateVolume']);
        Route::post('/roughVolume', ['as' => 'calculate.rough-volume', 'uses' => 'MoveController@calculateRoughVolume']);
        Route::post('/find-match', ['as' => 'calculate.find-match', 'uses' => 'LeadMatchController@findMatch']);
        Route::post('/get-removal-lists', ['as' => 'move.get-removal-lists', 'uses' => 'LeadMatchController@getRemovalLists']);
        Route::post('/get-removal-details', ['as' => 'move.get-removal-details', 'uses' => 'LeadMatchController@getRemovalDetails']);
        Route::post('/get-delivery-prices', ['as' => 'move.get-delivery-prices', 'uses' => 'LeadMatchController@getDeliveryPrices']);
        Route::post('/get-ancillaries', ['as' => 'move.get-ancillaries', 'uses' => 'MoveController@getAncillaryServices']);
        Route::post('/get-booked-ancillaries', ['as' => 'move.get-booked-ancillaries', 'uses' => 'MoveController@getBookedAncillaries']);
        Route::post('/get-move-details/{moveId}', ['as' => 'move.get-move-details', 'uses' => 'MoveController@getMoveDetails']);
        Route::post('/set-move', 'MoveController@setMoveDetails');
        Route::post('/add-customer', 'MoveController@addCustomer');
        Route::post('/get-pricing-details', 'LeadMatchController@getPricingDetail');
        Route::post('/get-customer-details/{customerId}', 'MoveController@getCustomerDetails');
        Route::get('/get-setup-intent/{customerId}', 'MoveController@getSetupIntent');
        Route::get('/get-access-attribute', 'MoveController@getAccessAttribute');

    });
});

//auth routes
Route::post('v1/user-auth', 'AuthController@login');
Route::group([
    'prefix' => 'v1',
    'as' => 'api.',

//    'middleware' => ['auth:api']

], function () {
    // Lists all leads
    Route::post('leads', 'MoveController@getLeads');
    // Get Lead details
    Route::get('lead/{id}', 'MoveController@getLeadDetails');
    Route::post('lead', 'MoveController@saveLeadDetails');
});







