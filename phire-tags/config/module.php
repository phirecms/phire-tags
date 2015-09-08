<?php
/**
 * Module Name: phire-tags
 * Author: Nick Sagona
 * Description: This is the tags module for Phire CMS 2, to be used in conjunction with the Content and Media modules
 * Version: 1.0
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
        ],
        'summary_length'  => 150,
        'show_total'      => true
    ]
];
