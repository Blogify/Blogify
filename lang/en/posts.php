<?php

return [

    'overview' => [
        'page_title'    => 'Posts',

        'table_head'    => [
            'title_active'     => 'Posts',
            'title_trashed'    => 'Deleted posts',
            'hash'             => 'Hash',
            'title'            => 'Title',
            'slug'             => 'Slug',
            'status'           => 'Status',
            'publish_date'     => 'Publish date',
            'actions'          => 'Actions'
        ],

        'no_results'    => 'No posts found',

        'links'         => [
            'trashed'           => 'Show deleted posts',
            'active'            => 'Show active posts',
        ],
    ],

    'form'  => [
        'page'  => [
            'title' => [
                'create' => 'Add new post',
                'update' => 'Edit post',
            ]
        ],

        'title' => [
            'placeholder' => 'Enter title here',
        ],

        'slug' => [
            'placeholder' => 'Enter slug here',
        ],

        'publish' => [
            'title'         => 'Publish',
            'status'        => [
                'label'         => 'Status:',
            ],

            'visibility'    => [
                'label'         => 'Visibility:',
            ],

            'password'    => [
                'label'         => 'Password:',
            ],

            'publish_date'  => [
                'label'         => 'Publish date:'
            ],

            'save_button'   => [
                'value'         => 'Save post',
            ],
        ],

        'reviewer'  => [
            'title'     => 'Reviewer',
        ],

        'category'  => [
            'title'         => 'Category',
            'placeholder'   => 'Create new category',
            'no_results'    => 'No categories found',
        ],

        'tags'      => [
            'title'         => 'tags',
            'placeholder'   => 'Add tags...',
            'help_block'    => 'Separate tags with commas',
        ],
    ],

    'validation'    => [
        'required'  => 'You have to fill in at least one tag',
        'min'       => 'One tag needs to have at least 2 characters',
        'max'       => 'One tag can maximum have 45 characters',
    ],

    'notify'        => [
        'being_edited'  => 'Post is all ready being edited by :name',
    ]
];