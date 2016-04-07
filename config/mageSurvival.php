<?php
$cell_type_normal = 'normal';
$cell_type_passage = 'passage';
return array(
    'status-to-page' => [
        'game_init' => 'mage-list',
        'mage_home' => 'mage-home',
        'mage_battle' => 'battle',
    ],
    'pages' => [
        'mage-list' => 'games.mageSurvival.mage-list',
        'mage-home' => 'games.mageSurvival.mage-home',
        'battle' => 'games.mageSurvival.battle',
    ],
    'game' => [
        'screen-radius' => 5,
        'active-units-radius' => 8,
    ],
    'world-types' => [
        1 => 'Tutorial'
    ],
    'objects' => [
        'list' => [
            1 => ['name' => 'Chest', 'class' => 'Chest', 'icon' => 'icon-locked-chest', 'iconColor' => 'color-clay'],
            2 => ['name' => 'Corpse', 'class' => 'Corpse', 'icon' => 'icon-carrion', 'iconColor' => 'color-clay'],
        ],
        'chances' => [
            // World type
            1 => [ // Tutorial world
                // Range from world center

                /* 0 - 50 */        50 => [1],
                /* 50 - 100 */      100 => [1],
                /* 100 - 500 */     500 => [1],
                /* 500 - 1000 */    1000 => [1],
                /* 1000 - 9999999 */9999999 => [1],
            ],
        ],
    ],
    'items' => [
        1 => ['type'=>'carrier', 'name'=>'Damaged scroll', 'stats'=>['usages' => '2-5'], 'icon' => 'icon-tied-scroll'],
        2 => ['type'=>'carrier', 'name'=>'Bad scroll',  'stats'=>['usages' => '1-3'], 'icon' => 'icon-tied-scroll'],
        301 => ['type'=>'ingredient', 'name'=>'Emerald',  'stats'=>['spell' => '30'], 'icon' => 'icon-emerald'],
        302 => ['type'=>'ingredient', 'name'=>'Fire essence',  'stats'=>['spell' => '30', 'school' => ['fire' => 5]],
                'icon' => 'icon-fire-bottle', 'iconColor' => 'color-red'],
        303 => ['type'=>'ingredient', 'name'=>'Ore',  'stats'=>['spell' => '20', 'cooldown' => ['min' => 1, 'max' => 1]],
                'icon' => 'icon-ore', 'iconColor' => 'color-red'],
        304 => ['type'=>'ingredient', 'name'=>'Bone',  'stats'=>['spell' => '40', 'cooldown' => ['min' => 1, 'max' => 1]],
                'icon' => 'icon-broken-bone', 'iconColor' => 'color-white'],
        // Сера
        // Горный хрусталь
        // Кость гоблина
        // Золотая монета (деньги грубо говоря)
        // Квартц
        // Уголь
        // Болотная трава
        // черная жемчужина
        // кость скелета
        // Аквамарин
        // Сердце каменного голема
        // Святая вода
    ],
    'item-types' => [
        ['name' => 'carrier', 'icon' => 'icon-scroll-unfurled', 'class' => 'color-brown'],
        ['name' => 'ingredient', 'icon' => 'icon-fizzing-flask', 'class' => 'color-dark-blue'],
    ],
//    'spells' => [
//
//        'list' => [
//            1 => [ //fire
//                1 => [ // level 1
//                    'Fireball',
//                    'FireNova',
//                ],
//                2 => [ // level 2
//
//                ],
//            ],
//            2 => [ // water
//                1 => [
//                    'IceCrown'
//                ],
//                2 => [],
//            ],
//            3 => [ // air
//                1 => [
//                    'Push'
//                ],
//                2 => [],
//            ],
//            4 => [ // earth
//                1 => [
//                    'StoneFace'
//                ],
//                2 => [],
//            ],
//            5 => [ // light
//                1 => [
//                    'SmallHeal'
//                ],
//                2 => [],
//            ],
//            6 => [ // death
//                1 => [
//                    'BoneArrow'
//                ],
//                2 => [],
//            ],
//        ],
//    ],
    'mages-types' => [
        'apprentice'   => ['name' => 'Apprentice', 'available' => true],
        'wizard'       => ['name' => 'Wizard'],
        'archmage'     => ['name' => 'Archmage'],
        'sorcerer'     => ['name' => 'Sorcerer'],
        'magus'        => ['name' => 'Magus'],
        'druid'        => ['name' => 'Druid'],
        'elementalist' => ['name' => 'Elementalist'],
        'arcanist'     => ['name' => 'Arcanist'],
        'shadowmage'   => ['name' => 'Shadowmage'],
        'pyromancer'   => ['name' => 'Pyromancer'],
        'geomancer'    => ['name' => 'Geomancer'],
        'aeromancer'   => ['name' => 'Aeromancer'],
        'necromancer'  => ['name' => 'Necromancer'],
        'ice_caller'   => ['name' => 'Ice Caller'],
        'warlock'      => ['name' => 'Warlock'],
        'summoner'     => ['name' => 'Summoner'],
        'bloodmage'    => ['name' => 'Blood Mage'],
        'spellbinder'  => ['name' => 'Spellbinder'],
        'shaman'       => ['name' => 'Shaman'],
        'enchanter'    => ['name' => 'Enchanter'],
        'illusionist'  => ['name' => 'Illusionist'],
        'invoker'      => ['name' => 'Invoker'],
        'dragon_mage'  => ['name' => 'Dragon Mage'],
        'time_master'  => ['name' => 'Time Master'],
        'techno_mage'  => ['name' => 'Techno Mage'],
    ]
);