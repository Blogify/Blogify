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
    'prefix' => 'admin',
    'namespace'=>'jorenvanhocht\Blogify\Controllers\Admin',
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
        Route::get('/', function() {
            return View('blogify::admin.home');
        });

        // Logout
        Route::get('logout', [
            'as'    =>  'admin.logout',
            'uses'  =>  'AuthController@logout'
        ]);

        Route::resource('users', 'UserController');

    });

});