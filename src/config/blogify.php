<?php namespace jorenvanhocht\Blogify\Config;

return array (

    /**
     * Array with all available character sets
     *
     */
    'char_sets' => [
        'hash'   => 'ABCDEFGHIJKLMNOPQRSTUVWabcdefghijklmnopqrstuvw0123456789'
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
    ]

);