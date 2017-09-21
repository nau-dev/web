<?php

/** @var \Illuminate\Routing\Router $router */
$router = app('router');

// Unauthorized users
$router->group(['middleware' => 'guest:jwt,web'], function () use ($router) {

    $router->get('/', function () {
        return response()->render('home', []);
    })->name('home');

    $router->group(['prefix' => 'auth'], function () use ($router) {

        $router->group(['prefix' => 'login'], function () use ($router) {
            $router->get('', 'Auth\LoginController@getLogin')
                   ->name('loginForm');

            $router->post('', 'Auth\LoginController@login')
                   ->name('login');

            $router->get('{phone_number}/code', 'Auth\LoginController@getOtpCode')
                   ->middleware('throttle:1')
                   ->where('phone_number', '\+[0-9]+')
                   ->name('get-login-otp-code');
        });

        $router->group(['prefix' => 'register'], function () use ($router) {
            $router->get('{invite}/{phone_number}/code', 'Auth\RegisterController@getOtpCode')
                //->middleware('throttle:1')
                   ->where(['invite', '[a-z0-9]+'], ['phone_number', '\+[0-9]+'])
                   ->name('get-register-otp-code');

            /**
             * register with invite code
             */
            $router->get('{invite}', 'Auth\RegisterController@getRegisterForm')
                   ->where('invite', '[a-z0-9]+')
                   ->name('registerForm');
        });

        /**
         * reset password
         */
        $router->group(['prefix' => 'password'], function () use ($router) {
            $router->get('reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
            $router->post('email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            $router->get('reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
            $router->post('reset', 'Auth\ResetPasswordController@reset');
        });
    });

    /**
     * register
     */
    $router->post('users', 'Auth\RegisterController@register')->name('register');
});

//---- Unauthorized users


// Authorized users

$router->group(['middleware' => 'auth:jwt,web'], function () use ($router) {

    $router->get('auth/logout', 'Auth\LoginController@logout')->name('logout');
    $router->get('auth/token', 'Auth\LoginController@tokenRefresh')->name('auth.token.refresh');

    $router->group(['prefix' => 'users/{id}', 'where' => ['id' => '[a-z0-9-]+']], function () use ($router) {
        $router->get('', 'ProfileController@show')->name('users.show');
        $router->get('referrals', 'ProfileController@referrals');
    });

    $router->group(['prefix' => 'profile'], function () use ($router) {
        $router->get('', 'ProfileController@show')->name('profile');
        $router->get('referrals', 'ProfileController@referrals')->name('referrals');
    });

    $router->resource('advert/offers', 'Advert\OfferController', [
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
    $router->group(['prefix' => 'offers/{offerId}'], function () use ($router) {
        $router->get('activation_code', 'RedemptionController@getActivationCode')->name('redemption.code');
        $router->group(['prefix' => 'redemption'], function () use ($router) {
            $router->get('create', 'RedemptionController@create')->name('redemption.create');
            $router->post('', 'RedemptionController@redemption')->name('redemption.store');
            $router->get('{rid}', 'RedemptionController@show')->where('rid',
                '[a-z0-9-]+')->name('redemption.show');
        });
    });

    $router->resource('offers', 'User\OfferController', [
        'except' => [
            'create',
            'store',
            'update',
            'destroy'
        ]
    ]);

    $router->get('transactions/create', '\App\Http\Controllers\TransactionController@createTransaction')
           ->name('transactionCreate');
    $router->post('transactions', '\App\Http\Controllers\TransactionController@completeTransaction')
           ->name('transactionComplete');
    $router->get('transactions/{transactionId?}', '\App\Http\Controllers\TransactionController@listTransactions')
           ->where('reansactionId', '[0-9]+')
           ->name('transactionList');

    /**
     * Categories
     */
    $router->get('categories', 'CategoryController@index')
           ->name('categories');
    $router->get('categories/{uuid}', 'CategoryController@show')
           ->name('categories.show');
});

//---- Authorized users
