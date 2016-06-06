<?php
$cell_type_normal = 'normal';
$cell_type_passage = 'passage';
return array(

//AggressiveMelee
    'list' => [
        1 => [
            'name' => 'Rat', 'class' => 'Rodents\\Rat', 'health' => 2,
            'behaviour' => 'Passive', 'aggressiveRange' => 4, 'attacks' => [
                ['name' => 'teeth']
            ],
            'icon' => 'icon-mouse-1', 'loot' => 100,
            'description' => 'Just a rat. Will not attack you unless you will you will attack it first.',
        ],
        2 => [
            'name' => 'Obstacle', 'class' => 'Stationary\\Dummy', 'health' => 1,
            'behaviour' => 'Passive',
            'icon' => 'icon-wood-chunks',
            'description' => 'You have to destroy this to go further. Try one of your spells.',
        ],
        3 => [
            'name' => 'Witch', 'class' => 'Boss\\Witch', 'health' => 25,
            'behaviour' => 'AggressiveRange', 'aggressiveRange' => 6, 'attacks' => [
                ['name' => 'spawnSpiders'],
                ['name' => 'greenLaser']
            ],
            'icon' => 'icon-witch-1', 'loot' => 102,
            'description' => 'She is common member of magic community. What she can do? You have to find out.',
        ],
        4 => [
            'name' => 'Spider', 'class' => 'Rodents\\Spider', 'health' => 4,
            'behaviour' => 'AggressiveRange', 'aggressiveRange' => 6, 'attacks' => [
                ['name' => 'teeth2'],
                ['name' => 'web']
            ],
            'icon' => 'icon-spider-2', 'loot' => 102,//, 'iconColor' => 'color-red'
            'description' => 'Not a strong creature, but can throw a web at you that would block you from moving. Thankfully a spider can carry only one net.',
        ],
        5 => [
            'name' => 'Baby spider', 'class' => 'Rodents\\SmallSpider', 'health' => 2,
            'behaviour' => 'JumpingMelee', 'aggressiveRange' => 6, 'attacks' => [
                ['name' => 'teeth2']
            ],
            'icon' => 'icon-spider-2', 'loot' => 101, 'morfIcon' => 'baby',
            'description' => 'A small spider. Nothing special, but be cautious it can jump.',
        ],
        101 => [
            'name' => 'Spider Nest', 'class' => 'Boss\\SpiderNest', 'health' => 20,
            'behaviour' => 'Stationary', 'attacks' => [
                ['name' => 'spawnSpider']
            ],
            'icon' => 'icon-spider-egg',
            'description' => 'This egg will spawn spiders until you kill it! Do it quick! .',
        ],
        1001 => [
            'name' => 'Fire imp', 'class' => 'Friendly\\FireImp', 'health' => 3,
            'behaviour' => ['AttackUnits', 'Follow', 'JumpAround'],
            'team' => 'f', 'attacks' => [['name' => 'fireSpit']],
            'icon' => 'icon-fireImp-1', 'iconColor' => 'color-red',
            'description' => 'Creation of fire magic! Will attack your enemies till it`s magic is over. When it is done the Imp would explode.',
        ],
    ],
    'chances' => [
        // World type
        2 => [ // WitchForest world
            // Range from world center

            /* 0 - 5 */        5 => [],
            /* 5 - 10 */       10 => [1, 1, 1, 1, 5],
            /* 10 - 20 */      20 => [1, 1, 1, 5, 5, 4],
            /* 20 - 30 */      30 => [1, 5, 5, 4],
            /* 30 - 40 */      40 => [5, 4],
            /* 40 - 1000 */    1000 => [1],
            /* 1000 - 9999999 */9999999 => [1],
        ],
        3 => [ // WitchForest world
            // Range from world center

            /* 0 - 5 */        5 => [],
            /* 5 - 10 */       10 => [ 5, ],
            /* 10 - 20 */      20 => [ 5, ],
            /* 20 - 30 */      30 => [5, 5, 4],
            /* 30 - 9999999 */9999999 => [5, 4],
        ],
        900 => [ // Battle test world
            // Range from world center

            /* 0 - 3 */        3 => [],
            /* 3 - 10 */      10 => [1, 5],
            /* 10 - 20 */     20 => [1, 5],
            /* 20 - 30 */    30 => [1, 5],
            /* 30 - 9999999 */9999999 => [1, 5],
        ],
    ],
    'attacks' => [
        'teeth' => ['range' => 1.9, 'damage' => 1, 'animation' => 'melee'],
        'teeth2' => ['range' => 1.9, 'damage' => 2, 'animation' => 'melee'],
        'web' => ['range' => 3.2, 'damage' => 0, 'animation' => 'web', 'class' => 'Web', 'charges' => 1],
        'fireSpit' => ['range' => 2.5, 'damage' => 2, 'animation' => 'fireSpit', 'charges' => 3, 'source' => 'fire'],
        'spawnSpiders' => ['range' => 4, 'damage' => 0, 'animation' => 'spawn', 'class' => 'WitchSpiders',  'charges' => 1],
        'spawnSpider' => ['range' => 6, 'damage' => 0, 'animation' => 'spawn', 'class' => 'SpawnSpider'],
        'greenLaser' => ['range' => 3.2, 'damage' => 3, 'animation' => 'greenLaser', 'source' => 'nature'],
    ],


);