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

// Unauthorized users

Route::get('/', '\App\Http\Controllers\User\ProfileController@index')->name('home');

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', '\App\Http\Controllers\Auth\LoginController@getLogin')->name('login');
    Route::post('login', '\App\Http\Controllers\Auth\LoginController@postLogin');
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
});

Route::group(['prefix' => 'users'], function () {
    Route::get('/', '\App\Http\Controllers\Auth\RegisterController@getRegister');
    Route::post('/', '\App\Http\Controllers\Auth\RegisterController@postRegister');
});

//---- Unauthorized users


// Authorized users

Route::group(['middleware' => 'auth'], function () {

    Route::get('users/{id}', '\App\Http\Controllers\User\ProfileController@show')->where('id', '[a-z0-9-]+')->name('profile');

});

//---- Authorized users
