<?php

// All default package route will be defined here

///////////////////////////////////////////////////////////////////////////
// public routes
///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Logged in user routes
///////////////////////////////////////////////////////////////////////////

Route::group(['prefix' => 'auth'], function()
{

    Route::group(['middleware' => 'auth'], function()
    {

    });

});


///////////////////////////////////////////////////////////////////////////
// Admin routes
///////////////////////////////////////////////////////////////////////////

$admin = [
    'prefix'    => 'admin',
    'namespace' =>'jorenvanhocht\Blogify\Controllers\Admin',
];


Route::group($admin, function()
{
    Route::group( ['middleware' => 'jorenvanhocht\Blogify\Middleware\Guest'], function(){
        // Login
        Route::get('login', [
            'as'    =>  'admin.login',
            'uses'  =>  'AuthController@index'
        ]);

        Route::post('login/post', [
            'as'    =>  'admin.login.post',
            'uses'  =>  'AuthController@login'
        ]);
    });

    Route::group(['middleware' => 'jorenvanhocht\Blogify\Middleware\BlogifyAdminAuthenticate'], function()
    {
        // Dashboard
        Route::get('/', [
            'as'    => 'admin.dashboard',
            'uses'  => 'DashboardController@index'
        ]);

        // Logout
        Route::get('logout', [
            'as'    =>  'admin.logout',
            'uses'  =>  'AuthController@logout'
        ]);

        /**
         * User routes
         *
         */

        Route::resource('users', 'UserController', ['except' => ''] );
        Route::get('users/get/trashed', [
            'as'    => 'admin.users.trashed',
            'uses'  => 'UserController@trashed'
        ]);

        Route::resource('posts', 'PostsController');

        ///////////////////////////////////////////////////////////////////////////
        // API routes
        ///////////////////////////////////////////////////////////////////////////

        $api = [
            'prefix' => 'api',
        ];

        Route::group($api, function()
        {
            Route::get('sort/{table}/{column}/{order}/{trashed?}', [
                'as'    => 'admin.api.sort',
                'uses'  => 'ApiController@sort'
            ]);
        });

    });

});