<?php

return [
    'role_structure' => [
        'admin' => [
            'users' => 'c,r,u,d',
            'jobs' => 'c,r,u,d',
            'job-categories' => 'c,r,u,d',
            'job-skills' => 'c,r,u,d',
            'job-applications' => 'c,r,u,d',
            'application-message' => 'c,r,u,d',
            'payouts' => 'c,r,u,d',
            'logs' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'manager' => [
            'users' => 'c,r,u,d',
            'jobs' => 'c,r,u,d',
            'job-categories' => 'c,r,u,d',
            'job-skills' => 'c,r,u,d',
            'job-applications' => 'c,r,u,d',
            'application-message' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'user' => [
            'profile' => 'r,u',
            'application-message' => 'c,r,u,d',
        ],
    ],
//    'permission_structure' => [
//        'cru_user' => [
//            'profile' => 'c,r,u'
//        ],
//    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
