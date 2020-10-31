<?php

return [
  'admin' => 
  [
    0 => 
    [
      'allowed' => true,
      'entities' => 
      [
        'content' => 
        [
          0 => 'insert',
          1 => 'update',
          2 => 'delete',
        ],
        'admin' => 
        [
          0 => 'insert',
          1 => 'update',
          2 => 'delete',
        ],
        'readme.txt' => 
        [
          0 => 'update',
          1 => 'delete',
        ],
      ],
    ],
  ],
  'author' => 
  [
    0 => 
    [
      'allowed' => true,
      'entities' => 
      [
        'content' => 
        [
          0 => 'insert',
        ],
        'readme.txt' => 
        [
        ],
      ],
    ],
  ],
  'user' => 
  [
    0 => 
    [
      'allowed' => true,
      'entities' => 
      [
        'content' => 
        [
        ],
        'readme.txt' => 
        [
        ],
      ],
    ],
  ],
  'guest' => 
  [
    0 => 
    [
      'allowed' => true,
      'entities' => 
      [
        'readme.txt' => 
        [
        ],
      ],
    ],
  ],
];