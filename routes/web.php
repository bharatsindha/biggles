<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');*/

require __DIR__ . '/auth.php';

Route::get('/', 'App\Http\Controllers\HomeController@index');
Route::get('/dashboard', ['as' => 'dashboard.index', 'uses' => 'App\Http\Controllers\HomeController@index']);
/*
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('seen-notification', ['as' => 'notification.seen', 'uses' => 'HomeController@notificationSeen']);
//Route::post('/search', 'HomeController@search')->name('search.post');
Route::get('/search', 'HomeController@search')->name('search.keyword.get');
//Route::get('/search', 'HomeController@search')->name('search.get');*/
