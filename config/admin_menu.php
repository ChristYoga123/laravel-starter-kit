<?php
return [
    'admin' => [
        [
            'title' => 'Dashboard',
            'icon' => 'ti ti-home',
            'url' => 'admin/dashboard',
            'permission' => ''
        ],
        [
            'title' => 'Settings',
            'icon' => 'ti ti-settings',
            'url' => 'admin/settings/*',
            'permission' => ['admins.admin.index', 'roles.admin.index'],
            'sub' => [
                [
                    'title' => 'Administrator',
                    'url' => 'admin/settings/administrators',
                    'permission' => 'admins.admin.index'
                ],
                [
                    'title' => 'Role',
                    'url' => 'admin/settings/roles',
                    'permission' => 'roles.admin.index'
                ]
            ]
        ]
    ]
];
