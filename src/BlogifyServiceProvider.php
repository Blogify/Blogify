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
            __DIR__ . '/Migrations/' => base_path('/database/migrations/')
        ], 'migrations' );
    }

}