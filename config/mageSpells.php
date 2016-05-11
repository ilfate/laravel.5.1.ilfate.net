<?php

return array(


    'list' => [
        1 => [ //fire
            0 => [
                'name' => 'Fireball',
                'class' => 'Fireball',
                'description' => 'Hit a single target with fire',
                'iconClass' => 'icon-flame',
                'iconColor' => 'color-red',
                'directTargetSpell' => 'enemy',
            ],
            1 => [
                'name' => 'FireNova',
                'class' => 'FireNova',
                'description' => 'Create ring of Fire to burn all the enemies around',
                'iconClass' => 'icon-fire-ring',
                'iconColor' => 'color-red',
            ],
            2 => [
                'name' => 'Exploding Bees',
                'class' => 'ExplodingBees',
                'description' => 'Bees all hunt your enemy down',
                'iconClass' => 'icon-bee',
                'iconColor' => 'color-red',
                'directTargetSpell' => 'enemy',
            ],
            3 => [
                'name' => 'Butthurt jump',
                'class' => 'ButthurtJump',
                'description' => 'Use you anger to travel larger distances.',
                'iconClass' => 'icon-fire-dash',
                'iconColor' => 'color-red',
            ],
            4 => [
                'name' => 'Bomb',
                'class' => 'Bomb',
                'description' => 'Leave a bomb where you stand.',
                'iconClass' => 'icon-fire-bomb',
                'iconColor' => 'color-red',
                'noTargetSpell' => true,
            ],
            5 => [
                'name' => 'FireLady',
                'class' => 'FireLady',
                'description' => 'Lady will go around battle field and put your enemies on fire',
                'iconClass' => 'icon-flame',
                'iconColor' => 'color-red',
                'noTargetSpell' => true,
                'noAutoAnimationTrigger' => true,
            ],
            6 => [
                'name' => 'Face canon',
                'class' => 'FaceCanon',
                'description' => 'Shoot your enemy in the face. Kickback might be hard thought.',
                'iconClass' => 'icon-blaster',
                'iconColor' => 'color-red',
            ],
            7 => [
                'name' => 'Phoenix strike',
                'class' => 'PhoenixStrike',
                'description' => 'Launch a phoenix that would attack all enemies in its reach.',
                'iconClass' => 'icon-alien-fire',
                'iconColor' => 'color-red',
                'noAutoAnimationTrigger' => true,
            ],
            8 => [
                'name' => 'Rain of fire',
                'class' => 'RainOfFire',
                'description' => 'Drown your enemies in fire from the sky.',
                'iconClass' => 'icon-fire-tail',
                'iconColor' => 'color-red',
                'noAutoAnimationTrigger' => true,
            ],
            9 => [
                'name' => 'Fire imp',
                'class' => 'FireImp',
                'description' => 'Summon an imp. Let`s hope he will help you defeating your enemies.',
                'iconClass' => 'icon-ifrit',
                'iconColor' => 'color-red',
                'noAutoAnimationTrigger' => true,
                'noTargetSpell' => true,
            ],
        ],

        // level 2

        2 => [ // water
            0 => [
                'name' => 'IceSlide',
                'class' => 'IceSlide',
                'description' => 'Next move you do you can slide very far away',
                'iconClass' => 'icon-snowflake-1',
                'iconColor' => 'color-dark-blue',
                'noTargetSpell' => true,
            ],
            1 => [
                'name' => 'IceCrown',
                'class' => 'IceCrown',
                'description' => 'Reduce damage for next 3 enemy`s attacks',
                'iconClass' => 'icon-frozen-orb',
                'iconColor' => 'color-dark-blue',
                'noTargetSpell' => true,
            ],
            2 => [
                'name' => 'Freeze',
                'class' => 'Freeze',
                'description' => 'Freeze one of your enemies in place',
                'iconClass' => 'icon-ice-cube',
                'iconColor' => 'color-dark-blue',
                'directTargetSpell' => 'enemy',
            ],
        ],
        3 => [ // air
            0 => [
                'name' => 'Push',
                'class' => 'Push',
                'description' => 'Default description',
                'iconColor' => 'color-blue',
                'iconClass' => 'icon-cloud-ring',
            ],
        ],
        4 => [ // earth
            0 => [
                'name' => 'StoneFace',
                'class' => 'StoneFace',
                'description' => 'Default description',
                'iconClass' => 'icon-iron-mask',
                'iconColor' => 'color-brown',
                'noTargetSpell' => true,
            ],
        ],
        5 => [ // light
            0 => [
                'name' => 'Small Heal',
                'class' => 'SmallHeal',
                'description' => 'Default description',
                'iconClass' => 'icon-flame',
                'noTargetSpell' => true,
            ],
        ],
        6 => [ // death
            0 => [
                'name' => 'Bone Arrow',
                'class' => 'BoneArrow',
                'description' => 'Default description',
                'iconClass' => 'icon-flame',
                'directTargetSpell' => 'enemy',
            ],
        ],
    ],
    'school-chances' => [
        1,1,1,1,1,1,1,1,   //fire
        2,2,2,2,2,2,2,2, // water
//        3,3,3,3,3,3,     // air
//        4,4,4,4,4,4,     // earth
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