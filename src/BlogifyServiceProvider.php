<?php namespace jorenvanhocht\Blogify;


use Illuminate\Support\ServiceProvider;

class BlogifyServiceProvider extends ServiceProvider {

    /**
     * Register the service provider
     *
     * @return Blogify
     */
    public function register()
    {
        $this->app->bind('jorenvanhocht.blogify', function()
        {
            $db = $this->app['db'];
            return new Blogify($db);
        });

        $this->app['router']->middleware('BlogifyAdminAuthenticate', 'jorenvanhocht\Blogify\Middleware\BlogifyAdminAuthenticate');
        $this->app['router']->middleware('BlogifyVerifyCsrfToken', 'jorenvanhocht\Blogify\Middleware\BlogifyVerifyCsrfToken');
        $this->app['router']->middleware('CanEditPost', 'jorenvanhocht\Blogify\Middleware\CanEditPost');
        $this->app['router']->middleware('DenyIfBeingEdited', 'jorenvanhocht\Blogify\Middleware\DenyIfBeingEdited');
        $this->app['router']->middleware('BlogifyGuest', 'jorenvanhocht\Blogify\Middleware\Guest');
        $this->app['router']->middleware('HasAdminOrAuthorRole', 'jorenvanhocht\Blogify\Middleware\HasAdminOrAuthorRole');
        $this->app['router']->middleware('HasAdminRole', 'jorenvanhocht\Blogify\Middleware\HasAdminRole');
        $this->app['router']->middleware('RedirectIfAuthenticated', 'jorenvanhocht\Blogify\Middleware\RedirectIfAuthenticated');
        $this->app['router']->middleware('IsOwner', 'jorenvanhocht\Blogify\Middleware\IsOwner');
    }

    /**
     * Load the resources
     *
     */
    public function boot()
    {
        // Load the routes for the package
        include __DIR__ . '/routes.php';

        // Publish the migration files for the package
        $this->publishes([
            __DIR__ . '/migrations/' => base_path('/database/migrations/')
        ], 'migrations' );

        // Publish the seed files for the package
        $this->publishes([
            __DIR__ . '/seeds/' => base_path('/database/seeds/')
        ], 'seeds' );

        // Publish the config files for the package
        $this->publishes([
            __DIR__.'/../config' => config_path('blogify/'),
        ], 'config');

        $this->publishes([
            __DIR__.'/public/assets' => base_path('/public/assets/blogify/'),
            __DIR__.'/public/ckeditor' => base_path('public/ckeditor/'),
            __DIR__.'/public/datetimepicker' => base_path('public/datetimepicker/')
        ], 'assets');

        $this->loadViewsFrom(__DIR__.'/views', 'blogify');

        // Make the config file accessible even when the files are not published
        $this->mergeConfigFrom(__DIR__.'/../config/blogify.php', 'blogify');

        $this->loadTranslationsFrom(__DIR__.'/lang/', 'blogify');
    }

}