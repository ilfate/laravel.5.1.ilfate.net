<?php
$color = [
    'brown' => '#5E412F',
    'grey' =>  '#777777',
    'clay' =>  '#FCEBB6',
    'red' =>   '#FF8360',
    'bordo' => '#711F1F',
    'green' => '#069E2D',
    'yellow'=> '#F0A830',
    'white' => '#ffffff',
    'purple'=> '#c700d6',
    'gold'  => '#F0A830',
];
return array(


    'waves' => [
        2 => ['name' => 'Boss 3HP', 'min' => 1, 'max' => 1, 'types' => ['b1'], 'turns' => 1, 'skipTurns' => 10],
        3 => ['name' => '2HP', 'min' => 2, 'max' => 2, 'types' => ['r2'], 'turns' => 5, 'skipTurns' => 5, 'newTower' => 'diagonal'],
        4 => ['name' => '2HP', 'min' => 2, 'max' => 2, 'types' => ['r2'], 'turns' => 5, 'skipTurns' => 8],
        5 => ['name' => 'Diagonal 2HP', 'min' => 2, 'max' => 2, 'types' => ['d1'], 'turns' => 2, 'skipTurns' => 5],
    ],
    'monsters' => [
        'b1' => ['health'=> 3, 'moneyAward'=> 10, 'color'=> $color['purple']],
        'r2' => ['health'=> 2, 'moneyAward'=> 2, 'color'=> $color['red']],
        'd1' => ['health'=> 2, 'moneyAward'=> 3, 'color'=> $color['bordo'], 'diagonal' => true],
    ],
    'towers' => [
        'Tdiagonal' => ['color' => $color['green'], 'price'=> 10, 'damage' => 1, 'attackPattern'=>[
            [-1,1], [-1,-1], [1,1], [1,-1]
        ]],
        'diagonal' => ['color' => $color['green'], 'price'=> 40, 'damage' => 3, 'attackPattern'=>[
            [-1,1], [-1,-1], [1,1], [1,-1]
        ]],
    ],
    'item-types' => [
        ['name' => 'catalyst', 'icon' => 'icon-round-struck', 'class' => 'color-red'],
        ['name' => 'ingredient', 'icon' => 'icon-fizzing-flask', 'class' => 'color-dark-blue'],
    ],


);