<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'fas-house',
        'route' => 'dashboard',
        'active' => 'dashboard*',
        'permission' => null, // Bebas diakses semua user terautentikasi
    ],
    [
        'title' => 'User Management',
        'icon' => 'fas-users',
        'active' => ['users*', 'roles*', 'permissions*'],
        'permission' => ['view-users', 'view-roles', 'view-permissions'], // Parent muncul jika punya salah satu
        'children' => [
            [
                'title' => 'Users',
                'icon' => 'fas-user',
                'route' => 'users.index',
                'active' => 'users*',
                'permission' => 'view-users',
            ],
            [
                'title' => 'Roles',
                'icon' => 'fas-shield',
                'route' => 'roles.index',
                'active' => 'roles*',
                'permission' => 'view-roles',
            ],
            [
                'title' => 'Permissions',
                'icon' => 'fas-key',
                'route' => 'permissions.index',
                'active' => 'permissions*',
                'permission' => 'view-permissions',
            ],
        ],
    ],
    [
        'title' => 'Work Spaces Management',
        'icon' => 'fas-building',
        'active' => ['work-spaces*', 'work-space-members*'],
        'permission' => ['view-work-spaces', 'view-work-space-members'],
        'children' => [
            [
                'title' => 'Work Spaces',
                'icon' => 'fas-building',
                'route' => 'work-spaces.index',
                'active' => 'work-spaces*',
                'permission' => 'view-work-spaces',
            ],
            [
                'title' => 'Work Space Members',
                'icon' => 'fas-users',
                'route' => 'work-space-members.index',
                'active' => 'work-space-members*',
                'permission' => 'view-work-space-members',
            ],
        ],
    ],
    [
        'title' => 'Finance Management',
        'icon' => 'fas-money-bill',
        'active' => [
            'finance-categories*',
            'finance-transactions*',
            'finance-merchants*',
            'finance.dashboard*'
        ],
        'permission' => [
            'view-finance-categories',
            'view-finance-merchants',
            'view-finance-transactions',
        ],
        'children' => [
            [
                'title' => 'Dashboard',
                'icon' => 'fas-tachometer-alt',
                'route' => 'finance.dashboard',
                'active' => 'finance.dashboard*',
                'permission' => 'view-finance-transactions',
            ],
            [
                'title' => 'Finance Categories',
                'icon' => 'fas-building',
                'route' => 'finance-categories.index',
                'active' => 'finance-categories*',
                'permission' => 'view-finance-categories',
            ],
            [
                'title' => 'Finance Merchants',
                'icon' => 'fas-store',
                'route' => 'finance-merchants.index',
                'active' => 'finance-merchants*',
                'permission' => 'view-finance-merchants',
            ],
            [
                'title' => 'Finance Transaction',
                'icon' => 'fas-money-bill',
                'route' => 'finance-transactions.index',
                'active' => 'finance-transactions*',
                'permission' => 'view-finance-transactions',
            ],
        ],
    ],

];
