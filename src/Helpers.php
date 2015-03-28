<?php

if ( ! function_exists( 'blogify' ) )
{
    function blogify()
    {
        return $this->app->bind('blogify');
    }
}