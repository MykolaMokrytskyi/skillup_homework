<?php

return [
    'admin' => [
        0 => [
            'allowed' => true,
            'entities' => [
                'content',
                'admin',
                'readme.txt',
            ],
        ],
    ],
    'user' => [
        0 => [
            'allowed' => true,
            'entities' => [
                'content',
                'readme.txt',
            ],
        ],
    ],
    'guest' => [
        0 => [
            'allowed' => true,
            'entities' => [
                'readme.txt',
            ],
        ],
    ],
    'author' => [
        0 => [
            'allowed' => true,
            'entities' => [
                'content',
                'readme.txt',
            ],
        ],
    ],
];