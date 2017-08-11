<?php

namespace Jorenvh\Share\Providers;

use Illuminate\Support\ServiceProvider;

class BlogifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang/', 'blogify');

        $this->publishes([
            __DIR__ . '/../../config/blogify.php' => config_path('blogify.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../resources/lang/' => resource_path('lang/vendor/blogify')
        ], 'translations');

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/blogify.php', 'blogify');
    }
}
