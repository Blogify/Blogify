<?php

return [

    'overview' => [
        'page_title'=> 'Artigos',

        'table_head' => [
            'title_active' => 'Artigos',
            'title_trashed' => 'Artigos removidos',
            'hash' => 'Código',
            'title' => 'Título',
            'slug' => 'URL amigável',
            'status' => 'Status',
            'publish_date' => 'Data de publicação',
            'actions' => 'Ações'
        ],

        'no_results' => 'Nenhum artigo encontrado',

        'links' => [
            'trashed' => 'Exibir artigos removidos',
            'active' => 'Exibir artigos ativos',
        ],
    ],

    'form' => [
        'page' => [
            'title' => [
                'create' => 'Adicionar novo artigo',
                'update' => 'Editar artigo',
            ],
        ],

        'title' => [
            'placeholder' => 'Defina o título aqui',
        ],

        'slug' => [
            'placeholder' => 'Defina a URL amigável aqui',
        ],

        'publish' => [
            'title' => 'Publicar',
            'status' => [
                'label' => 'Status:',
            ],

            'visibility' => [
                'label' => 'Visibilidade:',
            ],

            'password' => [
                'label' => 'Senha:',
            ],

            'publish_date' => [
                'label' => 'Data de publicação:'
            ],

            'save_button' => [
                'value' => 'Salvar artigo',
            ],
        ],

        'reviewer' => [
            'title' => 'Revisor',
        ],

        'category' => [
            'title' => 'Categoria',
            'placeholder' => 'Criar nova categoria',
            'no_results' => 'Nenhuma categoria encontrada',
        ],

        'tags' => [
            'title' => 'tags',
            'placeholder' => 'Adicionar tags...',
            'help_block' => 'Separe as tags por vírgula',
        ],
    ],

    'validation' => [
        'required' => 'Você deve definir pelo menos uma tag',
        'min' => 'A tag deve conter pelo menos 2 caracteres',
        'max' => 'A tag deve conter no máximo 45 caracteres',
    ],

    'notify' => [
        'being_edited' => 'Artigo já está sendo editado por :name',
    ]
];
