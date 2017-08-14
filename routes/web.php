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

Route::get('/', 'ProfileController@index')->name('home');

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', 'Auth\LoginController@getLogin')->name('loginForm');
    Route::post('login', 'Auth\LoginController@postLogin')->name('login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('register/{invite}', 'Auth\RegisterController@getRegisterForm')
        ->where('invite', '[a-z0-9]+')
        ->name('registerForm');
});

Route::post('users', 'Auth\RegisterController@register')->name('register');

//---- Unauthorized users


// Authorized users

Route::group(['middleware' => 'auth'], function () {

    Route::get('users/{id}', 'ProfileController@show')
        ->where('id', '[a-z0-9-]+')
        ->name('profile');
    Route::get('profile', 'ProfileController@show');

    Route::resource('advert/offers', 'Advert\OfferController', [
        'names'  => [
            'index'  => 'advert.offers.index',
            'show'   => 'advert.offers.show',
            'create' => 'advert.offers.create',
            'store'  => 'advert.offers.store'
        ],
        'except' => [
            'update',
            'destroy'
        ]
    ]);
    Route::group(['prefix' => 'offers/{offerId}'], function () {
        Route::get('activation_code', 'RedemptionController@getActivationCode')->name('redemption.code');
        Route::group(['prefix' => 'redemption'], function () {
            Route::get('create', 'RedemptionController@create')->name('redemption.create');
            Route::post('', 'RedemptionController@redemption')->name('redemption.store');
            Route::get('{rid}', 'RedemptionController@show')->where('rid',
                '[a-z0-9-]+')->name('redemption.show');
        });
    });

    Route::resource('offers', 'User\OfferController', [
        'except' => [
            'create',
            'store',
            'update',
            'destroy'
        ]
    ]);


    Route::get('categories', 'CategoryController@index')->name('categories');
});

//---- Authorized users
