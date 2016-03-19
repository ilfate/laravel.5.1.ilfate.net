<?php

return array(


    'list' => [
        1 => [ //fire
            1 => [
                'Fireball' => [
                    'description' => 'Hit a single target with fire',
                    'iconClass' => 'icon-flame',
                    'iconColor' => 'color-red',
                    'directTargetSpell' => 'enemy',
                ],
                'FireNova' => [
                    'description' => 'Create ring of Fire to bern all the enemies around',
                    'iconClass' => 'icon-flame',
                    'iconColor' => 'color-red',

                ],
            ],
        ],

        // level 2

        2 => [ // water
            1 => [
                'IceCrown' => [
                    'description' => 'Default description',
                    'iconClass' => 'icon-frozen-orb',
                    'iconColor' => 'color-dark-blue',
                    'noTargetSpell' => true,
                ],
            ],
        ],
        3 => [ // air
            1 => [
                'Push' => [
                    'description' => 'Default description',
                    'iconColor' => 'color-blue',
                    'iconClass' => 'icon-cloud-ring',
                ],
            ],
        ],
        4 => [ // earth
            1 => [
                'StoneFace' => [
                    'description' => 'Default description',
                    'iconClass' => 'icon-iron-mask',
                    'iconColor' => 'color-brown',
                    'noTargetSpell' => true,
                ],
            ],
        ],
        5 => [ // light
            1 => [
                'SmallHeal' => [
                    'description' => 'Default description',
                    'iconClass' => 'icon-flame',
                    'noTargetSpell' => true,
                ],
            ],
        ],
        6 => [ // death
            1 => [
                'BoneArrow' => [
                    'description' => 'Default description',
                    'iconClass' => 'icon-flame',
                    'directTargetSpell' => 'enemy',
                ],
            ],
        ],
    ],
    'school-chances' => [
        1,1,1,1,1,1,1,1,   //fire
        2,2,2,2,2,2,2,2, // water
        3,3,3,3,3,3,     // air
        4,4,4,4,4,4,     // earth
        //  5,5,             // light
        //  6,6,             // death
//            7,7,
//            8,
//            9,
//            10,
    ],
    'schools' => [
        1 => ['name' => 'fire', 'icon' => 'icon-flame'],
        2 => ['name' => 'water', 'icon' => 'icon-drop', 'class' => 'color-dark-blue'],
        3 => ['name' => 'air', 'icon' => 'icon-cloud-ring'],
        4 => ['name' => 'earth', 'icon' => 'icon-rock', 'class' => 'color-brown'],
        5 => ['name' => 'light', 'icon' => 'icon-flame'],
        6 => ['name' => 'death', 'icon' => 'icon-flame'],
        7 => ['name' => 'nature', 'icon' => 'icon-flame'],
        8 => ['name' => 'arcane', 'icon' => 'icon-flame'],
        9 => ['name' => 'blood', 'icon' => 'icon-flame'],
        10 => ['name' => 'voodoo', 'icon' => 'icon-flame'],
        11 => ['name' => 'demonology', 'icon' => 'icon-flame'],
        12 => ['name' => 'dragon', 'icon' => 'icon-flame'],
        13 => ['name' => 'spirit', 'icon' => 'icon-flame'],
        14 => ['name' => 'shadow', 'icon' => 'icon-flame'],
        15 => ['name' => 'steel', 'icon' => 'icon-flame'],
    ],

);