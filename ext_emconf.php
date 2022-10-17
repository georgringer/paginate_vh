<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pagination ViewHelpers',
    'description' => '',
    'category' => 'plugin',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Georg Ringer',
    'author_email' => 'mail@ringer.it',
    'version' => '0.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-12.1.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
            'numbered_pagination' => '1.0.1-1.99.99'
        ],
    ],
];
