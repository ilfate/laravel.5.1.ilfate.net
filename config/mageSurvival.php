<?php
$cell_type_normal = 'normal';
$cell_type_passage = 'passage';
return array(
    'game' => [
        'screen-radius' => 5,
        'active-units-radius' => 8,
    ],
    'status-to-page' => [
        'game_init' => 'mage-list',
        'mage_battle' => 'battle',
    ],
    'pages' => [
        'mage-list' => 'games.mageSurvival.mage-list',
        'battle' => 'games.mageSurvival.battle',
    ],
    'world-types' => [
        1 => 'Tutorial'
    ],
    'objects' => [
        'list' => [
            1 => ['name' => 'Chest', 'class' => 'Chest'],
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
        1 => ['type'=>'carrier', 'name'=>'Damaged scroll', 'stats'=>['usages' => '2-5'], 'class' => 'msc_scroll_map'],
        2 => ['type'=>'carrier', 'name'=>'Bad scroll',  'stats'=>['usages' => '1-3'], 'class' => 'msc_scroll_map'],
        301 => ['type'=>'ingredient', 'name'=>'Red Ore',  'stats'=>['spell' => '30'], 'class' => 'msc_ore_red1'],
        302 => ['type'=>'ingredient', 'name'=>'Fire essence',  'stats'=>['spell' => '30', 'school' => ['fire' => 5]],
                'class' => 'mgc_fire_1'],
        303 => ['type'=>'ingredient', 'name'=>'Feather',  'stats'=>['spell' => '20', 'cooldown' => ['min' => 1, 'max' => 1]],
                'class' => 'mgc_misc_7'],
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
        ['name' => 'carrier', 'class' => 'msc_scroll_open'],
        ['name' => 'ingredient', 'class' => 'msc_feather_red'],
    ],
    'spells' => [
        'schools' => [
            1 => 'fire',
            2 => 'water',
            3 => 'air',
            4 => 'earth',
            5 => 'light',
            6 => 'death',
            7 => 'nature',
            8 => 'arcane',
            9 => 'blood',
            10 => 'voodoo',
            11 => 'demonology',
            12 => 'dragon',
            13 => 'spirit',
            14 => 'shadow',
            15 => 'steel',
        ],
        'list' => [
            1 => [ //fire
                1 => [ // level 1
                    'Fireball',
                    'FireNova',
                ],
                2 => [ // level 2

                ],
            ],
            2 => [ // water
                1 => [
                    'IceCrown'
                ],
                2 => [],
            ],
            3 => [ // air
                1 => [
                    'Push'
                ],
                2 => [],
            ],
            4 => [ // earth
                1 => [
                    'StoneFace'
                ],
                2 => [],
            ],
            5 => [ // light
                1 => [
                    'SmallHeal'
                ],
                2 => [],
            ],
            6 => [ // death
                1 => [
                    'BoneArrow'
                ],
                2 => [],
            ],
        ],
        'school-chances' => [
            1,1,1,1,1,1,1,1,   //fire
//            2,2,2,2,2,2,2,2, // water
            3,3,3,3,3,3,     // air
          //  4,4,4,4,4,4,     // earth
          //  5,5,             // light
          //  6,6,             // death
//            7,7,
//            8,
//            9,
//            10,
        ],
    ],
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