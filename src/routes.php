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
    // Login
    Route::get('login', [
        'as'    =>  'admin.login',
        'uses'  =>  'LoginController@index'
    ]);

    Route::post('login/post', [
        'as'    =>  'admin.login.post',
        'uses'  =>  'LoginController@login'
    ]);

    Route::group(['middleware' => 'auth'], function()
    {

        // Dashboard
        Route::get('/', function() {
            return View('blogify.admin.dashboard');
        });

        // Logout
        Route::get('logout', [
            'as'    =>  'admin.logout',
            'uses'  =>  'LoginController@logout'
        ]);
    });

});