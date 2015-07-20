<?php

namespace jorenvanhocht\Blogify\Repositories\Category;

use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{


    /**
     * Register the service provider
     */
    public function register()
    {
        $this->app->bind('jorenvanhocht\Blogify\Repositories\Category\CategoryInterface', 'jorenvanhocht\Blogify\Repositories\Category\EloquentCategoryRepository');
    }

}