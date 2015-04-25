<?php

return [
    'overview' => [
        'page_title'    => 'Users',

        'table_head'    => [
            'title_active'     => 'Active users',
            'title_trashed'    => 'Deleted users',
            'hash'             => 'Hash',
            'name'             => 'Name',
            'firstname'        => 'First name',
            'username'         => 'Username',
            'email'            => 'E-mail',
            'role'             => 'Role',
            'actions'          => 'Actions'
        ],

        'no_results'    => 'No users found',

        'links'         => [
            'trashed'           => 'Show deleted users',
            'active'            => 'Show active users',
        ],
    ],

    'form'  => [
        'page_title_create'    => 'Create new user',
        'page_title_edit'      => 'Edit user',

        'name'                 => [
            'label' => 'Name:',
        ],

        'firstname'            => [
            'label' => 'First name:',
        ],

        'email'                => [
            'label' => 'E-mail:',
        ],

        'role'                 => [
            'label' => 'Role:',
        ],

        'submit_button'        => [
            'value' => 'Save user',
        ],
    ],
];