<?php

if ( ! function_exists('blogify'))
{
    /**
     * Get the Blogify binding
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    function blogify()
    {
        return app('jorenvanhocht.blogify');
    }
}

if ( ! function_exists('generateFullName'))
{
    function generateFullName($firstname, $lastname)
    {
        return $firstname . ' ' . $lastname;
    }
}