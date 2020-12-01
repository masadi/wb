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
            'repot' => 'r',
            'users' => 'c,r,u,d',
            'referensi' => 'c,r,u,d',
            'news'  => 'c,r,u,d',
            'verifikasi' => 'c,r,u,d',
            'pengesahan' => 'c,r,u,d',
        ],
        'direktorat' => [
            'repot' => 'r',
            'users' => 'r',
            'referensi' => 'c,r,u,d',
            'news'  => 'c,r,u,d',
            'pengesahan' => 'c,r,u,d',
        ],
        'penjamin_mutu' => [
            'repot' => 'r',
            'referensi' => 'r,u',
            //'news'  => 'c,r,u,d',
            'verifikasi' => 'c,r,u,d',
        ],
        'sekolah' => [
            'repot' => 'r',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'c,r,u',
        ],
        'author' => [
            'news'  => 'c,r,u,d',
        ],
        'disprov' => [
            'repot' => 'r',
        ],
        'pengawas' => [
            'repot' => 'r',
        ],
        'pendamping' => [
            'repot' => 'c,r,u,d',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
