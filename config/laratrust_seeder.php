<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [
            'users' => 'c,r,u,d',
            'referensi' => 'c,r,u,d',
            'news'  => 'c,r,u,d',
        ],
        'dinas' => [
            'users' => 'c,r,u,d',
            'referensi' => 'c,r,u,d',
            'news'  => 'c,r,u,d',
        ],
        'sekolah' => [
            'users' => 'r',
            'referensi' => 'c,r,u,d',
            'news'  => 'r',
        ],
        'author' => [
            'news'  => 'c,r,u,d',
        ],
        'pengawas' => [
            'users' => 'r',
            'referensi' => 'r',
            'news'  => 'r',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
