<?php
/**
 * phire-tags routes
 */
return [
    '/tag/*' => [
        'controller' => 'Phire\Tags\Controller\IndexController',
        'action'     => 'index'
    ],
    APP_URI => [
        '/tags[/]' => [
            'controller' => 'Phire\Tags\Controller\TagController',
            'action'     => 'index',
            'acl'        => [
                'resource'   => 'tags',
                'permission' => 'index'
            ]
        ],
        '/tags/add[/]' => [
            'controller' => 'Phire\Tags\Controller\TagController',
            'action'     => 'add',
            'acl'        => [
                'resource'   => 'tags',
                'permission' => 'add'
            ]
        ],
        '/tags/edit/:id' => [
            'controller' => 'Phire\Tags\Controller\TagController',
            'action'     => 'edit',
            'acl'        => [
                'resource'   => 'tags',
                'permission' => 'edit'
            ]
        ],
        '/tags/remove[/]' => [
            'controller' => 'Phire\Tags\Controller\TagController',
            'action'     => 'remove',
            'acl'        => [
                'resource'   => 'tags',
                'permission' => 'remove'
            ]
        ]
    ]
];
