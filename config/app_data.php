<?php

/**
 * Custom data for app
 */
return [
    'admin_nav' => [
        [
            'href' => 'admin.home',
            'icon' => 'fas fa-home',
            'title' => 'Главная страница',
            'can' => 'home_access',
        ],
        [
            'href' => '#',
            'icon' => 'fas fa-list-ul',
            'title' => 'Монеты',
            'can' => ['algorithm_access', 'coin_access'],
            'child' => [
                [
                    'href' => 'admin.coins.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Монеты',
                    'can' => 'coin_access',
                ],
                [
                    'href' => 'admin.algorithms.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Алгоритмы',
                    'can' => 'algorithm_access',
                ],
            ]
        ],
        [
            'href' => 'admin.ratings.index',
            'icon' => 'fas fa-star',
            'title' => 'Рейтинг',
            'can' => 'rating_access',
        ],
        [
            'href' => 'admin.mining.index',
            'icon' => 'fas fa-gem',
            'title' => 'Майнинг',
            'can' => 'mining_access',
        ],
        [
            'href' => '#',
            'icon' => 'fas fa-server',
            'title' => 'Прошивки',
            'can' => ['firmware_category_access', 'firmware_access'],
            'child' => [
                [
                    'href' => 'admin.firmwares.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Прошивки',
                    'can' => 'firmware_access',
                ],
                [
                    'href' => 'admin.firmware_categories.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Категории',
                    'can' => 'firmware_category_access',
                ],
            ]
        ],
        [
            'href' => '#',
            'icon' => 'fas fa-map-marker-alt',
            'title' => 'Расположение',
            'can' => ['country_access', 'city_access'],
            'child' => [
                [
                    'href' => 'admin.countries.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Страны',
                    'can' => 'country_access',
                ],
                [
                    'href' => 'admin.cities.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Города',
                    'can' => 'city_access',
                ],
            ]
        ],
        [
            'href' => 'admin.data_centers.index',
            'icon' => 'fas fa-boxes',
            'title' => 'Дата центры',
            'can' => 'data_center_access',
        ],
        [
            'href' => 'admin.services.index',
            'icon' => 'fas fa-tools',
            'title' => 'Сервис центры',
            'can' => 'service_access',
        ],
        [
            'href' => '#',
            'icon' => 'fab fa-xbox',
            'title' => 'Оборудование',
            'can' => ['equipment_access', 'manufacturer_access'],
            'child' => [
                [
                    'href' => 'admin.equipments.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Оборудование',
                    'can' => 'equipment_access',
                ],
                [
                    'href' => 'admin.manufacturers.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Производители',
                    'can' => 'manufacturer_access',
                ],
            ]
        ],
        [
            'href' => '#',
            'icon' => 'fab fa-wikipedia-w',
            'title' => 'WIKI',
            'can' => ['wiki_category_access', 'wiki_access'],
            'child' => [
                [
                    'href' => 'admin.wiki.index',
                    'icon' => 'far fa-circle',
                    'title' => 'WIKI',
                    'can' => 'wiki_access',
                ],
                [
                    'href' => 'admin.wiki_categories.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Категории',
                    'can' => 'wiki_category_access',
                ],
            ]
        ],
        [
            'href' => '#',
            'icon' => 'far fa-newspaper',
            'title' => 'Новости',
            'can' => ['news_category_access', 'news_access'],
            'child' => [
                [
                    'href' => 'admin.news.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Новости',
                    'can' => 'news_access',
                ],
                [
                    'href' => 'admin.news_categories.index',
                    'icon' => 'far fa-circle',
                    'title' => 'Категории',
                    'can' => 'news_category_access',
                ],
            ]
        ],
        [
            'href' => 'admin.pages.index',
            'icon' => 'fas fa-file-alt',
            'title' => 'Страницы',
            'can' => 'page_access',
        ],
        [
            'href' => 'admin.advertisings.index',
            'icon' => 'fas fa-ad',
            'title' => 'Реклама',
            'can' => 'advertising_access',
        ],
        [
            'heading' => 'Настройки',
            'can' => ['permission_access', 'role_access', 'user_access', 'setting_edit'],
        ],
        [
            'href' => '#',
            'icon' => 'fas fa-users',
            'title' => 'Пользователи',
            'can' => ['permission_access', 'role_access', 'user_access'],
            'child' => [
                [
                    'href' => 'admin.permissions.index',
                    'icon' => 'fas fa-unlock-alt',
                    'title' => 'Права',
                    'can' => 'permission_access',
                ],
                [
                    'href' => 'admin.roles.index',
                    'icon' => 'fas fa-briefcase',
                    'title' => 'Роли',
                    'can' => 'role_access',
                ],
                [
                    'href' => 'admin.users.index',
                    'icon' => 'fas fa-user-friends',
                    'title' => 'Пользователи',
                    'can' => 'user_access',
                ]
            ]
        ],
        [
            'href' => 'admin.settings.edit',
            'icon' => 'fas fa-cogs',
            'title' => 'Настройки',
            'can' => 'setting_edit',
        ]
    ],

    'permission_types' => ['_access', '_create', '_edit', '_delete'],
    'page_types' => ['page', 'home', 'mining', 'firmware', 'data_center', 'equipment', 'wiki', 'news', 'contact', 'crypto_calc', 'service'],
    'advertising_types' => [
        'pc_1' => 'ПК горизонтальный - 1240x154 px',
        'pc_2' => 'ПК вертикальный справа - 220x830 px',
        'mobile_1' => 'Мобильный - 341x237 px',
    ],
    'advertising_positions' => ['home', 'ratings', 'mining', 'firmwares', 'data_centers', 'equipments', 'wiki', 'contacts', 'crypto_calc', 'services'],

    'pool_stats_coins' => ['btc', 'ltc', 'bch', 'dash'],
];
