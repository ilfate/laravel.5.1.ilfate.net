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
        3 => ['name' => '2HP', 'min' => 2, 'max' => 2, 'types' => ['r2'], 'turns' => 5, 'skipTurns' => 5, 'newTower' => 'TBaseBlue'],
        4 => ['name' => '2HP', 'min' => 2, 'max' => 2, 'types' => ['r2'], 'turns' => 5, 'skipTurns' => 8],
        5 => ['name' => 'Diagonal 2HP', 'min' => 2, 'max' => 2, 'types' => ['d1'], 'turns' => 2, 'skipTurns' => 5],
        6 => ['name' => 'Fast 2HP', 'min' => 2, 'max' => 2, 'types' => ['f1'], 'turns' => 4, 'skipTurns' => 4],
        7 => ['name' => 'Boss 10HP', 'min' => 1, 'max' => 1, 'types' => ['b2'], 'turns' => 1, 'skipTurns' => 10],
        8 => ['name' => '5HP', 'min' => 3, 'max' => 4, 'types' => ['r3'], 'turns' => 5, 'skipTurns' => 15,
              'newTower' => ['TSniper1', 'TSniper2', 'TSniper3', 'TSniper4', 'TSniper5', 'TSniper6', 'TSniper7', 'TSniper8']],
        9 => ['name' => 'Diagonal 6HP', 'min' => 3, 'max' => 3, 'types' => ['d2'], 'turns' => 3, 'skipTurns' => 10],
        10 => ['name' => 'Fast 7HP', 'min' => 4, 'max' => 4, 'types' => ['f2'], 'turns' => 3, 'skipTurns' => 10, 'newTower' => 'TBlueBolder'],
        11 => ['name' => 'Boss 25HP', 'min' => 1, 'max' => 1, 'types' => ['b3'], 'turns' => 1, 'skipTurns' => 16, 'newTower' => 'TBlueCanon'],
    ],
    'monsters' => [
        'b1' => ['health'=> 3, 'moneyAward'=> 10, 'color'=> $color['purple']],
        'b2' => ['health'=> 10, 'moneyAward'=> 15, 'color'=> $color['purple']],
        'b3' => ['health'=> 25, 'moneyAward'=> 30, 'color'=> $color['purple']],
        'r2' => ['health'=> 2, 'moneyAward'=> 2, 'color'=> $color['red']],
        'r3' => ['health'=> 5, 'moneyAward'=> 2, 'color'=> $color['red']],
        'd1' => ['health'=> 2, 'moneyAward'=> 3, 'color'=> $color['bordo'], 'diagonal' => true],
        'd2' => ['health'=> 6, 'moneyAward'=> 5, 'color'=> $color['bordo'], 'diagonal' => true],
        'f1' => ['health'=> 2, 'moneyAward'=> 2, 'color'=> $color['purple'], 'fast' => true],
        'f2' => ['health'=> 7, 'moneyAward'=> 6, 'color'=> $color['purple'], 'fast' => true],
    ],
    'towersAccess' => [
        15 => ['TBasic2'],
        18 => ['TBasic3'],
        20 => ['TDSniper1', 'TDSniper2', 'TDSniper3', 'TDSniper4'],
        23 => ['TRound'],
        27 => ['TTor1','TTor2','TTor3','TTor4'],
        30 => ['TBiPlank1','TBiPlank2'],
        38 => ['Thorse1','Thorse2','Thorse3','Thorse4'],
        50 => ['TSpace'],
        64 => ['TCore'],
        78 => ['TRadic1' ,'TRadic2' ,'TRadic3' ,'TRadic4'],
    ],
    'towers' => [
        'TBaseBlue' => ['color' => $color['blue'], 'price'=> 12, 'damage' => 1, 'targets'=> 1,
               'attackPattern'=>[[-1,1], [-1,-1], [1,1], [1,-1], [-1, 0], [0, -1], [1, 0], [0, 1]]
        ],
        'TBlueBolder' => ['color' => $color['blue'], 'price'=> 46, 'damage' => 3, 'targets'=> 1,
               'attackPattern'=>[[-1,1], [-1,-1], [1,1], [1,-1], [-1, 0], [0, -1], [1, 0], [0, 1]]
        ],
        'TBlueCanon' => ['color' => $color['blue'], 'price'=> 68, 'damage' => 5, 'targets'=> 1,
               'attackPattern'=>[[-1,1], [-1,-1], [1,1], [1,-1], [-2, 0], [0, -2], [2, 0], [0, 2]]
        ],
        'TSniper1' => ['image' => 'TSniper', 'color' => $color['green'], 'price'=> 28, 'damage' => 3, 'attackPattern'=>[[0,-1]]],
        'TSniper3' => ['image' => 'TSniper', 'color' => $color['green'], 'price'=> 28, 'damage' => 3, 'attackPattern'=>[[1, 0]], 'rotate' => 1],
        'TSniper2' => ['image' => 'TSniper', 'color' => $color['green'], 'price'=> 28, 'damage' => 3, 'attackPattern'=>[[0, 1]], 'rotate' => 2],
        'TSniper4' => ['image' => 'TSniper', 'color' => $color['green'], 'price'=> 28, 'damage' => 3, 'attackPattern'=>[[-1,0]], 'rotate' => 3],
        'TSniper5' => ['image' => 'Tfork', 'color' => $color['green'], 'price'=> 28, 'damage' => 1, 'attackPattern'=>[[-1,-1],[0,-1],[1,-1]]],
        'TSniper6' => ['image' => 'Tfork', 'color' => $color['green'], 'price'=> 28, 'damage' => 1, 'attackPattern'=>[[1,-1],[1,0],[1,1]], 'rotate' => 1],
        'TSniper7' => ['image' => 'Tfork', 'color' => $color['green'], 'price'=> 28, 'damage' => 1, 'attackPattern'=>[[1,1],[0,1],[-1,1]], 'rotate' => 2],
        'TSniper8' => ['image' => 'Tfork', 'color' => $color['green'], 'price'=> 28, 'damage' => 1, 'attackPattern'=>[[-1,1],[-1,0],[-1,-1]], 'rotate' => 3],

        'Tdiagonal' => ['color' => $color['green'], 'price'=> 10, 'damage' => 1, 'attackPattern'=>[
            [-1,1], [-1,-1], [1,1], [1,-1]
        ]],

        'TBasic2' => ['color' => $color['green'], 'price'=> 30, 'damage' => 3, 'attackPattern'=>[
            [-1,0], [0,-1], [0,1], [1,0]
        ]],
        'TBasic3' => ['color' => $color['green'], 'price'=> 55, 'damage' => 2, 'attackPattern'=>[
            [-1,1], [-1,-1], [1,1], [1,-1], [-1, 0], [0, -1], [1, 0], [0, 1]
        ]],

        'TDSniper1' => ['image' => 'TDSniper','color' => $color['gold'], 'price'=> 75, 'damage' => 20, 'cooldown' => 1, 'attackPattern'=>[[1,-1]]],
        'TDSniper2' => ['image' => 'TDSniper','color' => $color['gold'], 'price'=> 75, 'damage' => 20, 'cooldown' => 1, 'attackPattern'=>[[1,1]], 'rotate' => 1],
        'TDSniper3' => ['image' => 'TDSniper','color' => $color['gold'], 'price'=> 75, 'damage' => 20, 'cooldown' => 1, 'attackPattern'=>[[-1,1]], 'rotate' => 2],
        'TDSniper4' => ['image' => 'TDSniper','color' => $color['gold'], 'price'=> 75, 'damage' => 20, 'cooldown' => 1, 'attackPattern'=>[[-1,-1]], 'rotate' => 3],

        'TRound' => ['color' => $color['green'], 'price'=> 125, 'damage' => 4, 'attackPattern'=>[
            [-1,1], [-1,-1], [1,1], [1,-1], [-1, 0], [0, -1], [1, 0], [0, 1], [-2,0], [0,-2], [2,0], [0,2]
        ]],

        'TTor1' => ['image' => 'TTor','color' => $color['green'], 'price'=> 180, 'damage' => 14, 'attackPattern'=>[[-1,-1], [0,-1], [1,0]]],
        'TTor2' => ['image' => 'TTor','color' => $color['green'], 'price'=> 180, 'damage' => 14, 'attackPattern'=>[[1,-1],[1,0],[0,1]], 'rotate' => 1],
        'TTor3' => ['image' => 'TTor','color' => $color['green'], 'price'=> 180, 'damage' => 14, 'attackPattern'=>[[1,1],[0,1],[-1,0]], 'rotate' => 2],
        'TTor4' => ['image' => 'TTor','color' => $color['green'], 'price'=> 180, 'damage' => 14, 'attackPattern'=>[[-1,1],[-1,0],[0,-1]], 'rotate' => 3],

        'TBiPlank1' => ['image' => 'TBiPlank', 'color' => $color['green'], 'price'=> 222, 'damage' => 11, 'attackPattern'=>[
            [-1,-2],[-1,-1],[-1,0],[-1,1],[-1,2],[1,-2],[1,-1],[1,0],[1,1],[1,2]
        ], 'rotate' => 1],
        'TBiPlank2' => ['image' => 'TBiPlank', 'color' => $color['green'], 'price'=> 222, 'damage' => 11, 'attackPattern'=>[
            [-2,-1],[-1,-1],[0,-1],[1,-1],[2,-1],[-2,1],[-1,1],[0,1],[1,1],[2,1]
        ]],

        'Thorse1' => ['image' => 'Thorse', 'color' => $color['blue'], 'price'=> 300, 'damage' => 66, 'targets' => 1, 'attackPattern'=>[
            [1,0],[2,0],[3,0],[4,0],[5,0],[6,0]
        ]],
        'Thorse2' => ['image' => 'Thorse', 'color' => $color['blue'], 'price'=> 300, 'damage' => 66, 'targets' => 1, 'attackPattern'=>[
            [0,1],[0,2],[0,3],[0,4],[0,5],[0,6]
        ], 'rotate' => 1],
        'Thorse3' => ['image' => 'Thorse', 'color' => $color['blue'], 'price'=> 300, 'damage' => 66, 'targets' => 1, 'attackPattern'=>[
            [-1,0],[-2,0],[-3,0],[-4,0],[-5,0],[-6,0]
        ], 'rotate' => 2],
        'Thorse4' => ['image' => 'Thorse', 'color' => $color['blue'], 'price'=> 300, 'damage' => 66, 'targets' => 1, 'attackPattern'=>[
            [0,-1],[0,-2],[0,-3],[0,-4],[0,-5],[0,-6]
        ], 'rotate' => 3],

        'TSpace' => ['image' => 'TSpace', 'color' => $color['blue'], 'price'=> 500, 'damage' => 120, 'targets' => 1, 'attackPattern'=>[
            [-2,-2],[2,-2],[2,2],[-2,2]
        ]],

        'TCore' => ['image' => 'TCore', 'color' => $color['gold'], 'price'=> 700, 'damage' => 185, 'targets' => 1, 'cooldown' => 1, 'attackPattern'=>[
            [0,-2],[2,0],[0,2],[-2,0]
        ]],

        'TRadic1' => ['image' => 'TRadic', 'color' => $color['green'], 'price'=> 1000, 'damage' => 50, 'attackPattern'=>[
            [-1,-1],[0,-2],[1,-3],[2,-4]
        ]],
        'TRadic2' => ['image' => 'TRadic', 'color' => $color['green'], 'price'=> 1000, 'damage' => 50, 'attackPattern'=>[
            [1,-1],[2,0],[3,1],[4,2]
        ], 'rotate' => 1],
        'TRadic3' => ['image' => 'TRadic', 'color' => $color['green'], 'price'=> 1000, 'damage' => 50, 'attackPattern'=>[
            [1,1],[0,2],[-1,3],[-2,4]
        ], 'rotate' => 2],
        'TRadic4' => ['image' => 'TRadic', 'color' => $color['green'], 'price'=> 1000, 'damage' => 50, 'attackPattern'=>[
            [-1,1],[-2,0],[-3,-1],[-4,-2]
        ], 'rotate' => 3],
    ],
    'item-types' => [
        ['name' => 'catalyst', 'icon' => 'icon-round-struck', 'class' => 'color-red'],
        ['name' => 'ingredient', 'icon' => 'icon-fizzing-flask', 'class' => 'color-dark-blue'],
    ],


);