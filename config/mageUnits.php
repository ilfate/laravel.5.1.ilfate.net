<?php
$cell_type_normal = 'normal';
$cell_type_passage = 'passage';
return array(


    'list' => [
        1 => [
            'name' => 'Rat', 'class' => 'Rodents\\Rat', 'health' => 1,
            'behaviour' => 'AggressiveMelee', 'aggressiveRange' => 4, 'attacks' => ['teeth']
        ],
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
    'attacks' => [
        'teeth' => ['range' => 'melee'],
    ],


);