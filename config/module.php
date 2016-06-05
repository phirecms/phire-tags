<?php
/**
 * Module Name: phire-tags
 * Author: Nick Sagona
 * Description: This is the tags module for Phire CMS 2, to be used in conjunction with the Content and Media modules
 * Version: 1.1
 */
return [
    'phire-tags' => [
        'prefix'     => 'Phire\Tags\\',
        'src'        => __DIR__ . '/../src',
        'routes'     => include 'routes.php',
        'resources'  => include 'resources.php',
        'forms'      => include 'forms.php',
        'nav.phire'  => [
            'tags' => [
                'name' => 'Tags',
                'href' => '/tags',
                'acl' => [
                    'resource'   => 'tags',
                    'permission' => 'index'
                ],
                'attributes' => [
                    'class' => 'tags-nav-icon'
                ]
            ]
        ],
        'models' => [
            'Phire\Tags\Model\Tag' => []
        ],
        'events' => [
            [
                'name'     => 'app.route.pre',
                'action'   => 'Phire\Tags\Event\Tag::bootstrap',
                'priority' => 1000
            ],
            [
                'name'     => 'app.send.pre',
                'action'   => 'Phire\Tags\Event\Tag::setTemplate',
                'priority' => 1000
            ],
            [
                'name'     => 'app.send.pre',
                'action'   => 'Phire\Tags\Event\Tag::getAll',
                'priority' => 1000
            ],
            [
                'name'     => 'app.send.pre',
                'action'   => 'Phire\Tags\Event\Tag::save',
                'priority' => 1000
            ],
            [
                'name'     => 'app.send.pre',
                'action'   => 'Phire\Tags\Event\Tag::delete',
                'priority' => 1000
            ],
            [
                'name'     => 'app.send.pre',
                'action'   => 'Phire\Tags\Event\Tag::init',
                'priority' => 1000
            ]
        ],
        'filters'          => [
            'strip_tags' => null,
            'substr'     => [0, 150]
        ],
        'show_total'      => true
    ]
];
