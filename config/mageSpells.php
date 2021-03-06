<?php

return array(


    'list' => [
        1 => [ //fire
            0 => [
                'name' => 'Fireball',
                'class' => 'Fireball',
                'description' => 'Hit a single target with fire. For only 1 damage...',
                'iconClass' => 'icon-ember-shot',
                'iconColor' => 'color-red',
                'directTargetSpell' => 'enemy',
            ],
            1 => [
                'name' => 'FireNova',
                'class' => 'FireNova',
                'description' => 'Create ring of Fire to burn all the enemies around for 2 damage',
                'iconClass' => 'icon-fire-ring',
                'iconColor' => 'color-red',
            ],
            2 => [
                'name' => 'Exploding Bees',
                'class' => 'ExplodingBees',
                'description' => 'Bees all hunt your enemy down. (For 1-4 damage)',
                'iconClass' => 'icon-bee',
                'iconColor' => 'color-red',
                'directTargetSpell' => 'enemy',
            ],
            3 => [
                'name' => 'Butthurt jump',
                'class' => 'ButthurtJump',
                'description' => 'Use you anger to travel larger distances. Landing would not be very exact.',
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
                'description' => 'Leave a bomb where you stand. It will explode for 3 damage when any unit will be around. Explosion will also damage you.',
                'iconClass' => 'icon-fire-bomb',
                'iconColor' => 'color-red',
                'noTargetSpell' => true,
            ],
            6 => [
                'name' => 'Fire Woman',
                'class' => 'FireLady',
                'description' => 'Deal from 1 to 6 damage to a random target.',
                'iconClass' => 'icon-fire-woman',
                'iconColor' => 'color-red',
                'noTargetSpell' => true,
                'noAutoAnimationTrigger' => true,
            ],
            7 => [
                'name' => 'Face canon',
                'class' => 'FaceCanon',
                'description' => 'Shoot your enemy in the face for 2 damage. Kickback might be hard thought.',
                'iconClass' => 'icon-blaster',
                'iconColor' => 'color-red',
            ],
            8 => [
                'name' => 'Let the Fire in your eyes',
                'class' => 'LetFireInYourEyes',
                'description' => 'Let your enemies burn like hell. Unless they are devils in disguise. (2 damage + burn for 3 turns)',
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
                'name' => 'Fire rains from above',
                'class' => 'RainOfFire',
                'description' => 'Drown your enemies in fire from the sky dealing 1 - 3 damage.',
                'iconClass' => 'icon-fire-tail',
                'iconColor' => 'color-red',
                'noAutoAnimationTrigger' => true,
            ],
            11 => [
                'name' => 'Burn cities to the ground',
                'class' => 'BurnCitiesToTheGround',
                'description' => 'Unleash the wild fire upon your enemies dealing 1 damage to all in range of sight and burning them for 3 turns.',
                'iconClass' => 'icon-eruption',
                'iconColor' => 'color-red',
                'noAutoAnimationTrigger' => true,
                'noTargetSpell' => true,
            ],
            12 => [
                'name' => 'Fire imp',
                'class' => 'FireImp',
                'description' => 'Summon an imp. Let`s hope he will help you defeating your enemies.',
                'iconClass' => 'icon-ifrit',
                'iconColor' => 'color-red',
                'noAutoAnimationTrigger' => true,
                'noTargetSpell' => true,
            ],
            13 => [
                'name' => 'Does it burns?',
                'class' => 'DoesItBurns',
                'description' => 'Deal 2 damage to a target. If the target is already burning deal 3 more damage.',
                'iconClass' => 'icon-flame-spin',
                'iconColor' => 'color-red',
                'directTargetSpell' => 'enemy',
            ],
        ],

        2 => [ // water
            0 => [
                'name' => 'IceSlide',
                'class' => 'IceSlide',
                'description' => 'Freeze all enemies around you. Next move you do you can slide very far away',
                'iconClass' => 'icon-snowflake-1',
                'iconColor' => 'color-dark-blue',
                'noTargetSpell' => true,
            ],
            1 => [
                'name' => 'IceCrown',
                'class' => 'IceCrown',
                'description' => 'Reduce damage for next 5 enemy`s attacks',
                'iconClass' => 'icon-frozen-orb',
                'iconColor' => 'color-dark-blue',
                'noTargetSpell' => true,
            ],
            2 => [
                'name' => 'Freeze',
                'class' => 'Freeze',
                'description' => 'Freeze one of your enemies in place for 3 turns',
                'iconClass' => 'icon-ice-cube',
                'iconColor' => 'color-dark-blue',
                'directTargetSpell' => 'enemy',
            ],
            3 => [
                'name' => 'Ice wall',
                'class' => 'IceWall',
                'description' => 'Create an ice wall that will last for 5 turns. Deal 2 damage to every unit stuck in wall.',
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
                'description' => 'Protect yourself with lots of ice! You will get 5 armor and for next 3 attacks enemies would freeze.',
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
            13 => [
                'name' => 'Brain freeze',
                'class' => 'Freeze2',
                'description' => 'Freeze on enemy for 4 turns and deal 3 damage.',
                'iconClass' => 'icon-brain-freeze',
                'iconColor' => 'color-dark-blue',
                'directTargetSpell' => 'enemy',
            ],

            // Winter is coming
        ],
        3 => [ // air
            0 => [
                'name' => 'Push',
                'class' => 'Push',
                'description' => 'Push your enemies away from you and deal 0-1 damage. If they hit a wall inflict additional damage.',
                'iconColor' => 'color-light-blue',
                'iconClass' => 'icon-cloud-ring',
            ],
            1 => [
                'name' => 'Harmony',
                'class' => 'Harmony',
                'description' => 'Meditate to regain 5 health and increase Air spells damage by 2 for next 2 turns.',
                'iconClass' => 'icon-triorb',
                'iconColor' => 'color-light-blue',
                'noTargetSpell' => true,
            ],
            2 => [
                'name' => 'No more air for you',
                'class' => 'NoMoreAirForYou',
                'description' => 'Deal 2 damage to target by taking all air or their lungs.',
                'iconClass' => 'icon-totem-head',
                'iconColor' => 'color-light-blue',
                'directTargetSpell' => 'enemy',
            ],
            3 => [
                'name' => 'Hard landing',
                'class' => 'HardLanding',
                'description' => 'Launch yourself to a location knocking away everyone around. Deal 1 damage to all units around landing location.',
                'iconClass' => 'icon-fire-dash',
                'iconColor' => 'color-light-blue',
            ],
            4 => [
                'name' => 'Quardro lightning',
                'class' => 'QuardroLightning',
                'description' => 'Deal 1 damage to 4 random units.',
                'iconClass' => 'icon-round-struck',
                'iconColor' => 'color-light-blue',
                'noTargetSpell' => true,
                'noAutoAnimationTrigger' => true,
            ],
            5 => [
                'name' => 'Lightning',
                'class' => 'Lightning',
                'description' => 'Strike a random target(includes you) with lightning for 8.',
                'iconClass' => 'icon-thunder-struck',
                'iconColor' => 'color-light-blue',
                'noTargetSpell' => true,
                'noAutoAnimationTrigger' => true,
            ],
            6 => [
                'name' => 'Sky fist',
                'class' => 'SkyFist',
                'description' => 'Hit a location dealing 3 damage to damage in place and 1 damage to all units around pushing them away.',
                'iconClass' => 'icon-fist',
                'iconColor' => 'color-light-blue',
            ],
            7 => [
                'name' => 'Lightning shield',
                'class' => 'LightingShield',
                'description' => 'Protect yourself with lighting that will zap one enemy near you for 1 damage for next 5 turns. And protect you from any Air damage',
                'iconClass' => 'icon-lighting-shield',
                'iconColor' => 'color-light-blue',
                'noTargetSpell' => true,
            ],
            8 => [
                'name' => 'Wind sword',
                'class' => 'WindSword',
                'description' => 'Hit the ground in a direction to knock all enemies asides and deal 1 damage',
                'iconClass' => 'icon-windy-stripes',
                'iconColor' => 'color-light-blue',
            ],
            9 => [
                'name' => 'Loot it all',
                'class' => 'LootItAll',
                'description' => 'Get all loot from all objects that you can see.',
                'iconClass' => 'icon-profit',
                'iconColor' => 'color-light-blue',
                'noTargetSpell' => true,
                'noAutoAnimationTrigger' => true,
            ],
            10 => [
                'name' => 'Push II',
                'class' => 'Push2',
                'description' => 'Push all enemies in ranges of 2 away and deal 2 damage.',
                'iconClass' => 'icon-wind-slap',
                'iconColor' => 'color-light-blue',
                'noTargetSpell' => true,
            ],
            11 => [
                'name' => 'Tesla trap',
                'class' => 'TeslaTrap',
                'description' => 'Create tesla trap at one of the locations, that will attack everyone around it.',
                'iconClass' => 'icon-tesla-coil',
                'iconColor' => 'color-light-blue',
            ],
            12 => [
                'name' => 'Rasengan',
                'class' => 'Rasengan2',
                'description' => 'Pure air energy that will travel in on direction damaging and knocking units.',
                'iconClass' => 'icon-rasengan',
                'iconColor' => 'color-light-blue',
            ],
            13 => [
                'name' => 'Chain lighting',
                'class' => 'ChainLighting',
                'description' => 'Hit a target enemy with lighting for 1 damage. Then lighting would jump to other targets dealing more damage with each target hit. Jumps two times.',
                'iconClass' => 'icon-heavy-lightning',
                'iconColor' => 'color-light-blue',
                'directTargetSpell' => 'enemy',
                'noAutoAnimationTrigger' => true,
            ],


        ],
        4 => [ // earth
            0 => [
                'name' => 'StoneFace',
                'class' => 'StoneFace',
                'description' => 'Heal yourself for 3 HP and get 3 points of armor',
                'iconClass' => 'icon-iron-mask',
                'iconColor' => 'color-brown',
                'noTargetSpell' => true,
            ],
            1 => [
                'name' => 'Ground shake',
                'class' => 'GroundShake',
                'description' => 'Deal 1 damage to all units in target area',
                'iconClass' => 'icon-stone-pile',
                'iconColor' => 'color-brown',
            ],
            2 => [
                'name' => 'Quicksand',
                'class' => 'Quicksand',
                'description' => 'All enemies will not be able to move for next turn.',
                'iconClass' => 'icon-quicksand',
                'iconColor' => 'color-brown',
                'noTargetSpell' => true,
                'noAutoAnimationTrigger' => true,
            ],
            3 => [
                'name' => 'Stone spear',
                'class' => 'StoneSpear',
                'description' => 'Deal 1 damage to target enemy. This enemy would be vulnerable to any next earth attack. (+2 damage)',
                'iconClass' => 'icon-stone-spear',
                'iconColor' => 'color-brown',
                'directTargetSpell' => 'enemy',
            ],
            4 => [
                'name' => 'Tunnel travel',
                'class' => 'TunnelTravel',
                'description' => 'Dig a tunnel and swap places with target unit.',
                'iconClass' => 'icon-dig-hole',
                'iconColor' => 'color-brown',
                'directTargetSpell' => 'enemy',
            ],
            5 => [
                'name' => 'Earth protection',
                'class' => 'EarthProtection',
                'description' => 'Heal yourself for 5. Get 3 armor and this turn you receive no damage.',
                'iconClass' => 'icon-field',
                'iconColor' => 'color-brown',
                'noTargetSpell' => true,
            ],
            6 => [
                'name' => 'Stalactites fall',
                'class' => 'StalactitesFall',
                'description' => 'Three huge stalactites are falling down from skies dealing 5 damage to impact location and 1 damage to everybody around. Could fall on your head as well.',
                'iconClass' => 'icon-stalactite',
                'iconColor' => 'color-brown',
                'noTargetSpell' => true,
                'noAutoAnimationTrigger' => true,
            ],
            7 => [
                'name' => 'Earthquake',
                'class' => 'Earthquake',
                'description' => 'Grand earthquake will deal 1-2 damage to all units.',
                'iconClass' => 'icon-peaks',
                'iconColor' => 'color-brown',
                'noTargetSpell' => true,
            ],
            8 => [
                'name' => 'Astonishing',
                'class' => 'Astonishing',
                'description' => 'Turn one enemy into a stone for 3 turns. All earth damage to him would be increased by 2.',
                'iconClass' => 'icon-spiked-armor',
                'iconColor' => 'color-brown',
                'directTargetSpell' => 'enemy',
            ],
            9 => [
                'name' => 'Wall up!',
                'class' => 'WallUp',
                'description' => 'Build a walls around yourself for 3 turns. Each wall will deal 1 damage to enemies on destruction.',
                'iconClass' => 'icon-stone-tower',
                'iconColor' => 'color-brown',
                'noTargetSpell' => true,
            ],
            10 => [
                'name' => 'Milestone hit',
                'class' => 'MilestoneHit',
                'description' => 'Deal 3 damage to a target.',
                'iconClass' => 'icon-stone-tablet',
                'iconColor' => 'color-brown',
                'directTargetSpell' => 'enemy',
            ],
            11 => [
                'name' => 'Rolling Stones',
                'class' => 'RollingStones',
                'description' => 'Deal 3 damage to all units in four directions.',
                'iconClass' => 'icon-striking-balls',
                'iconColor' => 'color-brown',
            ],
            //Eruption
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
        8 => [ // arcane
            0 => [
                'name' => 'Arcane missiles',
                'class' => 'ArcaneMissiles',
                'description' => 'Default description',
                'iconClass' => 'icon-flame',
                'directTargetSpell' => 'enemy',
            ],
        ],
    ],

    // TODO: fix chat bug on mobile


    'school-chances' => [
        1,1,1,1,1,1,1,1,   //fire
        2,2,2,2,2,2,2,2, // water
        3,3,3,3,3,3,3,3,     // air
        4,4,4,4,4,4,4,4,     // earth
        //  5,5,             // light
        //  6,6,             // death
//            7,7,
//            8,
//            9,
//            10,
    ],
    'schools' => [
        1 => ['name' => 'fire', 'icon' => 'icon-flame', 'color' => 'red'],
        2 => ['name' => 'water', 'icon' => 'icon-drop', 'color' => 'darkBlue'],
        3 => ['name' => 'air', 'icon' => 'icon-cloud-ring', 'color' => 'lightBlue'],
        4 => ['name' => 'earth', 'icon' => 'icon-rock', 'color' => 'brown'],
        5 => ['name' => 'light', 'icon' => 'icon-flame', 'color' => 'red'],
        6 => ['name' => 'death', 'icon' => 'icon-flame', 'color' => 'red'],
        7 => ['name' => 'nature', 'icon' => 'icon-flame', 'color' => 'red'],
        8 => ['name' => 'arcane', 'icon' => 'icon-abstract-119', 'color' => 'purple'],
//        9 => ['name' => 'blood', 'icon' => 'icon-flame'],
//        10 => ['name' => 'voodoo', 'icon' => 'icon-flame'],
//        11 => ['name' => 'demonology', 'icon' => 'icon-flame'],
//        12 => ['name' => 'dragon', 'icon' => 'icon-flame'],
//        13 => ['name' => 'spirit', 'icon' => 'icon-flame'],
//        14 => ['name' => 'shadow', 'icon' => 'icon-flame'],
//        15 => ['name' => 'steel', 'icon' => 'icon-flame'],
    ],

);