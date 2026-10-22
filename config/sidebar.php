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
        'title' => 'Toko Management',
        'icon' => 'fas-store',
        'route' => 'tokos.index',
        'active' => 'tokos*',
        'permission' => 'view-tokos',
    ],
    [
        'title' => 'Kasir',
        'icon' => 'fas-cash-register',
        'route' => 'kasir.dashboard',
        'active' => 'kasir*',
        'permission' => 'kasir',
    ],
    [
        'title' => 'Penjualan Management',
        'icon' => 'fas-chart-line',
        'route' => 'penjualans.index',
        'active' => 'penjualans*',
        'permission' => 'view-penjualans',
    ],
    [
        'title' => 'Produk Management',
        'icon' => 'fas-boxes-stacked',
        'active' => ['produks*', 'kategories*', 'satuans*'],
        'permission' => ['view-produks', 'view-kategories', 'view-satuans'],
        'children' => [
            [
                'title' => 'Produk',
                'icon' => 'fas-box',
                'route' => 'produks.index',
                'active' => 'produks*',
                'permission' => 'view-produks',
            ],
            [
                'title' => 'Kategori',
                'icon' => 'fas-tags',
                'route' => 'kategories.index',
                'active' => 'kategories*',
                'permission' => 'view-kategories',
            ],
            [
                'title' => 'Satuan',
                'icon' => 'fas-scale-balanced',
                'route' => 'satuans.index',
                'active' => 'satuans*',
                'permission' => 'view-satuans',
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
];