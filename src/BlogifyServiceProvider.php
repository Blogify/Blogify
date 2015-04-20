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
            return new Blogify;
        });
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
            __DIR__.'/config' => config_path('blogify/'),
        ], 'config');

        /*$this->publishes([
            __DIR__.'/views' => base_path('/resources/views/blogify/')
        ], 'views');*/

        $this->publishes([
            __DIR__.'/public/assets' => base_path('/public/assets/blogify/'),
            __DIR__.'/public/ckeditor' => base_path('public/ckeditor/'),
            __DIR__.'/public/datetimepicker' => base_path('public/datetimepicker/')
        ], 'assets');

        $this->loadViewsFrom(__DIR__.'/views', 'blogify');

        // Make the config file accessible even when the files are not published
        $this->mergeConfigFrom(__DIR__.'/config/blogify.php', 'blogify');

        $this->loadTranslationsFrom(__DIR__.'/lang/', 'blogify');
    }

}