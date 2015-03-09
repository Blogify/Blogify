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

        $this->publishes([
            __DIR__.'/views' => base_path('/resources/views/blogify/')
        ], 'views');

        // Make the config file accessible even when the files are not published
        $path_to_config_file = __DIR__.'/config/blogify.php';
        $config = $this->app['files']->getRequire($path_to_config_file);
        $this->app['config']->set('blogify::custom', $config);
    }

}