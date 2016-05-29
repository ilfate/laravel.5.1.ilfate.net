<?php

return array(


    'list' => [
        1 => [ //fire
            0 => [
                'name' => 'Fireball',
                'class' => 'Fireball',
                'description' => 'Hit a single target with fire',
                'iconClass' => 'icon-ember-shot',
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
                'name' => 'Light My Fire',
                'class' => 'LightMyFire',
                'description' => 'C`mon light the night on fire.',
                'iconClass' => 'icon-flame',
                'iconColor' => 'color-red',
                'directTargetSpell' => 'enemy',
            ],
            5 => [
                'name' => 'Bomb',
                'class' => 'Bomb',
                'description' => 'Leave a bomb where you stand.',
                'iconClass' => 'icon-fire-bomb',
                'iconColor' => 'color-red',
                'noTargetSpell' => true,
            ],
            6 => [
                'name' => 'Fire Woman',
                'class' => 'FireLady',
                'description' => 'Lady will go around battle field and put your enemies on fire',
                'iconClass' => 'icon-fire-woman',
                'iconColor' => 'color-red',
                'noTargetSpell' => true,
                'noAutoAnimationTrigger' => true,
            ],
            7 => [
                'name' => 'Face canon',
                'class' => 'FaceCanon',
                'description' => 'Shoot your enemy in the face. Kickback might be hard thought.',
                'iconClass' => 'icon-blaster',
                'iconColor' => 'color-red',
            ],
            8 => [
                'name' => 'Let the Fire in your eyes',
                'class' => 'LetFireInYourEyes',
                'description' => 'Let your enemies burn like hell. Unless they are devils in disguise.',
                'iconClass' => 'icon-pyromaniac',
                'iconColor' => 'color-red',
                'directTargetSpell' => 'enemy',
            ],
            9 => [
                'name' => 'Phoenix strike',
                'class' => 'PhoenixStrike',
                'description' => 'Launch a phoenix that would attack all enemies in its reach.',
                'iconClass' => 'icon-alien-fire',
                'iconColor' => 'color-red',
                'noAutoAnimationTrigger' => true,
            ],
            10 => [
                'name' => 'Fire and Rain',
                'class' => 'RainOfFire',
                'description' => 'Drown your enemies in fire from the sky.',
                'iconClass' => 'icon-fire-tail',
                'iconColor' => 'color-red',
                'noAutoAnimationTrigger' => true,
            ],
            11 => [
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
            3 => [
                'name' => 'Ice wall',
                'class' => 'IceWall',
                'description' => 'Create an ice wall that will last for 5 turns',
                'iconClass' => 'icon-frozen-block',
                'iconColor' => 'color-dark-blue',
            ],
            4 => [
                'name' => 'Ice spear',
                'class' => 'IceSpear',
                'description' => 'Freeze the target. If target was already frozen, deal up to 5 damage based on range. Closer the target - more damage.',
                'iconClass' => 'icon-ice-spear',
                'iconColor' => 'color-dark-blue',
                'directTargetSpell' => 'enemy',
            ],
            5 => [
                'name' => 'Ice cone',
                'class' => 'IceCone',
                'description' => 'Deal 1-2 damage and freeze targets for a short time in area.',
                'iconClass' => 'icon-icicles-fence',
                'iconColor' => 'color-dark-blue',
            ],
            6 => [
                'name' => 'Wash and go',
                'class' => 'WashAndGo',
                'description' => 'If you stand near the water, heal yourself for 30% of your maximum health.',
                'iconClass' => 'icon-drowning-2',
                'iconColor' => 'color-dark-blue',
                'noTargetSpell' => true,
                'noAutoAnimationTrigger' => true,
            ],
            7 => [
                'name' => 'Winter is coming',
                'class' => 'Blizzard',
                'description' => 'Freeze and damage(1-2) every unit in range of 4 cells from you.',
                'iconClass' => 'icon-dust-cloud',
                'iconColor' => 'color-dark-blue',
                'noTargetSpell' => true,
            ],
            8 => [
                'name' => 'Ice shield',
                'class' => 'IceShield',
                'description' => 'Protect yourself with lots of ice! You will get some armor and for next 3 attacks enemies would freeze.',
                'iconClass' => 'icon-ice-shield',
                'iconColor' => 'color-dark-blue',
                'noTargetSpell' => true,
            ],
            9 => [
                'name' => 'Icelock',
                'class' => 'Icelock',
                'description' => 'Launch an ice missile at every frozen enemy for 3 dmg',
                'iconClass' => 'icon-ice-bolt',
                'iconColor' => 'color-dark-blue',
                'noTargetSpell' => true,
                'noAutoAnimationTrigger' => true,
            ],
            10 => [
                'name' => 'Fresh water fountain',
                'class' => 'FreshWaterFountain',
                'description' => 'Create a small water fountain that would heal you when you stay around.',
                'iconClass' => 'icon-fountain',
                'iconColor' => 'color-dark-blue',
            ],
            11 => [
                'name' => 'Water body',
                'class' => 'WaterBody',
                'description' => 'For next 5 turns you would be able to go trough any wall',
                'iconClass' => 'icon-water-body',
                'iconColor' => 'color-dark-blue',
                'noTargetSpell' => true,
            ],
            12 => [
                'name' => 'Rasengan',
                'class' => 'Rasengan',
                'description' => 'A spinning water sphere that would ice down everything on its way',
                'iconClass' => 'icon-rasengan',
                'iconColor' => 'color-dark-blue',
            ],
            // Winter is coming
        ],
        3 => [ // air
            0 => [
                'name' => 'Push',
                'class' => 'Push',
                'description' => 'Push your enemies away from you.',
                'iconColor' => 'color-light-blue',
                'iconClass' => 'icon-cloud-ring',
            ],
            1 => [
                'name' => 'Harmony',
                'class' => 'Harmony',
                'description' => 'Meditate to regain 5 health and increase Air spells damage by 2 for next 2 turns.',
                'iconColor' => 'color-light-blue',
                'iconClass' => 'icon-triorb',
                'noTargetSpell' => true,
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