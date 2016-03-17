<?php

return array(


    'list' => [
        //fire
        // level 1
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

        // level 2

        // water
        //level 1
        'IceCrown' => [
            'description' => 'Default description',
            'iconClass' => 'icon-flame',
            'noTargetSpell' => true,
        ],

        // air
        //level 1
        'Push' => [
            'description' => 'Default description',
            'iconColor' => 'color-blue',
            'iconClass' => 'icon-cloud-ring',
        ],

        // earth
        //level 1
        'StoneFace' => [
            'description' => 'Default description',
            'iconClass' => 'icon-flame',
            'noTargetSpell' => true,
        ],

        // light
        //level 1
        'SmallHeal' => [
            'description' => 'Default description',
            'iconClass' => 'icon-flame',
            'noTargetSpell' => true,
        ],

        // death
        //level 1
        'BoneArrow' => [
            'description' => 'Default description',
            'iconClass' => 'icon-flame',
            'directTargetSpell' => 'enemy',
        ],
    ],
    'schools' => [
        1 => ['name' => 'fire', 'icon' => 'icon-flame'],
        2 => ['name' => 'water', 'icon' => 'icon-drop'],
        3 => ['name' => 'air', 'icon' => 'icon-cloud-ring'],
        4 => ['name' => 'earth', 'icon' => 'icon-flame'],
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