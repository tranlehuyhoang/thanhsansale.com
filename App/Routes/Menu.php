<?php
$menu = [
    [
        'name' => 'Hệ Thống',
        'icon' => '',
        'url' => '',
        'children' => [
            [
                'name' => 'Dashboard',
                'icon' => 'home',
                'url' => ADMIN_PATH.'',
                'children' => []
            ],
            [
                'name' => 'Identity Management',
                'icon' => 'users',
                'url' => '',
                'children' => [
                    [
                        'name' => 'Tài khoản',
                        'icon' => '',
                        'url' => ADMIN_PATH.'/user',
                        'children' => []
                    ],
                    [
                        'name' => 'Tài khoản chưa kích hoạt',
                        'icon' => '',
                        'url' => ADMIN_PATH.'/user/not-active',
                        'children' => []
                    ]
                ]
            ],
            [
                'name' => 'Cài Đặt',
                'icon' => 'settings',
                'url' => ADMIN_PATH.'/setting',
                'children' => []
            ],
            // bank
            [
                'name' => 'Ngân Hàng',
                'icon' => 'credit-card',
                'url' => ADMIN_PATH.'/bank',
                'children' => []
            ],
        ]
    ],
    [
        'name' => 'Vận Hành',
        'icon' => '',
        'url' => '',
        'children' => [
            [
                'name' => 'Quản lý trang',
                'icon' => 'file-text',
                'url' => ADMIN_PATH.'/trang',
                'children' => []
            ],
            [
                'name' => 'Quản lý tin',
                'icon' => 'file-text',
                'url' => ADMIN_PATH.'/blog',
                'children' => []
            ],
            [
                'name' => 'Cấu hình shop',
                'icon' => 'align-center',
                'url' => ADMIN_PATH.'/category',
                'children' => []
            ],
            // order
            [
                'name' => 'Đơn Hàng',
                'icon' => 'shopping-cart',
                'url' => ADMIN_PATH.'/order',
                'children' => []
            ],
            // payment transaction
            [
                'name' => 'Lịch sử giao dịch',
                'icon' => 'credit-card',
                'url' => ADMIN_PATH.'/payment-transaction',
                'children' => []
            ],
            [
                'name' => 'Lịch sử rút tiền',
                'icon' => 'dollar-sign',
                'url' => ADMIN_PATH.'/history-transaction',
                'children' => []
            ],

            // notification
            [
                'name' => 'Thông Báo',
                'icon' => 'bell',
                'url' => ADMIN_PATH.'/notification',
                'children' => []
            ],
        ]
        ],
    // tools
    [
        'name' => 'Công Cụ',
        'icon' => '',
        'url' => '',
        'children' => [
            [
                'name' => 'Shopee',
                'icon' => 'shopping-bag',
                'url' => ADMIN_PATH.'/tools/shopee',
                'children' => []
            ],
            // [
            //     'name' => 'Tiktok Shop',
            //     'icon' => 'shopping-bag',
            //     'url' => ADMIN_PATH.'/tools/tiktok',
            //     'children' => []
            // ],
            [
                'name' => 'Lazada',
                'icon' => 'shopping-bag',
                'url' => ADMIN_PATH.'/tools/lazada',
                'children' => []
            ],
        ],
    ],
];
