<?php

namespace jorenvanhocht\Blogify\Repositories\Comment;

use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{


    /**
     * Register the service provider
     */
    public function register()
    {
        $this->app->bind('jorenvanhocht\Blogify\Repositories\Comment\CommentInterface', 'jorenvanhocht\Blogify\Repositories\Comment\EloquentCommentRepository');
    }

}