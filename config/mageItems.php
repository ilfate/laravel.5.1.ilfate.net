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

        1 => ['value' => 0, 'type'=>'ingredient', 'name'=>'Empty scroll', 'icon' => 'icon-tied-scroll', 'iconColor' => 'color-brown'],
        2 => ['value' => 1, 'type'=>'ingredient', 'name'=>'Carrot', 'icon' => 'icon-carrot', 'iconColor' => 'color-red'],
        3 => ['value' => -1, 'type'=>'ingredient', 'name'=>'Emerald', 'icon' => 'icon-emerald'],
        4 => ['value' => 2, 'type'=>'ingredient', 'name'=>'Aubergine', 'icon' => 'icon-aubergine'],
        5 => ['value' => -2, 'type'=>'ingredient', 'name'=>'Garlic', 'icon' => 'icon-garlic', 'iconColor' =>'color-white'],
        6 => ['value' => 3, 'type'=>'ingredient', 'name'=>'Ore',
                'icon' => 'icon-ore', 'iconColor' => 'color-red'],
        7 => ['value' => -3, 'type'=>'ingredient', 'name'=>'Bone',
                'icon' => 'icon-broken-bone', 'iconColor' => 'color-white'],

        1001 => ['type'=>'catalyst', 'name'=>'Fire essence', 'school' => 1,
              'icon' => 'icon-fire-bottle', 'iconColor' => 'color-red'],
    ],
    'item-types' => [
        ['name' => 'catalyst', 'icon' => 'icon-round-struck', 'class' => 'color-red'],
        ['name' => 'ingredient', 'icon' => 'icon-fizzing-flask', 'class' => 'color-dark-blue'],
    ],


);