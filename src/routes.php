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

        Route::resource('posts', 'PostsController', [
            'except' => 'store', 'update'
        ]);
        Route::post('posts', [
           'as'     => 'admin.posts.store',
            'uses'  => 'PostsController@store'
        ]);
        Route::post('posts/image/upload', [
            'as'    => 'admin.posts.uploadImage',
            'uses'  => 'PostsController@uploadImage',
        ]);
        Route::get('posts/get/trashed', [
            'as'    => 'admin.posts.trashed',
            'uses'  => 'PostsController@trashed'
        ]);

        Route::resource('categories', 'CategoriesController');

        Route::resource('tags', 'TagsController');

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

            Route::get('slug/checkIfSlugIsUnique/{slug}', [
                'as'    => 'admin.api.slug.checkIfUnique',
                'uses'  => 'ApiController@checkIfSlugIsUnique',
            ]);
        });

    });

});