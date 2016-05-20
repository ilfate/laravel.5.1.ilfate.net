<?php
$cell_type_normal = 'normal';
$cell_type_passage = 'passage';
return array(

//AggressiveMelee
    'list' => [
        1 => [
            'name' => 'Rat', 'class' => 'Rodents\\Rat', 'health' => 1,
            'behaviour' => 'Passive', 'aggressiveRange' => 4, 'attacks' => ['teeth'],
            'icon' => 'icon-mouse', 'loot' => 2
        ],
        2 => [
            'name' => 'Dummy', 'class' => 'Stationary\\Dummy', 'health' => 1,
            'behaviour' => 'Passive',
            'icon' => 'icon-police-target', 'iconColor' => 'color-dark-blue'
        ],
        3 => [
            'name' => 'Witch', 'class' => 'Boss\\Witch', 'health' => 25,
            'behaviour' => 'Passive',
            'icon' => 'icon-police-target', 'iconColor' => 'color-red'
        ],
        4 => [
            'name' => 'Spider', 'class' => 'Rodents\\Spider', 'health' => 4,
            'behaviour' => 'AggressiveRange', 'aggressiveRange' => 6, 'attacks' => ['teeth2', 'web'],
            'icon' => 'icon-spider'//, 'iconColor' => 'color-red'
        ],
        1001 => [
            'name' => 'Fire imp', 'class' => 'Friendly\\FireImp', 'health' => 3,
            'behaviour' => ['AttackUnits', 'Follow', 'JumpAround'],
            'team' => 'f', 'attacks' => ['fireSpit'],
            'icon' => 'icon-bat', 'iconColor' => 'color-red'
        ],
    ],
    'chances' => [
        // World type
        2 => [ // WitchForest world
            // Range from world center

            /* 0 - 5 */        5 => [],
            /* 5 - 10 */        10 => [1],
            /* 10 - 100 */      100 => [1],
            /* 100 - 500 */     500 => [1],
            /* 500 - 1000 */    1000 => [1],
            /* 1000 - 9999999 */9999999 => [1],
        ],
        900 => [ // Tutorial world
            // Range from world center

            /* 0 - 3 */        3 => [],
            /* 3 - 10 */      10 => [1, 4],
            /* 10 - 20 */     20 => [1, 4],
            /* 20 - 30 */    30 => [1, 4],
            /* 30 - 9999999 */9999999 => [1, 4],
        ],
    ],
    'attacks' => [
        'teeth' => ['range' => 1.9, 'damage' => 1, 'animation' => 'bite'],
        'teeth2' => ['range' => 1.9, 'damage' => 2, 'animation' => 'bite'],
        'web' => ['range' => 3.2, 'damage' => 0, 'animation' => 'web', 'class' => 'Web', 'charges' => 1],
        'fireSpit' => ['range' => 2.5, 'damage' => 2, 'animation' => 'fireSpit'],
        'spawn' => ['range' => 4, 'damage' => 0, 'animation' => 'spawn', 'class' => 'Spawn'],
    ],


);