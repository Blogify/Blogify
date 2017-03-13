<?php

// All default package route will be defined here
///////////////////////////////////////////////////////////////////////////
// public routes
///////////////////////////////////////////////////////////////////////////
$use_default_routes = config('blogify.blogify.enable_default_routes');

if ($use_default_routes)
{
    Route::group(['namespace' => 'App\Http\Controllers', 'middleware' => 'web'], function()
    {
        Route::resource('blog', 'BlogController', ['only' => ['index', 'show']]);
        Route::post('blog/{slug}', [
            'as'   => 'blog.confirmPass',
            'uses' => 'BlogController@show',
        ]);
        Route::get('blog/archive/{year}/{month}', [
            'as'   => 'blog.archive',
            'uses' => 'BlogController@archive'
        ]);
        Route::get('blog/category/{category}', [
            'as'   => 'blog.category',
            'uses' => 'BlogController@category',
        ]);
        Route::get('blog/protected/verify/{id}', [
            'as'   => 'blog.askPassword',
            'uses' => 'BlogController@askPassword'
        ]);
        Route::post('comments', [
            'as'   => 'comments.store',
            'uses' => 'CommentsController@store'
        ]);
        Route::get('press', [
            'as'   => 'blog.press',
            'uses' => 'BlogController@press'
        ]);
    });
}


///////////////////////////////////////////////////////////////////////////
// Admin routes
///////////////////////////////////////////////////////////////////////////

$admin = [
    'prefix'     => 'admin',
    'namespace'  => 'jorenvanhocht\Blogify\Controllers\Admin',
    'middleware' => 'web',
    'as'         => 'admin.'
];


Route::group($admin, function()
{

    Route::group(['middleware' => ['auth', 'isAdmin']], function()
    {
        // Dashboard
        Route::get('/', [
            'as'   => 'dashboard',
            'uses' => 'DashboardController@index'
        ]);

        /**
         * User routes
         *
         */
        Route::group(['middleware' => 'HasAdminRole'], function()
        {
            Route::resource('users', 'UserController', ['except' => '']);
            Route::get('users/overview/{trashed?}', [
                'as'   => 'users.overview',
                'uses' => 'UserController@index',
            ]);
            Route::get('users/{id}/restore', [
                'as'   => 'users.restore',
                'uses' => 'UserController@restore'
            ]);

            Route::resource('categories', 'CategoriesController');
            Route::get('categories/overview/{trashed?}', [
                'as'   => 'categories.overview',
                'uses' => 'CategoriesController@index',
            ]);
            Route::get('categories/{id}/restore', [
                'as'   => 'categories.restore',
                'uses' => 'CategoriesController@restore'
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
            'as'   => 'posts.store',
            'uses' => 'PostsController@store'
        ]);
        Route::post('posts/image/upload', [
            'as'   => 'posts.uploadImage',
            'uses' => 'PostsController@uploadImage',
        ]);
        Route::get('posts/overview/{trashed?}', [
            'as'   => 'posts.overview',
            'uses' => 'PostsController@index',
        ]);
        Route::get('posts/action/cancel/{id?}', [
            'as'   => 'posts.cancel',
            'uses' => 'PostsController@cancel',
        ]);
        Route::get('posts/{id}/restore', [
            'as'   => 'posts.restore',
            'uses' => 'PostsController@restore'
        ]);

        Route::resource('tags', 'TagsController', [
            'except' => 'store'
        ]);
        Route::post('tags', [
            'as'   => 'tags.store',
            'uses' => 'TagsController@storeOrUpdate'
        ]);
        Route::get('tags/overview/{trashed?}', [
            'as'   => 'tags.overview',
            'uses' => 'TagsController@index',
        ]);
        Route::get('tags/{id}/restore', [
            'as'   => 'tags.restore',
            'uses' => 'TagsController@restore'
        ]);

        Route::get('comments/{revised?}', [
            'as'   => 'comments.index',
            'uses' => 'CommentsController@index'
        ]);
        Route::get('comments/changestatus/{id}/{revised}', [
            'as'   => 'comments.changeStatus',
            'uses' => 'CommentsController@changeStatus'
        ]);

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
                'as'   => 'api.sort',
                'uses' => 'ApiController@sort'
            ]);

            Route::get('slug/checkIfSlugIsUnique/{slug}', [
                'as'   => 'api.slug.checkIfUnique',
                'uses' => 'ApiController@checkIfSlugIsUnique',
            ]);

            Route::post('autosave', [
                'as'   => 'api.autosave',
                'uses' => 'ApiController@autoSave',
            ]);

            Route::get('tags/{id}', [
                'as'   => 'api.tags',
                'uses' => 'ApiController@getTag'
            ]);
        });
    });
});
