<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pagination ViewHelpers',
    'description' => '',
    'category' => 'plugin',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Georg Ringer',
    'author_email' => 'mail@ringer.it',
    'version' => '0.0.2',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-13.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
            'numbered_pagination' => '1.0.1-2.99.99'
        ],
    ],
];
