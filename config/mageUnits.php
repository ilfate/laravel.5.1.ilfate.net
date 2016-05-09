<?php
$cell_type_normal = 'normal';
$cell_type_passage = 'passage';
return array(

//AggressiveMelee
    'list' => [
        1 => [
            'name' => 'Rat', 'class' => 'Rodents\\Rat', 'health' => 2,
            'behaviour' => 'Passive', 'aggressiveRange' => 4, 'attacks' => ['teeth'],
            'icon' => 'icon-mouse', 'loot' => 2
        ],
        2 => [
            'name' => 'Dummy', 'class' => 'Stationary\\Dummy', 'health' => 1,
            'behaviour' => 'Passive',
            'icon' => 'icon-police-target', 'iconColor' => 'color-dark-blue'
        ],
        3 => [
            'name' => 'Witch', 'class' => 'Stationary\\Dummy', 'health' => 3,
            'behaviour' => 'Passive',
            'icon' => 'icon-police-target', 'iconColor' => 'color-red'
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
        2 => [ // Tutorial world
            // Range from world center

            /* 0 - 50 */        50 => [1],
            /* 50 - 100 */      100 => [1],
            /* 100 - 500 */     500 => [1],
            /* 500 - 1000 */    1000 => [1],
            /* 1000 - 9999999 */9999999 => [1],
        ],
    ],
    'attacks' => [
        'teeth' => ['range' => 1.9, 'damage' => 1, 'animation' => 'bite'],
        'fireSpit' => ['range' => 2.5, 'damage' => 2, 'animation' => 'fireSpit'],
    ],


);