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
        'battle' => 'games.mageSurvival.user-battle',
    ],
    'game' => [
        'screen-radius' => 5,
        'active-units-radius' => 8,
        'user-logging-chance' => env('MS_USER_LOGGING_CHANCE', 5),
    ],
    'world-types' => [
        1 => 'Tutorial'
    ],
    'worlds' => [
        1 => [
            'name' => 'Tutorial',
            'map-type' => 'Tutorial',
            'map-visual' => 'Tutorial',
            'is-available' => true,
        ],
        2 => [
            'name' => 'Witch forest',
            'map-type' => 'WitchForest',
            'map-visual' => 'WitchForest',
            'is-available' => false,
        ],
        3 => [
            'name' => 'Secret cave',
            'map-type' => 'SecretCave',
            'map-visual' => 'WitchForest',
            'is-available' => false,
        ],
        4 => [
            'name' => 'School basement',
            'map-type' => 'SchoolBasement',
            'map-visual' => 'Tutorial',
            'is-available' => false,
        ],
        50 => [
            'name' => 'Home',
            'map-type' => 'Home',
            'map-visual' => 'Tutorial',
            'is-available' => true,
        ],
        900 => [
            'name' => 'Battle Test',
            'map-type' => 'BattleTest',
            'map-visual' => 'WitchForest',
            'is-available' => false,
            'is-admin' => true,
            'is-delete-on-exit' => true,
        ],
        901 => [
            'name' => 'SituationTest',
            'map-type' => 'SituationTest',
            'map-visual' => 'WitchForest',
            'is-available' => false,
            'is-admin' => true,
            'is-delete-on-exit' => true,
        ],
    ],
    'objects' => [
        'list' => [
            97 => ['name' => 'Corpse', 'class' => 'Corpse', 'icon' => 'icon-carrion', 'iconColor' => 'color-clay', 'loot' => [1]],
            98 => ['name' => 'Corpse', 'class' => 'Corpse', 'icon' => 'icon-carrion', 'iconColor' => 'color-clay', 'loot' => [2]],
            99 => ['name' => 'Corpse', 'class' => 'Corpse', 'icon' => 'icon-carrion', 'iconColor' => 'color-clay', 'loot' => [3]],
            100 => ['name' => 'Corpse', 'class' => 'Corpse', 'icon' => 'icon-carrion', 'iconColor' => 'color-clay', 'loot' => [1,2,3,4,5]],
            101 => ['name' => 'Corpse', 'class' => 'Corpse', 'icon' => 'icon-carrion', 'iconColor' => 'color-clay', 'loot' => [3,4,5,6,7]],
            102 => ['name' => 'Corpse', 'class' => 'Corpse', 'icon' => 'icon-carrion', 'iconColor' => 'color-clay', 'loot' => [5,6,7,8,9,10], 'quantity' => 2],
            110 => ['name' => 'Chest', 'class' => 'Chest', 'icon' => 'icon-locked-chest', 'iconColor' => 'color-clay', 'loot' => [1,2,3,4,5,6,7]],
            111 => ['name' => 'Chest', 'class' => 'Chest', 'icon' => 'icon-locked-chest', 'iconColor' => 'color-blue', 'loot' => [8,9,10,11,12,13], 'quantity'=>3],
            112 => ['name' => 'Chest', 'class' => 'Chest', 'icon' => 'icon-locked-chest', 'iconColor' => 'color-gold', 'loot' => [13, 14, 15], 'quantity' => 2],
            113 => ['name' => 'Chest', 'class' => 'Chest', 'icon' => 'icon-locked-chest', 'iconColor' => 'color-clay', 'loot' => [1,2,3,4,5,6,7], 'quantity' => 4],
            115 => ['name' => 'Chest', 'class' => 'Chest', 'icon' => 'icon-locked-chest', 'iconColor' => 'color-purple', 'loot' => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19], 'quantity' => 7],
            4 => ['name' => 'Bomb', 'class' => 'Bomb', 'icon' => 'icon-fire-bomb', 'iconColor' => 'color-red'],
            5 => ['name' => 'Ice wall', 'class' => 'IceWall', 'icon' => 'icon-cracked-glass', 'iconColor' => 'color-blue-bright'],
            6 => ['name' => 'Stone wall', 'class' => 'StoneWall', 'icon' => 'icon-stoned', 'iconColor' => 'color-grey'],
            7 => ['name' => 'Fresh water fountain', 'class' => 'FreshWaterFountain', 'icon' => 'icon-fountain', 'iconColor' => 'color-blue'],
            8 => ['name' => 'Rasengan', 'class' => 'Rasengan', 'icon' => 'icon-water-huracane', 'centered' => true],
            9 => ['name' => 'Rasengan', 'class' => 'Rasengan2', 'icon' => 'icon-water-huracane', 'centered' => true, 'morfIcon' => 'lightBlue',],
            15 => ['name' => 'Tesla trap', 'class' => 'TeslaTrap', 'icon' => 'icon-tesla-coil', 'iconColor' => 'color-blue-bright'],
            50 => ['name' => 'Door', 'class' => 'DoorQuest', 'icon' => 'icon-wooden-door', 'iconColor' => 'color-brown'],
            1000 => ['name' => 'Portal', 'class' => 'Portal', 'icon' => 'icon-magic-portal', 'iconColor' => 'color-purple-dark'],
        ],
        'chances' => [
            // World type
            2 => [ // WitchForest world
                // Range from world center

                /* 0 - 50 */        50 => [110],
                /* 50 - 100 */      100 => [110],
                /* 100 - 500 */     500 => [110],
                /* 500 - 1000 */    1000 => [1],
                /* 1000 - 9999999 */9999999 => [111],
            ],
            900 => [ // Test world

                /* 0 - 50 */        50 => [110],
                /* 50 - 100 */      100 => [110],
                /* 100 - 500 */     500 => [110],
                /* 500 - 1000 */    1000 => [110],
                /* 1000 - 9999999 */9999999 => [111],
            ],
        ],
    ],
    'mages-types' => [
        'apprentice'   => ['name' => 'Apprentice', 'available' => true, 'icon' => 'icon-sensuousness'],
        'sorcerer'     => ['name' => 'Sorcerer', 'icon' => 'icon-sensuousness',
                           'stats' => ['fire' => 5, 'water' => 5, 'air' => 5, 'earth' => 5]],
        'wizard'       => ['name' => 'Wizard', 'icon' => 'icon-sensuousness',
                           'stats' => ['fire' => 30, 'water' => 30, 'air' => 30, 'earth' => 30]],
        'druid'        => ['name' => 'Druid', 'icon' => 'icon-sensuousness',
                           'stats' => ['water' => 30, 'air' => 30, 'earth' => 100]],
//        'archmage'     => ['name' => 'Archmage'],
//        'magus'        => ['name' => 'Magus'],
//        'elementalist' => ['name' => 'Elementalist'],
//        'arcanist'     => ['name' => 'Arcanist'],
//        'shadowmage'   => ['name' => 'Shadowmage'],
//        'pyromancer'   => ['name' => 'Pyromancer'],
//        'geomancer'    => ['name' => 'Geomancer'],
//        'aeromancer'   => ['name' => 'Aeromancer'],
//        'necromancer'  => ['name' => 'Necromancer'],
//        'ice_caller'   => ['name' => 'Ice Caller'],
//        'warlock'      => ['name' => 'Warlock'],
//        'summoner'     => ['name' => 'Summoner'],
//        'bloodmage'    => ['name' => 'Blood Mage'],
//        'spellbinder'  => ['name' => 'Spellbinder'],
//        'shaman'       => ['name' => 'Shaman'],
//        'enchanter'    => ['name' => 'Enchanter'],
//        'illusionist'  => ['name' => 'Illusionist'],
//        'invoker'      => ['name' => 'Invoker'],
//        'dragon_mage'  => ['name' => 'Dragon Mage'],
//        'time_master'  => ['name' => 'Time Master'],
//        'techno_mage'  => ['name' => 'Techno Mage'],
    ]
);