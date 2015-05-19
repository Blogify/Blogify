<?php

// All default package route will be defined here

///////////////////////////////////////////////////////////////////////////
// public routes
///////////////////////////////////////////////////////////////////////////

/*Route::resource('blog', 'jorenvanhocht\Blogify\Example\Controllers\BlogController');
Route::get('blog/archive/{year}/{month}', [
    'as'    => 'blog.archive',
    'uses'  => 'jorenvanhocht\Blogify\Example\Controllers\BlogController@archive'
]);
Route::get('blog/category/{category}', [
    'as'    => 'blog.category',
    'uses'  => 'jorenvanhocht\Blogify\Example\Controllers\BlogController@category',
]);
Route::post('comments', [
    'as' => 'comments.store',
    'uses' => 'jorenvanhocht\Blogify\Example\Controllers\CommentsController@store'
]);*/

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
    Route::group( ['middleware' => 'BlogifyGuest'], function(){
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

    Route::group(['middleware' => 'BlogifyAdminAuthenticate'], function()
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
        Route::group(['middleware' => 'HasAdminRole'], function() {
            Route::resource('users', 'UserController', ['except' => '']);
            Route::get('users/overview/{trashed?}', [
                'as' => 'admin.users.overview',
                'uses' => 'UserController@index',
            ]);

            Route::resource('categories', 'CategoriesController');
            Route::get('categories/overview/{trashed?}', [
                'as' => 'admin.categories.overview',
                'uses' => 'CategoriesController@index',
            ]);
        });


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
        Route::get('posts/action/cancel/{hash?}', [
            'as'    => 'admin.posts.cancel',
            'uses'  => 'PostsController@cancel',
        ]);

        Route::group(['middleware' => 'HasAdminOrAuthorRole'], function(){
            Route::resource('tags', 'TagsController', [
                'except'    => 'store'
            ]);
            Route::post('tags', [
                'as'    => 'admin.tags.store',
                'uses'  => 'TagsController@storeOrUpdate'
            ]);
            Route::get('tags/overview/{trashed?}', [
                'as'    => 'admin.tags.overview',
                'uses'  => 'TagsController@index',
            ]);

            Route::get('comments/{revised?}', [
                'as'    => 'admin.comments.index',
                'uses'  => 'CommentsController@index'
            ]);
            Route::get('comments/changestatus/{hash}/{revised}', [
                'as'    => 'admin.comments.changeStatus',
                'uses'  => 'CommentsController@changeStatus'
            ]);
        });

        Route::resource('profile', 'ProfileController');

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