<?php namespace jorenvanhocht\Blogify\Config;

return array (

    /**
     * Array with all available character sets
     *
     */
    'char_sets' => [
        'hash'      => 'ABCDEFGHIJKLMNOPQRSTUVWabcdefghijklmnopqrstuvw0123456789',
        'password'  => 'ABCDEFGHIJKLMNOPQRSTUVWabcdefghijklmnopqrstuvw0123456789@&é#$%?!èà',
    ],

    /**
     * Information about the admin user
     * This will be used to seed the users table
     *
     */
    'admin_user'     => [
        'name'          => 'Van Hocht',
        'firstname'     => 'Joren',
        'username'      => 'admin',
        'email'         => "vanhochtjoren@gmail.com",
        'password'      => 'admin',
    ],

    /**
     * Defines how many items per
     * page you want to show
     *
     */
    'items_per_page' => 10,

    'upload_paths'  => [
        'posts' => [
            'images'    => 'uploads/posts/'
        ]
    ],

    'image_sizes'   => [
        'posts' => [500,null],
    ],
);