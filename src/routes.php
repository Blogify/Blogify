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
        Route::get('users/overview/{trashed?}', [
            'as'    => 'admin.users.overview',
            'uses'  => 'UserController@index',
        ]);


        /**
         *
         * Post routes
         */
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
        Route::get('posts/overview/{trashed?}', [
            'as'    => 'admin.posts.overview',
            'uses'  => 'PostsController@index',
        ]);

        Route::resource('categories', 'CategoriesController');

        Route::resource('tags', 'TagsController', [
            'except'    => 'store', 'update'
        ]);
        Route::post('tags', [
            'as'    => 'admin.tags.store',
            'uses'  => 'TagsController@storeOrUpdate'
        ]);
        Route::get('tags/overview/{trashed?}', [
            'as'    => 'admin.tags.overview',
            'uses'  => 'TagsController@index',
        ]);

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

            Route::post('autosave', [
                'as'    => 'admin.api.autosave',
                'uses'  => 'ApiController@autoSave',
            ]);
        });

    });

});