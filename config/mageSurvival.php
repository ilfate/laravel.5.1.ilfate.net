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
    'worlds' => [
        1 => [
            'name' => 'Tutorial',
            'map-type' => 'Tutorial',
            'is-available' => true,
        ],
        2 => [
            'name' => 'Witch forest',
            'map-type' => 'WitchForest',
            'is-available' => true,
        ],
    ],
    'objects' => [
        'list' => [
            1 => ['name' => 'Chest', 'class' => 'Chest', 'icon' => 'icon-locked-chest', 'iconColor' => 'color-clay'],
            2 => ['name' => 'Corpse', 'class' => 'Corpse', 'icon' => 'icon-carrion', 'iconColor' => 'color-clay'],
            50 => ['name' => 'Door', 'class' => 'DoorQuest', 'icon' => 'icon-wooden-door', 'iconColor' => 'color-brown'],
            1000 => ['name' => 'Portal', 'class' => 'Portal', 'icon' => 'icon-magic-portal', 'iconColor' => 'color-black'],
        ],
        'chances' => [
            // World type
            2 => [ // WitchForest world
                // Range from world center

                /* 0 - 50 */        50 => [1],
                /* 50 - 100 */      100 => [1],
                /* 100 - 500 */     500 => [1],
                /* 500 - 1000 */    1000 => [1],
                /* 1000 - 9999999 */9999999 => [1],
            ],
        ],
    ],
    'mages-types' => [
        'apprentice'   => ['name' => 'Apprentice', 'available' => true, 'icon' => 'icon-sensuousness'],
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