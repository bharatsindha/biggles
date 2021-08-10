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
    Route::get('user/profile', ['as' => 'profile', 'uses' => 'UserController@profile']);
    Route::post('user/change-password', ['as' => 'user.password', 'uses' => 'UserController@changePassword']);
    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');

    Route::get('my-account', ['as'   => 'user.account', 'uses' => 'UserController@account']);
    Route::post('file/upload/store', ['as'   => 'user.file.upload', 'uses' => 'UserController@fileStore']);
    Route::get('file/delete', ['as'   => 'user.file.delete', 'uses' => 'UserController@fileDestroy']);
    Route::get('file/delete/{fileId}', ['as'   => 'user.attach.delete', 'uses' => 'UserController@attachDestroy']);



});
