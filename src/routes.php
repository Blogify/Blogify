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

Route::group(['prefix' => 'admin'], function()
{
    // Login
    Route::get('login', [
        'as'    =>  'admin.login',
        'uses'  =>  'jorenvanhocht\Blogify\Controllers\Admin\LoginController@index'
    ]);

    Route::post('login/post', [
        'as'    =>  'admin.login.post',
        'uses'  =>  'jorenvanhocht\Blogify\Controllers\Admin\LoginController@login'
    ]);

    Route::group(['middleware' => 'auth'], function()
    {

        // Dashboard
        Route::get('/', function() {
            dd('index of the admin page');
        });

        // Logout
        Route::get('logout', [
            'as'    =>  'admin.logout',
            'uses'  =>  'jorenvanhocht\Blogify\Controllers\Admin\LoginController@logout'
        ]);
    });

});