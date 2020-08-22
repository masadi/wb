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
            'instrumen' => 'c,r,u,d',
            'news'  => 'c,r,u,d',
            'profile' => 'r,u',
            'jawaban' => 'c,r,u,d',
        ],
        'author' => [
            'news'  => 'c,r,u,d',
            'profile' => 'r,u',
            'instrumen' => 'r',
            'jawaban' => 'r',
        ],
        'menteri' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'r',
        ],
        'dirjen' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'r',
        ],
        'direktur' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'r',
        ],
        'kabid' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'r',
        ],
        'kasie' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'r',
        ],
        'dindik' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'r',
        ],
        'cabdin' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'r',
        ],
        'sekolah' => [
            'profile' => 'r,u',
            'referensi' => 'r,c,u,d',
            'instrumen' => 'r',
            'jawaban' => 'c,r,u,d',
        ],
        'pengawas' => [
            'profile' => 'r,u',
            'verifikasi' => 'r',
            'referensi' => 'r',
            'pengawas' => 'r,c,u,d',
            'jawaban' => 'c,r,u,d',
        ],
        'penjamin_mutu' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'verifikasi' => 'r,c,u,d',
            'jawaban' => 'r',
        ],
        'ptk' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'c,r,u,d',
        ],
        'pd' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'c,r,u,d',
        ],
        'komite' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'c,r,u,d',
        ],
        'dudi' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'c,r,u,d',
        ],
        'responden' => [
            'profile' => 'r,u',
            'referensi' => 'r',
            'instrumen' => 'r',
            'jawaban' => 'c,r,u,d',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
