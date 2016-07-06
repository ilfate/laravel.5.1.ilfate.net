<?php
$cell_type_normal = 'normal';
$cell_type_passage = 'passage';
return array(

    // Сера
    // Горный хрусталь
    // Кость гоблина
    // Золотая монета (деньги грубо говоря)
    // Квартц
    // Уголь
    // Болотная трава
    // черная жемчужина
    // кость скелета
    // Аквамарин
    // Сердце каменного голема
    // Святая вода

//AggressiveMelee
    'list' => [
//        1 => ['type'=>'carrier', 'name'=>'Damaged scroll', 'stats'=>['usages' => '2-5'], 'icon' => 'icon-tied-scroll'],
//        2 => ['type'=>'carrier', 'name'=>'Bad scroll',  'stats'=>['usages' => '1-3'], 'icon' => 'icon-tied-scroll'],
//        301 => ['type'=>'ingredient', 'name'=>'Emerald',  'stats'=>['spell' => '30'], 'icon' => 'icon-emerald'],
//        302 => ['type'=>'ingredient', 'name'=>'Fire essence',  'stats'=>['spell' => '30', 'school' => ['fire' => 5]],
//                'icon' => 'icon-fire-bottle', 'iconColor' => 'color-red'],
//        303 => ['type'=>'ingredient', 'name'=>'Ore',  'stats'=>['spell' => '20', 'cooldown' => ['min' => 1, 'max' => 1]],
//                'icon' => 'icon-ore', 'iconColor' => 'color-red'],
//        304 => ['type'=>'ingredient', 'name'=>'Bone',  'stats'=>['spell' => '40', 'cooldown' => ['min' => 1, 'max' => 1]],
//                'icon' => 'icon-broken-bone', 'iconColor' => 'color-white'],

        1 => ['value' => 0, 'type'=>'ingredient', 'name'=>'Empty scroll',
              'icon' => 'icon-tied-scroll', 'iconColor' => 'color-brown'],
        2 => ['value' => 1, 'type'=>'ingredient', 'name'=>'Fish bone',
              'icon' => 'icon-fish-bone', 'iconColor' => 'color-white'],
        3 => ['value' => -1, 'type'=>'ingredient', 'name'=>'Carrot',
              'icon' => 'icon-carrot', 'iconColor' => 'color-red'],

        4 => ['value' => 2, 'type'=>'ingredient', 'name'=>'Bone',
               'icon' => 'icon-broken-bone', 'iconColor' => 'color-white'],
        5 => ['value' => 2, 'type'=>'ingredient', 'name'=>'Tooth',
              'icon' => 'icon-tooth', 'iconColor' => 'color-white'],
        6 => ['value' => -2, 'type'=>'ingredient', 'name'=>'Aubergine',
              'icon' => 'icon-aubergine', 'iconColor' => 'color-brown'],
        7 => ['value' => -2, 'type'=>'ingredient', 'name'=>'Beans',
              'icon' => 'icon-coffee-beans', 'iconColor' => 'color-brown'],

        8 => ['value' => 3, 'type'=>'ingredient', 'name'=>'Broken skull',
              'icon' => 'icon-broken-skull', 'iconColor' => 'color-white'],
        9 => ['value' => 3, 'type'=>'ingredient', 'name'=>'Beast skull',
              'icon' => 'icon-bestial-fangs', 'iconColor' => 'color-grey'],
        10 => ['value' => -3, 'type'=>'ingredient', 'name' => 'Garlic',
              'icon' => 'icon-garlic', 'iconColor' =>'color-white'],
        11 => ['value' => -3, 'type'=>'ingredient', 'name' => 'Corn',
              'icon' => 'icon-corn', 'iconColor' =>'color-yellow'],

        12 => ['value' => 4, 'type'=>'ingredient', 'name'=> 'Rune stone',
                'icon' => 'icon-rune-stone', 'iconColor' => 'color-grey-darker'],
        13 => ['value' => 4, 'type'=>'ingredient', 'name'=>'Broken tablet',
                'icon' => 'icon-broken-tablet', 'iconColor' => 'color-red-bright'],
        14 => ['value' => -4, 'type'=>'ingredient', 'name'=>'Mushroom',
                'icon' => 'icon-mushroom-gills', 'iconColor' => 'color-grey'],
        15 => ['value' => -4, 'type'=>'ingredient', 'name'=>'Super mushroom',
                'icon' => 'icon-super-mushroom', 'iconColor' => 'color-red-bright'],

        16 => ['value' => 5, 'type'=>'ingredient', 'name'=> 'Spiral shell',
                'icon' => 'icon-spiral-shell', 'iconColor' => 'color-white'],
        17 => ['value' => 5, 'type'=>'ingredient', 'name'=>'Ore',
              'icon' => 'icon-ore', 'iconColor' => 'color-red'],
        18 => ['value' => -5, 'type'=>'ingredient', 'name'=> 'Vine flower',
                'icon' => 'icon-vine-flower', 'iconColor' => 'color-green'],
        19 => ['value' => -5, 'type'=>'ingredient', 'name'=>'Vanilla flower',
              'icon' => 'icon-vanilla-flower', 'iconColor' => 'color-yellow'],

        20 => ['value' => 6, 'type'=>'ingredient', 'name'=>'Floating cristal',
              'icon' => 'icon-floating-crystal', 'iconColor' => 'color-green'],
        21 => ['value' => 6, 'type'=>'ingredient', 'name'=>'Sapphire',
              'icon' => 'icon-saphir', 'iconColor' => 'color-light-blue'],
        22 => ['value' => -6, 'type'=>'ingredient', 'name'=>'Clover',
                'icon' => 'icon-clover', 'iconColor' => 'color-green'],
        23 => ['value' => -6, 'type'=>'ingredient', 'name'=>'Lotus',
                'icon' => 'icon-lotus-flower', 'iconColor' => 'color-red-bright'],
        
//        16 => ['value' => 5, 'type'=>'ingredient', 'name'=> 'Emerald',
//                'icon' => 'icon-emerald', 'iconColor' => 'color-green'],
//       22 => ['value' => -6, 'type'=>'ingredient', 'name'=>'Strange potion',
//              'icon' => 'icon-potion-ball', 'iconColor' => 'color-brown'],

        1001 => ['type'=>'catalyst', 'name'=>'Fire essence', 'school' => 1,
              'icon' => 'icon-fire-bottle', 'iconColor' => 'color-red'],
        1002 => ['type'=>'catalyst', 'name'=>'Water essence', 'school' => 2,
              'icon' => 'icon-snow-bottle', 'iconColor' => 'color-dark-blue'],
        1003 => ['type'=>'catalyst', 'name'=>'Air essence', 'school' => 3,
              'icon' => 'icon-concentric-crescents', 'iconColor' => 'color-light-blue'],
        1004 => ['type'=>'catalyst', 'name'=>'Earth essence', 'school' => 4,
              'icon' => 'icon-drink-me', 'iconColor' => 'color-brown'],
    ],
    'item-types' => [
        ['name' => 'catalyst', 'icon' => 'icon-round-struck', 'class' => 'color-red'],
        ['name' => 'ingredient', 'icon' => 'icon-fizzing-flask', 'class' => 'color-dark-blue'],
    ],


);