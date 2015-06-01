<?php

return [

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
     * ENV VARIBALES MAKEN
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
    'items_per_page' => 20,

    /**
     * Paths where uploaded images
     * wil be placed
     *
     */
    'upload_paths'  => [
        'posts' => [
            'images'    => 'uploads/posts/',
        ],
        'profiles' => [
            'profilepictures' => 'uploads/profilepictures/',
        ],
    ],

    /**
     * The size where an uploaded
     * image will be resized to
     *
     */
    'image_sizes'   => [
        'posts' => [500,null],
        'profilepictures' => [100, 100],
    ],

    /**
     * Define if new comments
     * first needs approval
     *
     */
    'approve_comments_first' => true,
];