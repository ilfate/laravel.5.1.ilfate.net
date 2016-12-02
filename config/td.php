<?php
$color = [
    'brown' => '#5E412F',
    'grey' =>  '#777777',
    'clay' =>  '#FCEBB6',
    'red' =>   '#FF8360',
    'blue' =>  '#529BCA',
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
        3 => ['name' => '2HP', 'min' => 2, 'max' => 2, 'types' => ['r2'], 'turns' => 5, 'skipTurns' => 5, 'newTower' => 'baseBlue'],
        4 => ['name' => '2HP', 'min' => 2, 'max' => 2, 'types' => ['r2'], 'turns' => 5, 'skipTurns' => 8],
        5 => ['name' => 'Diagonal 2HP', 'min' => 2, 'max' => 2, 'types' => ['d1'], 'turns' => 2, 'skipTurns' => 5],
        6 => ['name' => 'Fast 1HP', 'min' => 2, 'max' => 2, 'types' => ['f1'], 'turns' => 4, 'skipTurns' => 4],
        7 => ['name' => 'Boss 6HP', 'min' => 1, 'max' => 1, 'types' => ['b2'], 'turns' => 1, 'skipTurns' => 10],
        8 => ['name' => '4HP', 'min' => 3, 'max' => 4, 'types' => ['r3'], 'turns' => 5, 'skipTurns' => 15, 'newTower' => 'sniper'],
        9 => ['name' => 'Diagonal 7HP', 'min' => 3, 'max' => 3, 'types' => ['d2'], 'turns' => 3, 'skipTurns' => 10],
        10 => ['name' => 'Fast 8HP', 'min' => 4, 'max' => 4, 'types' => ['f2'], 'turns' => 3, 'skipTurns' => 10, 'newTower' => 'blueBolder'],
        11 => ['name' => 'Boss 16HP', 'min' => 1, 'max' => 1, 'types' => ['b3'], 'turns' => 1, 'skipTurns' => 16, 'newTower' => 'blueCanon'],
    ],
    'monsters' => [
        'b1' => ['health'=> 3, 'moneyAward'=> 10, 'color'=> $color['purple']],
        'b2' => ['health'=> 6, 'moneyAward'=> 10, 'color'=> $color['purple']],
        'r2' => ['health'=> 2, 'moneyAward'=> 2, 'color'=> $color['red']],
        'r3' => ['health'=> 4, 'moneyAward'=> 2, 'color'=> $color['red']],
        'd1' => ['health'=> 2, 'moneyAward'=> 3, 'color'=> $color['bordo'], 'diagonal' => true],
        'd2' => ['health'=> 7, 'moneyAward'=> 5, 'color'=> $color['bordo'], 'diagonal' => true],
        'f1' => ['health'=> 1, 'moneyAward'=> 2, 'color'=> $color['purple'], 'fast' => true],
        'f2' => ['health'=> 8, 'moneyAward'=> 6, 'color'=> $color['purple'], 'fast' => true],
    ],
        'towersAccess' => [
        3 => ['baseBlue'],
    ],
    'towers' => [
        'baseBlue' => ['color' => $color['blue'], 'price'=> 12, 'damage' => 1, 'targets'=> 1,
               'attackPattern'=>[[-1,1], [-1,-1], [1,1], [1,-1], [-1, 0], [0, -1], [1, 0], [0, 1]]
        ],
        'blueBolder' => ['color' => $color['blue'], 'price'=> 46, 'damage' => 3, 'targets'=> 1,
               'attackPattern'=>[[-1,1], [-1,-1], [1,1], [1,-1], [-1, 0], [0, -1], [1, 0], [0, 1]]
        ],
        'blueCanon' => ['color' => $color['blue'], 'price'=> 68, 'damage' => 5, 'targets'=> 1,
               'attackPattern'=>[[-1,1], [-1,-1], [1,1], [1,-1], [-2, 0], [0, -2], [2, 0], [0, 2]]
        ],
        'sniper' => ['color' => $color['green'], 'price'=> 28, 'damage' => 3,
               'attackPattern'=>[[0,-1]]
        ],
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