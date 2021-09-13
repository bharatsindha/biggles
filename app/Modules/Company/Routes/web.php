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
    Route::get('stripe-connect', [
        'as'   => 'company.stripe-connect',
        'uses' => 'CompanyController@connectStripe'
    ]);

    Route::get('stripe-disconnect/{company}', [
        'as'   => 'company.stripe-disconnect',
        'uses' => 'CompanyController@disconnectStripe'
    ]);

    Route::get('company-profile/{companyId?}', [
        'as'   => 'company.profile',
        'uses' => 'CompanyController@profile'
    ]);

    Route::get('interstate-setting/{companyId?}', [
        'as'   => 'company.interstate',
        'uses' => 'CompanyController@setting'
    ]);

    Route::get('company-setting/{companyId?}', [
        'as'   => 'company.setting',
        'uses' => 'CompanyController@companySetting'
    ]);

    Route::get('company/status/{company}', [
        'as'   => 'company.status',
        'uses' => 'CompanyController@manageStatus'
    ]);

    Route::resource('company', 'CompanyController');
});

# Access to register the company by external users
Route::get('register-company', [
    'as'   => 'register_company.index',
    'uses' => 'CompanyReqController@index'
]);
Route::post('register-company', [
    'as'   => 'register_company.store',
    'uses' => 'CompanyReqController@store'
]);
